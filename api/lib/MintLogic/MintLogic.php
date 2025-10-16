<?php

namespace MintHCM\Lib\MintLogic;

use MintHCM\Data\MintBean;
use MintHCM\Lib\MintLogic\Exceptions\ValidationException;

class MintLogic
{
    private $bean;
    private $defs;

    public function __construct($bean)
    {
        if (!$bean instanceof \SugarBean && !$bean instanceof MintBean) {
            throw new \InvalidArgumentException("Bean must be an instance of SugarBean or MintBean");
        }
        $this->bean = clone $bean;
        $this->defs = include __DIR__ . "/Modules/{$bean->module_name}/logicdefs.php" ?? [];
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
            if (Hook::ALL !== $hook && !in_array(Hook::ALL, $rule['hooks']) && !in_array($hook, $rule['hooks'])) {
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
        $initialLogic = $this->getInitialLogic();
        if (!empty($initialLogic)) {
            array_unshift($rules, [
                'hooks' => [Hook::ALL],
                'trigger' => true,
                'logic' => $initialLogic,
            ]);
        }
        return $rules;
    }

    private function getInitialLogic()
    {
        $initialLogic = [];
        $requiredFields = $this->getRequiredFieldsFromVardefs();
        if (!empty($requiredFields)) {
            $initialLogic['required'] = array_fill_keys($requiredFields, true);
        }
        $readonlyFields = $this->getReadonlyFieldsFromVardefs();
        if (!empty($readonlyFields)) {
            $initialLogic['readonly'] = array_fill_keys($readonlyFields, true);
        }
        $functionOptionsFields = $this->getFunctionOptionsFieldsFromVardefs();
        if (!empty($functionOptionsFields)) {
            $initialLogic['options'] = $functionOptionsFields;
        }
        return $initialLogic;
    }

    private function getRequiredFieldsFromVardefs(): array
    {
        $requiredFields = [];
        foreach ($this->bean->field_defs as $field => $vardef) {
            if (isset($vardef['required']) && true === $vardef['required'] && 'id' !== $vardef['name']) {
                $requiredFields[] = $field;
            }
        }
        return $requiredFields;
    }

    private function getReadonlyFieldsFromVardefs(): array
    {
        $readonlyFields = [];
        foreach ($this->bean->field_defs as $field => $vardef) {
            if (isset($vardef['readonly']) && true === $vardef['readonly']) {
                $readonlyFields[] = $field;
            }
        }
        return $readonlyFields;
    }

    private function getFunctionOptionsFieldsFromVardefs(): array
    {
        $functionOptionsFields = [];
        foreach ($this->bean->field_defs as $field => $vardef) {
            if (isset($vardef['type']) && in_array($vardef['type'], ['enum', 'multienum']) && isset($vardef['function'])) {
                if (!empty($vardef['function']['include'])) {
                    require_once $vardef['function']['include'];
                }
                $function_name = $vardef['function']['name'] ?? '';
                if (!empty($function_name)) {
                    $result = call_user_func($function_name, $this->bean, $field, $this->bean->{$field} ?? '', 'MintLogic', $vardef['function']['additional_params']);
                    if (!empty($result)) {
                        $functionOptionsFields[$field] = $result;
                    }
                }
            }
        }
        return $functionOptionsFields;
    }

    private function calculateLogic($rule)
    {
        $logic = [
            'errors' => [],
            'visible' => [],
            'readonly' => [],
            'required' => [],
            'update' => [],
            'options' => [],
        ];

        // Update
        $update = self::calculateExpression($rule['logic']['update'], $this->bean) ?? [];
        foreach ($update as $field => $value) {
            $this->bean->{$field} = $value;
            $logic['update'][$field] = $this->bean->{$field};
        }

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

        // Options
        $options = self::calculateExpression($rule['logic']['options'], $this->bean) ?? [];
        foreach ($options as $field => $option) {
            $logic['options'][$field] = self::calculateExpression($option, $this->bean);
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
                if (false === $result) {
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
