//

Ext.define('Shopware.apps.FrejuDiscountedItems.view.list.DiscountedItem', {
    extend: 'Shopware.grid.Panel',
    alias:  'widget.discounted-item-listing-grid',
    region: 'center',

    configure: function() {
        return {
            detailWindow: 'Shopware.apps.FrejuDiscountedItems.view.detail.Window'
        };
    }
});
