<div id="footer">
    <p class="footer-info">App 2018 - Jakub Bogacz</p>
</div>
{if isset($footer_gap) && !empty($footer_gap)}
{foreach $footer_gap as $type}
{foreach $type as $content}
{$content}
{/foreach}
{/foreach}
{/if}