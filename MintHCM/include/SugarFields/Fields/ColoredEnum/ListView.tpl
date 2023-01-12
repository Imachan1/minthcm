{if !empty($parentFieldArray.$col)}
    {assign var="item" value=$parentFieldArray.$col}
    {if !empty($vardef.options_list)}
        {assign var="itemVal" value=$vardef.options_list.$item}
    {else}
        {assign var="itemVal" value=$item}
    {/if}
    {* {assign var="fieldKey" value=$parentFieldArray.$col} *}

    <div class="colored_status colored_status_{$value_color}">{$itemVal}</div>

{/if}