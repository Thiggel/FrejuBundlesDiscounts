{extends file="parent:frontend/detail/content/buy_container.tpl"}

{block name='frontend_detail_index_buy_container'}
    {if $sArticle.attributes.discounts}
        {include file="frontend/modules/discounts.tpl" discounts=$sArticle.attributes.discounts->get('discounts')}
    {/if}

    {$smarty.block.parent}
{/block}