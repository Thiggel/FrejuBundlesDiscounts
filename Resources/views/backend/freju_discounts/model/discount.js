Ext.define('Shopware.apps.FrejuDiscounts.model.Discount', {
    extend: 'Shopware.data.Model',

    configure: function() {
        return {
            controller: 'FrejuDiscounts',
            detail: 'Shopware.apps.FrejuDiscounts.view.detail.Discount'
        };
    },


    fields: [
        { name : 'id', type: 'int', useNull: true },
        { name : 'active', type: 'boolean' },
        { name : 'cashback', type: 'boolean' },
        { name : 'discountPrecalculated', type: 'boolean' },
        { name : 'startDate', type: 'date' },
        { name : 'endDate', type: 'date' },
        { name : 'name', type: 'string' },
    ],

    associations: [{
        relation: 'ManyToMany',

        type: 'hasMany',
        model: 'Shopware.apps.FrejuDiscounts.model.Article',
        name: 'getRelatedProducts',
        associationKey: 'relatedProducts'
    }]
});

