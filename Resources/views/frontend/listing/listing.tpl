{extends file="parent:frontend/listing/listing.tpl"}

{block name="frontend_listing_listing_wrapper"}
    {include file="frontend/modules/discount-configurator.tpl"}

    {$smarty.block.parent}
{/block}
