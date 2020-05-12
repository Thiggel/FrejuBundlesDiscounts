{extends file="parent:frontend/checkout/cart_item.tpl"}

{block name='frontend_checkout_cart_item_product'}
    {$smarty.block.parent}

    {if $sBasketItem['additional_details'].attributes.discounts}
        {include file="frontend/modules/cart-discounts.tpl" discounts=$sBasketItem['additional_details'].attributes.discounts->get('discounts') quantity=$sBasketItem.quantity}
    {/if}
{/block}
