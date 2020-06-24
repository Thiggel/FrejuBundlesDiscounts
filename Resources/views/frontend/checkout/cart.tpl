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


{block name='frontend_checkout_cart_cart_footer'}
    {$bundleBonus = 0}
    {foreach $sBasket.content as $item}

        {if $item['additional_details'].attributes.bundleInBasket}
            {$bundle = $item['additional_details'].attributes.bundle->get('bundle')}
            {$bundleBonus = $bundleBonus + $bundle['absoluteBonus']}
        {/if}
    {/foreach}

    {if $bundleBonus > 0}
        <div class="freju--checkout__discount">
            <div class="freju--checkout__discount_name">Bundle-Bonus <a href="javascript:modalBundleBonus();"><i class="icon--service"></i></a>: </div>

            <div class="freju--checkout__discount_value"> - {$bundleBonus|currency}</div>
        </div>
    {/if}
    {include file="parent:frontend/checkout/cart_footer.tpl"}
{/block}

