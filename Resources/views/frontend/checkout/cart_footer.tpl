{extends file="parent:frontend/checkout/cart_footer.tpl"}

{block name='frontend_checkout_cart_footer_field_labels'}

    {include file="frontend/modules/cart-bundles.tpl" sBasket=$sBasket}

    {$smarty.block.parent}

{/block}
