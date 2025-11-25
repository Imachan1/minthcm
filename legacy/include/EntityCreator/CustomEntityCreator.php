<?php

require_once 'vendor/autoload.php';
require_once 'include/EntityCreator/EntityCreator.php';
require_once 'include/EntityCreator/CustomEntityCreatorDataGenerator.php';

class CustomEntityCreator extends EntityCreator
{
    protected const FILE_FORMAT_PHP = '_custom.php';
    protected $isCustom = true;

    public function __construct(string $moduleName, array $vardefs)
    {
        $this->moduleName = $moduleName . CustomEntityCreatorDataGenerator::CUSTOM_SUFFIX;
        $this->vardefs = $vardefs;
    }

    public function run(): void
    {
        $this->data = (new CustomEntityCreatorDataGenerator($this->moduleName, $this->vardefs))->getData();
        if (!$this->data["generate_custom_entity"]) {
            return;
        }
        $this->data['isCustom'] = $this->isCustom;
        $this->generateEntity();
    }
}
