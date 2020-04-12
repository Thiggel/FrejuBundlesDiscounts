{extends file="parent:frontend/listing/product-box/product-image.tpl"}

{block name='frontend_listing_box_article_image_media'}
    {$smarty.block.parent}

    {if $sArticle.attributes.freeAddArticles}
        {include file="frontend/modules/free-add-articles.tpl" freeAddArticles=$sArticle.attributes.freeAddArticles->get('freeAddArticles') className="listing"}
    {/if}
{/block}