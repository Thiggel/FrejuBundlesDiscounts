{extends file="parent:frontend/checkout/items/product.tpl"}

{* Product unit price *}
{block name='frontend_checkout_cart_item_price'}
    <div class="panel--td column--unit-price is--align-right">

        {if !$sBasketItem.modus}
            {block name='frontend_checkout_cart_item_unit_price_label'}
                <div class="column--label unit-price--label">
                    {s name="CartColumnPrice" namespace="frontend/checkout/cart_header"}{/s}
                </div>
            {/block}

            {if $sBasketItem['additional_details'].attributes.discounts}
                {$discounts = $sBasketItem['additional_details'].attributes.discounts->get('discounts')}
                {$systemPrice = $discounts['systemPrice']}
                {$systemPrice|currency}{block name='frontend_checkout_cart_tax_symbol'}{s name="Star" namespace="frontend/listing/box_article"}{/s}{/block}
            {else}
                {$sBasketItem.price|currency}{block name='frontend_checkout_cart_tax_symbol'}{s name="Star" namespace="frontend/listing/box_article"}{/s}{/block}
            {/if}

        {/if}
    </div>
{/block}

{* Accumulated product price *}
{block name='frontend_checkout_cart_item_total_sum'}
    <div class="panel--td column--total-price is--align-right">
        {block name='frontend_checkout_cart_item_total_price_label'}
            <div class="column--label total-price--label">
                {s name="CartColumnTotal" namespace="frontend/checkout/cart_header"}{/s}
            </div>
        {/block}

        {if $sBasketItem['additional_details'].attributes.discounts}
            {$discounts = $sBasketItem['additional_details'].attributes.discounts->get('discounts')}
            {$systemPrice = $discounts['systemPrice']}
            {$amount = $systemPrice * $sBasketItem.quantity}
            {$amount|currency}{block name='frontend_checkout_cart_tax_symbol'}{s name="Star" namespace="frontend/listing/box_article"}{/s}{/block}
        {else}
            {$sBasketItem.amount|currency}{block name='frontend_checkout_cart_tax_symbol'}{s name="Star" namespace="frontend/listing/box_article"}{/s}{/block}
        {/if}
    </div>
{/block}

{block name='frontend_checkout_cart_item_details_title'}
    {$smarty.block.parent}

    {if $sBasketItem['additional_details'].attributes.discounts}
        {include file="frontend/modules/cart-discount-labels.tpl" details=$sBasketItem['additional_details'].attributes.discounts->get('discounts')}
    {/if}
{/block}
