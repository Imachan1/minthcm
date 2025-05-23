<?php


namespace MintHCM\Lib\Search\ElasticSearch;

use Symfony\Component\Yaml\Parser as YamlParser;

class ModulePrefixer
{
    protected $module;

    private $map_config;

    public function __construct(string $module)
    {
        $this->module = $module;
    }

    public function modify(string $field_name): string
    {
        $mappings = $this->getDefaultMapParams($this->module);
        $field_parts = explode('.', $field_name);

        if (isset($mappings['mappings']['properties'][$this->module . '__' . $field_parts[0]])) {
            foreach ($field_parts as $key => $part) {
                if ($part == 'keyword') {
                    continue;
                }
                $field_parts[$key] = $this->module . '__' . $part;
            }

            $field_parts = implode('.', $field_parts);

            return $field_parts;
        }
        return $field_name;
    }

    protected function getDefaultMapParams($module)
    {
        if (empty($this->map_config)) {
            $file = realpath(__DIR__ . '/../../../../legacy/lib/Search/ElasticSearch/defaultParams.yml');

            $parse = new YamlParser();
            $this->map_config = $parse->parseFile($file);
        }

        return ['mappings' => $this->map_config['mappings'][$module]];
    }
}
