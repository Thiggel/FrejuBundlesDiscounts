Ext.define('Shopware.apps.FrejuDiscounts.model.DiscountedItem', {
    extend: 'Shopware.data.Model',

    configure: function() {
        return {
            listing: 'Shopware.apps.FrejuDiscounts.view.detail.DiscountedItem'
        }
    },

    fields: [
        { name: 'id', type: 'int' },
        { name: 'productId', type: 'int' },
        { name: 'discount', type: 'int' },
        { name: 'discountType', type: 'string' }
    ]
});