{block name="frontend_freju_cart_discounts_sum"}

    {foreach $basket.content as $item}

        {$productDetails = $item['additional_details'].attributes.discounts->get('discounts')}
        {$discounts = $productDetails['discounts']}
        {$amount = $basket.Amount}

        {foreach $discounts as $discount}
            {if !$discount['discount_precalculated'] && !$discount['cashback']}

                {$amount = ($amount*100 - floatval(str_replace(',', '', $discount['absoluteValue'])))/100}

            {/if}
        {/foreach}

        <div class="entry--value block">
            {$amount|currency}{s name="Star" namespace="frontend/listing/box_article"}{/s}
        </div>

        <div class="entry--value block is--no-star">
            {if $sAmountWithTax && $sUserData.additional.charge_vat}{$sAmountWithTax|currency}{else}{$sAmount|currency}{/if}
        </div>
    {/foreach}
{/block}