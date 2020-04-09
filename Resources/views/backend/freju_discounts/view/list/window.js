//

Ext.define('Shopware.apps.FrejuDiscounts.view.list.Window', {
    extend: 'Shopware.window.Listing',
    alias: 'widget.discount-list-window',
    height: 450,
    title : '{s name=window_title}Discount listing{/s}',

    configure: function() {
        return {
            listingGrid: 'Shopware.apps.FrejuDiscounts.view.list.Discount',
            listingStore: 'Shopware.apps.FrejuDiscounts.store.Discount'
        };
    }
});