{block name="frontend_freju_discounts"}
    {if isset($discounts) && is_array($discounts)}
        <style>
            .freju--discounts {
                margin-bottom: 16px;
                border-radius: 8px;
                overflow: hidden;
            }
            .freju--discounts__discount {
                display: flex;
                flex-wrap: wrap;
            }
            .freju--discounts__discount_cell {
                width: 50%;
                font-size: 14px;
                padding: 12px 16px;
                background: rgb(198,206,224);
                color: #292929;
            }
            .freju--discounts__discount_cell:first-child, .freju--discounts__discount_cell:nth-child(2) {
                padding-top: 12px;
            }
            .freju--discounts__discount_cell:last-child, .freju--discounts__discount_cell:nth-last-child(2) {
                padding-bottom: 12px;
            }
            .freju--discounts__discount_cell:nth-child(2n) {
                text-align: right;
            }
            .freju--discounts__discount_cell.freeAddArticle, .freju--discounts__discount_cell.discount {
                background: #fcfcfc;
            }
            .freju--discounts__discount_cell a {
                color: #292929;
                font-weight: 600;
            }

        </style>

        <div class="freju--discounts">
            <div class="freju--discounts__discount">
                <div class="freju--discounts__discount_cell">Unser regulärer Preis</div>
                <div class="freju--discounts__discount_cell">{$discounts['prePrice']|currency}</div>
                {if !$short}
                    {foreach $discounts['freeAddArticles'] as $article}
                        <div class="freju--discounts__discount_cell freeAddArticle">Ersparnis durch gratis <a href="{$article['url']}">{$article['name']}</a></div>
                        <div class="freju--discounts__discount_cell freeAddArticle">- {$article['price']|currency}</div>
                    {/foreach}

                    {foreach $discounts['discounts']['precalculated']['€'] as $discount}
                        <div class="freju--discounts__discount_cell discount">{$discount['name']}</a></div>
                        <div class="freju--discounts__discount_cell discount">- {$discount['absoluteValue']|currency}</div>
                    {/foreach}

                    {foreach $discounts['discounts']['precalculated']['%'] as $discount}
                        <div class="freju--discounts__discount_cell discount">{$discount['name']}</a></div>
                        <div class="freju--discounts__discount_cell discount">- {$discount['absoluteValue']|currency}</div>
                    {/foreach}

                    {foreach $discounts['discounts']['postcalculated']['€'] as $discount}
                        <div class="freju--discounts__discount_cell discount">{$discount['name']}</a></div>
                        <div class="freju--discounts__discount_cell discount">- {$discount['absoluteValue']|currency}</div>
                    {/foreach}

                    {foreach $discounts['discounts']['postcalculated']['%'] as $discount}
                        <div class="freju--discounts__discount_cell discount">{$discount['name']}</a></div>
                        <div class="freju--discounts__discount_cell discount">- {$discount['absoluteValue']|currency}</div>
                    {/foreach}
                {/if}

                <div class="freju--discounts__discount_cell">Sie zahlen an uns</div>
                <div class="freju--discounts__discount_cell">{$discounts['payablePrice']|currency}</div>

                {if !$short}
                    {foreach $discounts['discounts']['cashback']['€'] as $discount}
                        <div class="freju--discounts__discount_cell discount">{$discount['name']}</a></div>
                        <div class="freju--discounts__discount_cell discount">- {$discount['absoluteValue']|currency}</div>
                    {/foreach}

                    {foreach $discounts['discounts']['cashback']['%'] as $discount}
                        <div class="freju--discounts__discount_cell discount">{$discount['name']}</a></div>
                        <div class="freju--discounts__discount_cell discount">- {$discount['absoluteValue']|currency}</div>
                    {/foreach}
                {/if}

                {if $discounts['postPrice'] !== $discounts['payablePrice']}
                    <div class="freju--discounts__discount_cell">Preis nach allen Aktionen</div>
                    <div class="freju--discounts__discount_cell">{$discounts['postPrice']|currency}</div>
                {/if}
            </div>
        </div>

    {/if}
{/block}

