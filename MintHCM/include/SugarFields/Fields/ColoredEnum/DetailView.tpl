{if is_string({{sugarvar key='options' string=true}})}
<input type="hidden" class="sugar_field" id="{{sugarvar key='name'}}" value="{ {{sugarvar key='options' string=true}} }">
{ {{sugarvar key='options' string=true}} }
{else}
{capture name=getVal assign=fieldKey}{ {{sugarvar key='value' string=true}} }{/capture}
<input type="hidden" class="sugar_field" id="{{sugarvar key='name'}}" value="{{$fieldKey}}">


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

<div class="colored_status colored_status_{$status_color}">{ {{sugarvar key='options' string=true}}[{{sugarvar key='value' string=true}}]}</div>

{/if}
{{if !empty($displayParams.enableConnectors)}}
{{sugarvar_connector view='DetailView'}}
{{/if}}