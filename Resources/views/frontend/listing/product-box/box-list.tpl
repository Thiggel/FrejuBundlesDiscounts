{extends file="parent:frontend/listing/product-box/box-list.tpl"}

{block name='frontend_listing_box_article_description'}
    {$smarty.block.parent}

    {if $sArticle.attributes.discounts}
	<div style="width:50%">
        {include file="frontend/modules/discounts.tpl" discounts=$sArticle.attributes.discounts->get('discounts') short="true"}
   </div>
   {/if}
{/block}
