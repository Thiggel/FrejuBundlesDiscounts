{extends file="parent:frontend/checkout/cart_footer.tpl"}


{* Basket sum *}
{block name='frontend_checkout_cart_footer_field_labels_sum'}

    {$amount = $sBasket.Amount}

    {foreach $sBasket.content as $item}

        {$productDetails = $item['additional_details'].attributes.discounts->get('discounts')}
        {$discounts = $productDetails['discounts']}

        {foreach $discounts as $discount}
            {if !$discount['discount_precalculated'] && !$discount['cashback']}

                {$amount = ($amount*100 - floatval(str_replace(',', '', $discount['absoluteValue'])))/100}

            {/if}
        {/foreach}
    {/foreach}

    <li class="list--entry block-group entry--sum">

        {block name='frontend_checkout_cart_footer_field_labels_sum_label'}
            <div class="entry--label block">
                {s name="CartFooterLabelSum"}{/s}
            </div>
        {/block}

        {block name='frontend_checkout_cart_footer_field_labels_sum_value'}
            <div class="entry--value block">
                {$amount|currency}{s name="Star" namespace="frontend/listing/box_article"}{/s}
            </div>
        {/block}
    </li>
{/block}

{* Shipping costs *}
{block name='frontend_checkout_cart_footer_field_labels_shipping'}
    <li class="list--entry block-group entry--shipping">

        {block name='frontend_checkout_cart_footer_field_labels_shipping_label'}
            <div class="entry--label block">
                {s name="CartFooterLabelShipping"}{/s}
            </div>
        {/block}

        {block name='frontend_checkout_cart_footer_field_labels_shipping_value'}
            <div class="entry--value block">
                {$sShippingcosts|currency}{s name="Star" namespace="frontend/listing/box_article"}{/s}
            </div>
        {/block}
    </li>
{/block}

{* Total sum *}
{block name='frontend_checkout_cart_footer_field_labels_total'}

    {$amount = $sAmount}

    {if $sAmountWithTax && $sUserData.additional.charge_vat}{$amount = $sAmountWithTax}{/if}

    {foreach $sBasket.content as $item}

        {$productDetails = $item['additional_details'].attributes.discounts->get('discounts')}
        {$discounts = $productDetails['discounts']}

        {foreach $discounts as $discount}
            {if !$discount['discount_precalculated'] && !$discount['cashback']}

                {$amount = ($amount*100 - floatval(str_replace(',', '', $discount['absoluteValue'])))/100}

            {/if}
        {/foreach}
    {/foreach}

    <li class="list--entry block-group entry--total">

        {block name='frontend_checkout_cart_footer_field_labels_total_label'}
            <div class="entry--label block">
                {s name="CartFooterLabelTotal"}{/s}
            </div>
        {/block}

        {block name='frontend_checkout_cart_footer_field_labels_total_value'}
            <div class="entry--value block is--no-star">
                {$amount|currency}
            </div>
        {/block}
    </li>
{/block}

{* Total net *}
{block name='frontend_checkout_cart_footer_field_labels_totalnet'}
    {if $sUserData.additional.charge_vat}
        {$amount = $sAmount}

        {if $sAmountWithTax && $sUserData.additional.charge_vat}{$amount = $sAmountWithTax}{/if}

        {foreach $sBasket.content as $item}

            {$productDetails = $item['additional_details'].attributes.discounts->get('discounts')}
            {$discounts = $productDetails['discounts']}

            {foreach $discounts as $discount}
                {if !$discount['discount_precalculated'] && !$discount['cashback']}

                    {$amount = ($amount*100 - floatval(str_replace(',', '', $discount['absoluteValue'])))/100}

                {/if}
            {/foreach}
        {/foreach}

        {$amount = $amount / 1.19}

        <li class="list--entry block-group entry--totalnet">

            {block name='frontend_checkout_cart_footer_field_labels_totalnet_label'}
                <div class="entry--label block">
                    {s name="CartFooterTotalNet"}{/s}
                </div>
            {/block}

            {block name='frontend_checkout_cart_footer_field_labels_totalnet_value'}
                <div class="entry--value block is--no-star">
                    {$amount|currency}
                </div>
            {/block}
        </li>
    {/if}
{/block}

{* Taxes *}
{block name='frontend_checkout_cart_footer_field_labels_taxes'}
    {if $sUserData.additional.charge_vat}
        {foreach $sBasket.sTaxRates as $rate => $value}
            {$amount = $sAmount}

            {if $sAmountWithTax && $sUserData.additional.charge_vat}{$amount = $sAmountWithTax}{/if}

            {foreach $sBasket.content as $item}

                {$productDetails = $item['additional_details'].attributes.discounts->get('discounts')}
                {$discounts = $productDetails['discounts']}

                {foreach $discounts as $discount}
                    {if !$discount['discount_precalculated'] && !$discount['cashback']}

                        {$amount = ($amount*100 - floatval(str_replace(',', '', $discount['absoluteValue'])))/100}

                    {/if}
                {/foreach}
            {/foreach}

            {$amount = $amount - $amount/1.19}

            {block name='frontend_checkout_cart_footer_field_labels_taxes_entry'}
                <li class="list--entry block-group entry--taxes">

                    {block name='frontend_checkout_cart_footer_field_labels_taxes_label'}
                        <div class="entry--label block">
                            {s name="CartFooterTotalTax"}{/s}
                        </div>
                    {/block}

                    {block name='frontend_checkout_cart_footer_field_labels_taxes_value'}
                        <div class="entry--value block is--no-star">
                            {$amount|currency}
                        </div>
                    {/block}
                </li>
            {/block}
        {/foreach}
    {/if}
{/block}