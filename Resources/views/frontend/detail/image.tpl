{extends file="parent:frontend/detail/image.tpl"}

{block name='frontend_detail_image'}
    {if $sArticle.attributes.freeAddArticles}
        {include file="frontend/modules/free-add-articles.tpl" freeAddArticles=$sArticle.attributes.freeAddArticles->get('freeAddArticles')}
    {/if}

    {$smarty.block.parent}
{/block}
