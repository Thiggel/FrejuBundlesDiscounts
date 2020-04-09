//

Ext.define('Shopware.apps.FrejuDiscounts.store.Discount', {
    extend:'Shopware.store.Listing',

    configure: function() {
        return {
            controller: 'FrejuDiscounts'
        };
    },

    model: 'Shopware.apps.FrejuDiscounts.model.Discount'
});