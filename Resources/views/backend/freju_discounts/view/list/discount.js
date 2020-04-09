//

Ext.define('Shopware.apps.FrejuDiscounts.view.list.Discount', {
    extend: 'Shopware.grid.Panel',
    alias:  'widget.discount-listing-grid',
    region: 'center',

    configure: function() {
        return {
            detailWindow: 'Shopware.apps.FrejuDiscounts.view.detail.Window'
        };
    }
});
