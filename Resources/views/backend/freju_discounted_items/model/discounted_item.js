Ext.define('Shopware.apps.FrejuDiscountedItems.model.DiscountedItem', {
    extend: 'Shopware.data.Model',

    configure: function() {
        return {
            controller: 'FrejuDiscountedItems',
            detail: 'Shopware.apps.FrejuDiscountedItems.view.detail.DiscountedItem'
        };
    },


    fields: [
        { name: 'id', type: 'int' },
        { name: 'productId', type: 'int' },
        { name: 'discount', type: 'int' },
        { name: 'discountType', type: 'string' }
    ],

    associations: [{
        relation: 'ManyToOne',
        field: 'productId',

        type: 'hasMany',
        model: 'Shopware.apps.Base.model.Article',
        name: 'getProduct',
        associationKey: 'product'
    }]
});

