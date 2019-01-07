{if isset($alerts) && !empty($alerts)}
<div id="alerts">
    {foreach $alerts as $alert}
    <div class="alert alert-{$alert.alert_type}" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        {if !empty($alert.alert_title)}
        <h5><strong>{$alert.alert_title}</strong></h5>
        {/if}
        <p>{$alert.alert_message}</p>
    </div>
    {/foreach}
</div>
{/if}