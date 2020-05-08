//

Ext.define('Shopware.apps.FrejuDiscountedItems.store.DiscountedItem', {
    extend:'Shopware.store.Listing',

    configure: function() {
        return {
            controller: 'FrejuDiscountedItems'
        };
    },

    model: 'Shopware.apps.FrejuDiscountedItems.model.DiscountedItem'
});
