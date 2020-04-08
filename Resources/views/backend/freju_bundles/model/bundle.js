Ext.define('Shopware.apps.FrejuBundles.model.Bundle', {
    extend: 'Shopware.data.Model',

    configure: function() {
        return {
            controller: 'FrejuBundles',
            detail: 'Shopware.apps.FrejuBundles.view.detail.Bundle'
        };
    },


    fields: [
        { name : 'id', type: 'int', useNull: true },
        { name : 'active', type: 'boolean' },
        { name : 'createDate', type: 'date' },
        { name : 'mainProductId', type: 'int' },
        { name : 'bundleType', type: 'string' },
        { name : 'bundleBonus', type: 'int' },
    ],

    associations: [{
        relation: 'ManyToOne',
        field: 'mainProductId',

        type: 'hasMany',
        model: 'Shopware.apps.FrejuBundles.model.Article',
        name: 'getMainProduct',
        associationKey: 'mainProduct'
    }, {
        relation: 'ManyToMany',

        type: 'hasMany',
        model: 'Shopware.apps.FrejuBundles.model.Article',
        name: 'getRelatedProducts',
        associationKey: 'relatedProducts'
    }]
});

