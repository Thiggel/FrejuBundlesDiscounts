//

Ext.define('Shopware.apps.FrejuDiscountedItems.view.list.Window', {
    extend: 'Shopware.window.Listing',
    alias: 'widget.discounted-item-list-window',
    height: 450,
    title : '{s name=window_title}Discounted Item listing{/s}',

    configure: function() {
        return {
            listingGrid: 'Shopware.apps.FrejuDiscountedItems.view.list.DiscountedItem',
            listingStore: 'Shopware.apps.FrejuDiscountedItems.store.DiscountedItem'
        };
    }
});