{block name="frontend_freju_cart_discounts"}
    {if isset($details) && is_array($details)}

        <style>
            .freju--checkout__discount {
                width: 100%;
                display: flex;
                flex-wrap: wrap;
                align-items: center;
                justify-content: space-between;
                padding: 24px;
                margin: 12px 0;
                border: 2px solid #39589a;
                color: #39589a;
                font-size: 16px;
                font-weight: 600;
                background: rgba(57,88,154,0.3);
                border-radius: 5px;
            }
            .freju--checkout__discount_value, .freju--checkout__discount_name {
                width: 50%;
            }
            .freju--checkout__discount_value {
                text-align: right;
            }
            .freju--checkout__discount_description {
                width: 100%;
                margin-top: 12px;
                font-weight: 500;
            }
        </style>

        {if $cashbacks}

            {foreach $details as $item}

                {$productDetails = $item['additional_details'].attributes.discounts->get('discounts')}
                {$discounts = $productDetails['discounts']}

                {foreach $discounts as $discount}
                    {if $discount['cashback']}

                        <div class="freju--checkout__discount">
                            <div class="freju--checkout__discount_name">Cashback: {$discount['name']}</div>

                            <div class="freju--checkout__discount_value">(- {$discount['absoluteValue']} €)</div>

                            <div class="freju--checkout__discount_description">Den Cashback-Betrag erhalten Sie nach Kauf und Registierung des Produkts bei {$item['additional_details']['supplierName']}</div>
                        </div>

                    {/if}
                {/foreach}
            {/foreach}

        {else}

            {$discounts = $details['discounts']}

            {foreach $discounts as $discount}

                    {if !$discount['discount_precalculated'] && !$discount['cashback']}

                        <div class="freju--checkout__discount">
                            <div class="freju--checkout__discount_name">{$discount['name']}</div>

                            <div class="freju--checkout__discount_value">- {$discount['absoluteValue']} €</div>
                        </div>

                    {/if}

            {/foreach}

        {/if}

    {/if}

{/block}