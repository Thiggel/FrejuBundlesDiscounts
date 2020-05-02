{extends file="parent:frontend/detail/data.tpl"}

{block name='frontend_detail_data_price_default'}
    {if $sArticle.attributes.discounts}
        {$discounts = $sArticle.attributes.discounts->get('discounts')}
        {$price = $discounts['payablePrice']}
        <span class="price--content content--default">
            <meta itemprop="price" content="{$price|replace:',':'.'}">
            {if $sArticle.priceStartingFrom && !$sArticle.liveshoppingData}{s name='ListingBoxArticleStartsAt' namespace="frontend/listing/box_article"}{/s} {/if}{$price|currency} {s name="Star" namespace="frontend/listing/box_article"}{/s}
        </span>
    {else}
        <span class="price--content content--default">
            <meta itemprop="price" content="{$sArticle.price|replace:',':'.'}">
            {if $sArticle.priceStartingFrom && !$sArticle.liveshoppingData}{s name='ListingBoxArticleStartsAt' namespace="frontend/listing/box_article"}{/s} {/if}{$sArticle.price|currency} {s name="Star" namespace="frontend/listing/box_article"}{/s}
        </span>
    {/if}
{/block}
