{extends file="parent:frontend/detail/content/buy_container.tpl"}

{block name='frontend_detail_rich_snippets_brand'}
    {if $sArticle.attributes.freeAddArticles}
        {include file="frontend/modules/free-add-articles.tpl" freeAddArticles=$sArticle.attributes.freeAddArticles->get('freeAddArticles')}
    {/if}

    <style>
        .freju-bundles-discounts-detail-container {


        }
    </style>

    <div class="freju-bundles-discounts-detail-container">
        <div class="freju-bundles-discounts-detail-container__bundles">
            {if $sArticle.attributes.bundles}
                <h2>Sparkits</h2>
                {include file="frontend/modules/bundles.tpl" bundles=$sArticle.attributes.bundles->get('bundles')}
            {/if}
        </div>

        <div class="freju-bundles-discounts-detail-container__discounts">
            {if $sArticle.attributes.discounts}
                <h2>Rabatte</h2>
                {include file="frontend/modules/discounts.tpl" discounts=$sArticle.attributes.discounts->get('discounts')}
            {/if}
        </div>
    </div>

    {$smarty.block.parent}
{/block}
