<?php
namespace Api\V8\JsonApi\Helper;

use Api\V8\BeanDecorator\BeanManager;
use Api\V8\JsonApi\Response\AttributeResponse;

class AttributeObjectHelper
{
    /**
     * @var BeanManager
     */
    protected $beanManager;

    /**
     * @param BeanManager $beanManager
     */
    public function __construct(BeanManager $beanManager)
    {
        $this->beanManager = $beanManager;
    }

    /**
     * @param \SugarBean $bean
     * @param array|null $fields
     *
     * @return AttributeResponse
     */
    public function getAttributes(\SugarBean $bean, $fields = null)
    {
        $bean->fixUpFormatting();

        $current_time_zone = date_default_timezone_get();
        date_default_timezone_set('UTC');

        // using the ISO 8601 format for dates
        $attributes = array_map(function ($value) {
            return is_string($value)
                ? (\DateTime::createFromFormat('Y-m-d H:i:s', $value)
                    ? date(\DateTime::ATOM, strtotime($value))
                    : html_entity_decode(htmlspecialchars_decode($value), ENT_QUOTES))
                : $value;
        }, $bean->toArray());

        date_default_timezone_set($current_time_zone);

        if ($fields !== null) {
            $attributes = array_intersect_key($attributes, array_flip($fields));
        }
        // MintHCM #87119 start
        $attributes['acl_access'] = [
            'edit' => $bean->ACLAccess('edit'),
            'view' => $bean->ACLAccess('view'),
            'delete' => $bean->ACLAccess('delete'),
        ];
        // MintHCM #87119 end

        unset($attributes['id']);

        return new AttributeResponse($attributes);
    }
}
