{if isset($alerts[0]) && !empty($alerts[0])}
<div id="alerts">
    {foreach $alerts[0] as $alert}
    <div class="alert alert-{$alert.alerts_type}" role="alert">
        {if $alert.alerts_type == "success"}
        <span class="glyphicon glyphicon-ok-sign"></span>
        {elseif $alert.alerts_type == "warning"}
        <span class="glyphicon glyphicon-question-sign"></span>
        {elseif $alert.alerts_type == "danger"}
        <span class="glyphicon glyphicon-remove-sign"></span>
        {elseif $alert.alerts_type == "info"}
        <span class="glyphicon glyphicon-info-sign"></span>
        {/if}
        <button type="button" class="close" data-dismiss="alert" data-alert-id="{$alert.id}" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <div class="alert-content">
            {if !empty($alert.alerts_title)}
            <h5><strong>{$alert.alerts_title}</strong></h5>
            {/if}
            <p>{$alert.alerts_message}</p>
        </div>
    </div>
    {/foreach}
</div>
{/if}