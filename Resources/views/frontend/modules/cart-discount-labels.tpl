{block name="frontend_freju_cart_discount_labels"}
    {if isset($details) && is_array($details)}

        <style>
            .freju--checkout__discount-labels {
                display: flex;
                flex-wrap: wrap;
                margin: 12px 0;
            }
            .freju--checkout__discount-labels_label {
                background: rgba(57,88,154,0.3);
                color: #39589a;
                padding: 4px 8px;
                border-radius: 4px;
                font-weight: 600;
                margin-right: 8px;
            }
        </style>

        {$discounts = $details['discounts']}
        <div class="freju--checkout__discount-labels">
            {foreach $discounts['precalculated']['€'] as $discount}
                <div class="freju--checkout__discount-labels_label" style="background: {$discount['color']}; color: {$discount['darkerColor']};">{$discount['name']}</div>
            {/foreach}
            {foreach $discounts['precalculated']['%'] as $discount}
                <div class="freju--checkout__discount-labels_label" style="background: {$discount['color']}; color: {$discount['darkerColor']};">{$discount['name']}</div>
            {/foreach}
            {foreach $discounts['postcalculated']['€'] as $discount}
                <div class="freju--checkout__discount-labels_label" style="background: {$discount['color']}; color: {$discount['darkerColor']};">{$discount['name']}</div>
            {/foreach}
            {foreach $discounts['postcalculated']['%'] as $discount}
                <div class="freju--checkout__discount-labels_label" style="background: {$discount['color']}; color: {$discount['darkerColor']};">{$discount['name']}</div>
            {/foreach}
            {foreach $discounts['cashback']['€'] as $discount}
                <div class="freju--checkout__discount-labels_label" style="background: {$discount['color']}; color: {$discount['darkerColor']};">{$discount['name']}</div>
            {/foreach}
            {foreach $discounts['cashback']['%'] as $discount}
                <div class="freju--checkout__discount-labels_label" style="background: {$discount['color']}; color: {$discount['darkerColor']};">{$discount['name']}</div>
            {/foreach}
        </div>

    {/if}

{/block}
