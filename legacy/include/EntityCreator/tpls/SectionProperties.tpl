{$start_sectionproperties}
{foreach from=$fields item=field}
    /**
{if $field.isId}
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="CUSTOM")
     * @ORM\CustomIdGenerator(class=UuidGenerator::class)
{/if}
{if $field.columnAttributes}
     * @ORM\Column({$field.columnAttributes})
{/if}
{if $field.attributes}
{foreach from=$field.attributes item=attribute}
     * {$attribute}
{/foreach}
{/if}
     */
    protected ${$field.name};

{/foreach}
{foreach from=$relationshipFields item=relationshipField}
    /**
{foreach from=$relationshipField.attributes item=attribute}
     * {$attribute}
{/foreach}
     */
    protected {if $relationshipField.isCollection}Collection {/if}${$relationshipField.name};

{/foreach}
{$end_sectionproperties}