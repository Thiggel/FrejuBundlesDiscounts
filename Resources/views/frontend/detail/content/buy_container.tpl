{extends file="parent:frontend/detail/content/buy_container.tpl"}

{block name='frontend_detail_index_buy_container'}
    <pre>{print_r($sArticle.attributes.discounts)}</pre>
    <br><br>
    {$smarty.block.parent}
{/block}