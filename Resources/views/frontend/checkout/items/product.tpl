{extends file="parent:frontend/checkout/items/product.tpl"}

{block name='frontend_checkout_cart_item_details_title'}
    {$smarty.block.parent}

    {if $sBasketItem['additional_details'].attributes.discounts}
        {include file="frontend/modules/cart-discount-labels.tpl" details=$sBasketItem['additional_details'].attributes.discounts->get('discounts')}
    {/if}
{/block}