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
                background: #ebebeb;
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
                <div class="freju--discounts__discount_cell">{$discounts['prePrice']} €</div>

                {foreach $discounts['freeAddArticles'] as $article}
                    <div class="freju--discounts__discount_cell freeAddArticle">Inklusive Gratis <a href="{$article['url']}">{$article['name']}</a></div>
                    <div class="freju--discounts__discount_cell freeAddArticle">{$article['price']} €</div>
                {/foreach}

                {foreach $discounts['discounts'] as $discount}
                    {if !$discount['cashback']}
                        <div class="freju--discounts__discount_cell discount">{$discount['name']}</a></div>
                        <div class="freju--discounts__discount_cell discount">- {$discount['absoluteValue']} €</div>
                    {/if}
                {/foreach}

                <div class="freju--discounts__discount_cell">Sie zahlen an uns</div>
                <div class="freju--discounts__discount_cell">{$discounts['payablePrice']} €</div>

                {foreach $discounts['discounts'] as $discount}
                    {if $discount['cashback']}
                        <div class="freju--discounts__discount_cell discount">{$discount['name']}</a></div>
                        <div class="freju--discounts__discount_cell discount">- {$discount['absoluteValue']} €</div>
                    {/if}
                {/foreach}

                <div class="freju--discounts__discount_cell">Preis nach allen Aktionen</div>
                <div class="freju--discounts__discount_cell">{$discounts['postPrice']} €</div>
            </div>
        </div>

    {/if}
{/block}

