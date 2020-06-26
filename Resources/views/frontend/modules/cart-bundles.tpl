{block name="frontend_freju_cart_bundles"}
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
        .freju__bundle_discount {
            margin-right: 12px;
            margin-left: 12px;
            width: calc(100% - 24px);
        }
    </style>

    {if isset($sBasket) && is_array($sBasket)}

        {$bundleBonus = 0}
        {foreach $sBasket.content as $item}

            {if $item['additional_details'].attributes.bundleInBasket}
                {$bundle = $item['additional_details'].attributes.bundle->get('bundle')}
                {$bundleBonus = $bundleBonus + $bundle['absoluteBonus']}
            {/if}
        {/foreach}

        {if $bundleBonus > 0}
            <div class="freju--checkout__discount freju__bundle_discount">
                <div class="freju--checkout__discount_name">Bundle-Bonus <a href="javascript:modalBundleBonus();"><i class="icon--service"></i></a>: </div>

                <div class="freju--checkout__discount_value"> - {$bundleBonus|currency}</div>
            </div>
        {/if}

    {/if}
{/block}
