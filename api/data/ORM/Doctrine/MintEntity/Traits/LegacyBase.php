<?php

namespace MintHCM\Data\ORM\Doctrine\MintEntity\Traits;

use MintHCM\Data\BeanFactory;
use MintHCM\Data\MintBean;

trait LegacyBase
{
    /** @var MintBean */
    protected static $mint_bean;

    public function getMintBean(): MintBean
    {
        if (!empty(static::$mint_bean)) {
            return static::$mint_bean;
        }

        static::$mint_bean = BeanFactory::getBean($this->getModuleName(), $this->getId());
        return static::$mint_bean;
    }

    protected function fillLegacyBean(): void
    {
        $bean = $this->getMintBean();
        foreach (get_object_vars($this) as $property => $value) {
            $defs = $bean->field_defs[$property] ?? null;
            if (empty($defs)) {
                continue;
            }

            if ($property === 'id') {
                $lazy_value = is_object($value) && method_exists($value, 'toString') ? true : false;
                
                $id = $lazy_value ? $value->toString() : (is_string($value) ? $value : null);
                if (!empty($id) && empty($bean->id)) {
                    $bean->new_with_id = true;
                }
                $bean->id = $id;
                continue;
            }

            //TODO Przerobić/usystematyzować typy za pomocą Doctrine - zrobić w zagadnieniu z datami 
            switch ($defs['type']) {
                case 'bool':
                    $value = (bool) $value;
                    break;
                case 'int':
                    $value = (int) $value;
                    break;
                case 'float':
                    $value = (float) $value;
                    break;
                case 'date':
                    if ($value instanceof \DateTime) {
                        $value = $value->format('Y-m-d');
                    } else {
                        $value = (string) $value;
                    }
                    break;
                case 'datetime':
                case 'datetimecombo':
                    if ($value instanceof \DateTime) {
                        $value = $value->format('Y-m-d H:i:s');
                    } else {
                        $value = (string) $value;
                    }
                    break;
                case 'link':
                    continue 2;
                default:
                    $value = (string) $value;
                    break;
            }

            $bean->$property = $value;
        }
    }
}
