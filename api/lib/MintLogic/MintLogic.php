<?php

namespace MintHCM\Lib\MintLogic;
use MintHCM\Lib\MintLogic\Exceptions\ValidationException;

class MintLogic
{
    private $bean;
    private $defs;

    public function __construct(\SugarBean $bean)
    {
        $this->bean = clone $bean;
        $this->defs = include(__DIR__ . "/Modules/{$bean->module_name}/logicdefs.php") ?? [];
    }

    public function getInitial()
    {
        return [
            'rules' => $this->getRules(Hook::INIT),
        ];
    }

    public function getChanged($triggerFields)
    {
        return [
            'rules' => $this->getRules(Hook::CHANGE, $triggerFields),
        ];
    }

    public function validateBean()
    {
        $result = [
            'isValid' => true,
            'error' => null,
        ];
        if (!empty($this->defs['bean']['validation'])) {
            foreach ($this->defs['bean']['validation'] as $validator) {
                try {
                    self::calculateExpression($validator, $this->bean);
                } catch (ValidationException $e) {
                    $result['isValid'] = false;
                    $result['error'] = $e->getMessage();
                    break;
                }
            }
        }
        return $result;
    }

    private function getRules(Hook $hook = Hook::ALL, $triggerFields = null)
    {
        $rules = [];
        foreach ($this->getAllRules() as $key => $rule) {
            if ($hook !== Hook::ALL && !in_array(Hook::ALL, $rule['hooks']) && !in_array($hook, $rule['hooks'])) {
                continue;
            }
            if (!empty($triggerFields) && !empty($rule['triggerFields']) && !array_intersect($triggerFields, $rule['triggerFields'])) {
                continue;
            }

            $isTriggered = !isset($rule['trigger']) || self::calculateExpression($rule['trigger'], $this->bean);

            $rules[] = [
                'key' => $key,
                'triggerFields' => $rule['triggerFields'] ?? [],
                'trigger' => $isTriggered,
                'logic' => $isTriggered ? $this->calculateLogic($rule) : [],
                'hooks' => $rule['hooks'] ?? [],
            ];
        }
        return $rules;
    }

    private function getAllRules(): array
    {
        $rules = $this->defs['rules'] ?? [];
        $requiredFields = $this->getRequiredFieldsFromVardefs();
        if (!empty($requiredFields)) {
            array_unshift($rules, [
                'hooks' => [Hook::INIT],
                'logic' => [
                    'required' => array_fill_keys($requiredFields, true),
                ],
            ]);
        }
        return $rules;
    }

    private function getRequiredFieldsFromVardefs()
    {
        $requiredFields = [];
        foreach ($this->bean->field_defs as $field => $vardef) {
            if (isset($vardef['required']) && $vardef['required'] === true && $vardef['name'] !== 'id') {
                $requiredFields[] = $field;
            }
        }
        return $requiredFields;
    }

    private function calculateLogic($rule)
    {
        $logic = [
            'errors' => [],
            'visible' => [],
            'readonly' => [],
            'required' => [],
            'update' => [],
        ];

        // Visible
        $logic['visible'] = self::calculateExpression($rule['logic']['visible'], $this->bean) ?? [];
        foreach ($logic['visible'] as $field => $isVisible) {
            if (!$isVisible) {
                $this->bean->{$field} = null;
                $logic['update'][$field] = null;
            }
        }

        // Readonly
        $logic['readonly'] = self::calculateExpression($rule['logic']['readonly'], $this->bean) ?? [];
        foreach ($logic['readonly'] as $field => $isReadonly) {
            if ($isReadonly) {
                $this->bean->{$field} = $this->bean->fetched_row[$field] ?? null;
                $logic['update'][$field] = $this->bean->{$field};
            }
        }

        // Required
        $logic['required'] = self::calculateExpression($rule['logic']['required'], $this->bean) ?? [];

        // Validation
        $validation = self::calculateExpression($rule['logic']['validation'], $this->bean) ?? [];
        foreach ($validation as $field => $validator) {
            if (!empty($logic['errors'][$field])) {
                continue;
            }
            try {
                self::calculateExpression($validator, $this->bean, $field);
            } catch (ValidationException $e) {
                $logic['errors'][$field] = $e->getMessage();
            }
        }

        // Update
        $update = self::calculateExpression($rule['logic']['update'], $this->bean) ?? [];
        foreach ($update as $field => $value) {
            $this->bean->{$field} = $value;
            $logic['update'][$field] = $this->bean->{$field};
        }

        return $logic;
    }

    private static function calculateExpression($expr, $bean, $field = null)
    {
        if (empty($expr)) {
            return null;
        }
        if (is_array($expr) && array_is_list($expr)) {
            foreach ($expr as $item) {
                $result = self::calculateExpression($item, $bean, $field);
                if ($result === false) {
                    return $result;
                }
            }
        } else if (is_string($expr) && class_exists($expr)) {
            $object = new $expr($bean);
            if ($object instanceof Validator) {
                !empty($field) ? $object->validate($bean, $field) : $object->validate($bean);
            }
        } else if (is_callable($expr)) {
            return $expr($bean);
        } else if (!empty($expr['op'])) {
            return Formula::executeOperator($expr['op'], $bean, ...$expr['args']);
        }
        return $expr;
    }
}
