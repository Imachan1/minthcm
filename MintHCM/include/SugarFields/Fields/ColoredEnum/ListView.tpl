{if !empty($parentFieldArray.$col)}
    {assign var="item" value=$parentFieldArray.$col}
    {if !empty($vardef.options_list)}
        {assign var="itemVal" value=$vardef.options_list.$item}
    {else}
        {assign var="itemVal" value=$item}
    {/if}
    {* {assign var="fieldKey" value=$parentFieldArray.$col} *}

    {if $fieldKey == "Hired" || $fieldKey == "open" || $fieldKey == "Completed"}
        {assign var="status_color" value="green"}
    {elseif $fieldKey == "Rejected"}
        {assign var="status_color" value="red"}
    {elseif $fieldKey == "for_approval" || $fieldKey == "New" || $fieldKey == "new" || $fieldKey == "Not Started"}
        {assign var="status_color" value="blue"}
    {elseif $fieldKey == "CandidateResignation" || $fieldKey == "close" || $fieldKey == "Deferred"}
        {assign var="status_color" value="grey"}
    {else} {* in_progress i inne *}
        {assign var="status_color" value="yellow"}
    {/if}

    <div class="colored_status colored_status_{$status_color}">{$itemVal}</div>

{/if}