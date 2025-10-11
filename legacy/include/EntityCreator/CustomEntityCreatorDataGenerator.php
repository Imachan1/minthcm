<?php

require_once 'include/EntityCreator/EntityCreatorDataGenerator.php';

class CustomEntityCreatorDataGenerator extends EntityCreatorDataGenerator
{

    const CUSTOM_SUFFIX = '_cstm';

    public function __construct(string $moduleName, array $vardefs)
    {
        parent::__construct($moduleName, $vardefs);
        $this->buildCustomFields();
    }

    protected function buildCustomFields()
    {
        if (!$this->hasCustomTable()) {
            return;
        }
        $customFields = [];

        foreach ($this->vardefs['fields'] as $fieldName => $fieldDef) {
            if (empty($this->vardefs["fields"][$fieldName]["source"])
                || "custom_fields" != $this->vardefs["fields"][$fieldName]["source"]) {
                continue;
            }
            unset($fieldDef['source']);
            $customFields[$fieldName] = $fieldDef;
        }
        if (empty($customFields)) {
            return;
        }
        $this->setCustomData($customFields);
    }

    protected function setCustomData(array $customFields)
    {
        $this->data['table'] = $this->vardefs['table'] . self::CUSTOM_SUFFIX;
        $this->data['fields'] = $customFields;
        $this->data['relationships'] = [];
        $this->data['indices'] = [];
        $this->data['doctrineEntity'] = [];
        $this->data['generate_custom_entity'] = !empty($customFields);
    }
}
