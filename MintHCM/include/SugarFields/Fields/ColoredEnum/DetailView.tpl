{if is_string({{sugarvar key='options' string=true}})}
<input type="hidden" class="sugar_field" id="{{sugarvar key='name'}}" value="{ {{sugarvar key='options' string=true}} }">
{ {{sugarvar key='options' string=true}} }
{else}
{capture name=getVal assign=fieldKey}{ {{sugarvar key='value' string=true}} }{/capture}
<input type="hidden" class="sugar_field" id="{{sugarvar key='name'}}" value="{{$fieldKey}}">

<div class="colored_status colored_status_{{$displayParams.value_color}}">{ {{sugarvar key='options' string=true}}[{{sugarvar key='value' string=true}}]}</div>

{/if}
{{if !empty($displayParams.enableConnectors)}}
{{sugarvar_connector view='DetailView'}}
{{/if}}