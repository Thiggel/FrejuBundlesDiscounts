{extends file="parent:frontend/listing/product-box/box-basic.tpl"}

{block name='frontend_listing_box_article_description'}
    {$smarty.block.parent}

    {if $sArticle.attributes.discounts}
        {include file="frontend/modules/discounts.tpl" discounts=$sArticle.attributes.discounts->get('discounts') short="true"}
    {/if}
{/block}
