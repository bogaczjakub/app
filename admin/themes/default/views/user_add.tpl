<div class="col-xs-12">
    {if !empty($page_forms[0])}
        {foreach $page_forms[0] as $key => $form}
            {$form}
        {/foreach}
    {/if}
</div>