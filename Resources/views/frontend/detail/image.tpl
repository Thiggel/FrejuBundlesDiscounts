{extends file="parent:frontend/detail/image.tpl"}

<style>
    .navigation-main {
        z-index: 99999999999;
    }

    .js--overlay.is--open {
        z-index: 9999999;
    }

    .container--ajax-cart {
        z-index: 99999999;
    }
</style>

{block name='frontend_detail_image_thumbs'}
    {if $sArticle.attributes.freeAddArticles}
        {include file="frontend/modules/free-add-articles.tpl" freeAddArticles=$sArticle.attributes.freeAddArticles->get('freeAddArticles')}
    {/if}

    {$smarty.block.parent}
{/block}
