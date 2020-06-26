{extends file="parent:frontend/checkout/cart.tpl"}

{block name='frontend_checkout_cart_table_actions'}
    <div class="table--actions">

        <div class="main--actions">
            {* Continue shopping *}
            {block name="frontend_checkout_actions_link_last"}{/block}

            {block name="frontend_checkout_actions_confirm"}

                {* Forward to the checkout *}
                {if !$sMinimumSurcharge && !($sDispatchNoOrder && !$sDispatches) && !$sInvalidCartItems}
                    {block name="frontend_checkout_actions_checkout"}
                        {s name="CheckoutActionsLinkProceedShort" namespace="frontend/checkout/actions" assign="snippetCheckoutActionsLinkProceedShort"}{/s}
                        <a href="{if {config name=always_select_payment}}{url controller='checkout' action='shippingPayment'}{else}{url controller='checkout' action='confirm'}{/if}"
                           title="{$snippetCheckoutActionsLinkProceedShort|escape}"
                           class="btn btn--checkout-proceed is--primary right is--icon-right is--large">
                            {s name="CheckoutActionsLinkProceedShort" namespace="frontend/checkout/actions"}{/s}
                            <i class="icon--arrow-right"></i>
                        </a>
                    {/block}
                {else}
                    {block name="frontend_checkout_actions_checkout"}
                        {s name="CheckoutActionsLinkProceedShort" namespace="frontend/checkout/actions" assign="snippetCheckoutActionsLinkProceedShort"}{/s}
                        <span
                                title="{$snippetCheckoutActionsLinkProceedShort|escape}"
                                class="btn is--disabled btn--checkout-proceed is--primary right is--icon-right is--large">
                                                {s name="CheckoutActionsLinkProceedShort" namespace="frontend/checkout/actions"}{/s}
                                                <i class="icon--arrow-right"></i>
                                            </span>
                    {/block}
                {/if}
            {/block}
        </div>

        {include file="frontend/modules/cart-discounts.tpl" items=$sBasket.content}
    </div>
{/block}

