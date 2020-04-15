{extends file="parent:frontend/detail/content/buy_container.tpl"}

{block name='frontend_detail_rich_snippets_brand'}
{if $sArticle.attributes.freeAddArticles}
        {include file="frontend/modules/free-add-articles.tpl" freeAddArticles=$sArticle.attributes.freeAddArticles->get('freeAddArticles')}
    {/if}    

{if $sArticle.attributes.discounts}
        {include file="frontend/modules/discounts.tpl" discounts=$sArticle.attributes.discounts->get('discounts')}
    {/if}

    {$smarty.block.parent}
{/block}