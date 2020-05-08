Ext.define('Shopware.apps.FrejuDiscountedItems.model.DiscountedItem', {
    extend: 'Shopware.data.Model',

    configure: function() {
        return {
            controller: 'FrejuDiscountedItems',
            detail: 'Shopware.apps.FrejuDiscountedItems.view.detail.DiscountedItem'
        };
    },


    fields: [
        { name : 'id', type: 'int', useNull: true },
        { name : 'campaign', type: 'string' },
        { name : 'productId', type: 'int' },
        { name : 'precalculated', type: 'boolean' },
        { name : 'cashback', type: 'boolean' },
        { name : 'discount', type: 'string' },
        { name : 'discountType', type: 'string' }
    ],

    associations: [{
        relation: 'ManyToOne',
        field: 'productId',

        type: 'hasMany',
        model: 'Shopware.apps.FrejuDiscountedItems.model.Article',
        name: 'getProduct',
        associationKey: 'product'
    }]
});

