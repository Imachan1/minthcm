<?php

require_once 'vendor/autoload.php';
require_once 'include/EntityCreator/EntityCreatorDataGenerator.php';

class EntityCreator
{
    public const ENTITY_NAMESPACE = 'MintHCM\\Api\\Entities';
    private const ENTITY_FOLDER_PATH = '../api/app/Entities/';

    public const REPOSITORY_FOLDER_PATH = "MintHCM\\Api\\Repositories\\";

    private const TPL_DIR_PATH = '/tpls';

    private const SECTIONS = [
        'SectionUse',
        'SectionRepository',
        'SectionProperties',
        'SectionMethods',
    ];

    private $vardefs;
    private $moduleName;
    private $data;

    public function __construct(string $moduleName, array $vardefs)
    {
        $this->moduleName = $moduleName;
        $this->vardefs = $vardefs;
    }

    public function run(): void
    {
        $this->data = (new EntityCreatorDataGenerator($this->moduleName, $this->vardefs))->getData();
        $this->generateEntity();
    }

    private function generateEntity(): void
    {
        $file_path = self::ENTITY_FOLDER_PATH . $this->moduleName . '.php';
        $smarty = $this->prepareSmartyTemplate();
        
        if ($this->entityExists()) {
            $this->updateExistingEntity($file_path, $smarty);
        } else {
            $this->createNewEntity($file_path, $smarty);
        }
    }

    private function prepareSmartyTemplate(): Smarty
    {
        $smarty = new Smarty();
        $smarty->setTemplateDir(dirname(__FILE__) . self::TPL_DIR_PATH);
        $smarty->assign($this->data);
        
        foreach (self::SECTIONS as $section) {
            $smarty->assign('start_' . strtolower($section), $this->getStartCommentForSection($section));
            $smarty->assign('end_' . strtolower($section), $this->getEndCommentForSection($section));
        }
        
        return $smarty;
    }

    private function updateExistingEntity(string $file_path, Smarty $smarty): void
    {
        $class_code = file_get_contents($file_path);
        if ($class_code === false || !is_writable($file_path)) {
            $GLOBALS['log']->fatal("Cannot read or write to file: {$file_path}");
            throw new Exception("Cannot read or write to file: {$file_path}");
        }

        foreach (self::SECTIONS as $section) {
            $new_section_code = $smarty->fetch($this->getTplPath($section));
            $pattern = '/' . preg_quote($this->getStartCommentForSection($section), '/') . '.*?' . preg_quote($this->getEndCommentForSection($section), '/') . '/s';
            $class_code = preg_replace($pattern, $new_section_code, $class_code);
        }

        file_put_contents($file_path, $class_code);
    }

    protected function buildIndexes()
    {
        if (! empty($this->vardefs['indices'])) {
            $this->data['indexes'] = [];
            foreach ($this->vardefs['indices'] as $index) {
                if (isset($index['fields']) && is_array($index['fields'])) {
                    $this->data['indexes'][] = [
                        'name' => $index['name'],
                        'columns' => implode('", "', $index['fields']),
                    ];
                }
            }
        } else {
            $this->data['indexes'] = [];
        }
    }

    protected function buildAdditionalUseStatements()
    {
        foreach ($this->data['relationshipFields'] as $relationshipField) {
            if ($relationshipField['isCollection']) {
                $this->data['additionalUseStatements'][] = 'use Doctrine\\Common\\Collections\\ArrayCollection';
                $this->data['additionalUseStatements'][] = 'use Doctrine\\Common\\Collections\\Collection';
                break;
            }
        }
    }

    protected function buildConstructorFields()
    {
        foreach ($this->data['relationshipFields'] as $relationshipField) {
            if ($relationshipField['isCollection']) {
                $collection = $relationshipField['relation_type'] === 'one-to-many' ? 'Collection' : 'ArrayCollection';
                $this->data['constructorFields'][] = '$this->' . $relationshipField['name'] . ' = new ' . $collection . '();';
            }
        }
    }

    private function createNewEntity(string $file_path, Smarty $smarty): void
    {
        foreach (self::SECTIONS as $section) {
            $smarty->assign(strtolower($section), $this->getTplPath($section));
        }

        $class_code = $smarty->fetch($this->getTplPath('Entity'));

        if (!is_dir(self::ENTITY_FOLDER_PATH)) {
            mkdir(self::ENTITY_FOLDER_PATH, 0755, true);
        }
        file_put_contents($file_path, $class_code);
    }

    private function entityExists(): bool
    {
        return file_exists(self::ENTITY_FOLDER_PATH . $this->moduleName . '.php');
    }

    private function getTplPath(string $file_name = 'Entity'): string
    {
        $module_file = dirname(__FILE__) . self::TPL_DIR_PATH . '/' . $this->moduleName . '/' . $file_name . '.tpl';
        if (file_exists($module_file)) {
            return $module_file;
        }
        return dirname(__FILE__) . self::TPL_DIR_PATH . '/' . $file_name . '.tpl';
    }

    private function getStartCommentForSection(string $section): string
    {
        return "// Auto-generated {$section} section start";
    }

    private function getEndCommentForSection(string $section): string
    {
        return "// Auto-generated {$section} section end";
    }
}
