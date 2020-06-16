{block name="frontend_freju_cart_discounts"}
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

    {if isset($items) && is_array($items)}
        {foreach $items as $item}
            {if $item['additional_details'].attributes.discounts}
                {$productDiscounts = $item['additional_details'].attributes.discounts->get('discounts')}
                {$quantity = $item['quantity']}

                {foreach $productDiscounts['discounts']['cashback']['€'] as $discount}
                    <div class="freju--checkout__discount" style="background: {$discount['color']}; border-color: {$discount['darkerColor']}; color: {$discount['darkerColor']};">
                        <div class="freju--checkout__discount_name">Cashback: {$discount['name']}</div>
                        {$discountValue = $discount['absoluteValue'] * $quantity}
                        <div class="freju--checkout__discount_value">(- {$discountValue|currency})</div>

                        <div class="freju--checkout__discount_description">Den Cashback-Betrag erhalten Sie nach Kauf und Registierung des Produkts bei {$item['additional_details']['supplierName']}</div>
                    </div>
                {/foreach}
                {foreach $productDiscounts['discounts']['cashback']['%'] as $discount}
                    <div class="freju--checkout__discount" style="background: {$discount['color']}; border-color: {$discount['darkerColor']}; color: {$discount['darkerColor']};">
                        <div class="freju--checkout__discount_name">Cashback: {$discount['name']}</div>
                        {$discountValue = $discount['absoluteValue'] * $quantity}
                        <div class="freju--checkout__discount_value">(- {$discountValue|currency})</div>

                        <div class="freju--checkout__discount_description">Den Cashback-Betrag erhalten Sie nach Kauf und Registierung des Produkts bei {$item['additional_details']['supplierName']}</div>
                    </div>
                {/foreach}
            {/if}
        {/foreach}

    {/if}

    {if isset($discounts) && is_array($discounts)}
        {foreach $discounts['discounts']['precalculated']['€'] as $discount}

            <div class="freju--checkout__discount" style="background: {$discount['color']}; border-color: {$discount['darkerColor']}; color: {$discount['darkerColor']};">
                <div class="freju--checkout__discount_name">{$discount['name']}</div>

                {$discountValue = $discount['absoluteValue'] * $quantity}
                <div class="freju--checkout__discount_value">- {$discountValue|currency}</div>
            </div>

        {/foreach}
        {foreach $discounts['discounts']['precalculated']['%'] as $discount}

            <div class="freju--checkout__discount" style="background: {$discount['color']}; border-color: {$discount['darkerColor']}; color: {$discount['darkerColor']};">
                <div class="freju--checkout__discount_name">{$discount['name']}</div>

                {$discountValue = $discount['absoluteValue'] * $quantity}
                <div class="freju--checkout__discount_value">- {$discountValue|currency}</div>
            </div>

        {/foreach}
        {foreach $discounts['discounts']['postcalculated']['€'] as $discount}

            <div class="freju--checkout__discount" style="background: {$discount['color']}; border-color: {$discount['darkerColor']}; color: {$discount['darkerColor']};">
                <div class="freju--checkout__discount_name">{$discount['name']}</div>

                {$discountValue = $discount['absoluteValue'] * $quantity}
                <div class="freju--checkout__discount_value">- {$discountValue|currency}</div>
            </div>

        {/foreach}
        {foreach $discounts['discounts']['postcalculated']['%'] as $discount}

            <div class="freju--checkout__discount" style="background: {$discount['color']}; border-color: {$discount['darkerColor']}; color: {$discount['darkerColor']};">
                <div class="freju--checkout__discount_name">{$discount['name']}</div>

                {$discountValue = $discount['absoluteValue'] * $quantity}
                <div class="freju--checkout__discount_value">- {$discountValue|currency}</div>
            </div>

        {/foreach}
    {/if}

{/block}
