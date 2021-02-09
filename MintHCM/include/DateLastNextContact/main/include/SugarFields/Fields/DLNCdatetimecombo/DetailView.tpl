{capture assign="has_access"}{sugar_run_helper include="custom/include/evSubscription/DateLastNextContactSubscription.php" func="getDLNCaccess" displayType="DetailView"}{/capture}
{if $has_access=="1"}
{if strlen({{sugarvar key='value' string=true}}) <= 0}
{assign var="value" value={{sugarvar key='default_value' string=true}} }
{else}
{assign var="value" value={{sugarvar key='value' string=true}} }
{/if} 
<span class="sugar_field" id="{{sugarvar key='name'}}">{{sugarvar key='value'}}</span>
{{if !empty($displayParams.enableConnectors)}}
{if !empty($value)}
{{sugarvar_connector view='DetailView'}}
{/if}
{{/if}}
{else}
    <span>{$APP.LBL_NO_ACCESS}</span>
{/if}