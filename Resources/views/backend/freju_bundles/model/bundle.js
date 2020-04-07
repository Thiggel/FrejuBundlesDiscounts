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
        { name : 'bundleType', type: 'string' },
        { name : 'mainProductID', type: 'int' },
        { name : 'articleOneID', type: 'int' },
        { name : 'articleTwoID', type: 'int' },
        { name : 'articleThreeID', type: 'int' },
        { name : 'bundleBonus', type: 'int' },
    ],

    associations: [{
        relation: 'ManyToMany',

        type: 'hasMany',
        model: 'Shopware.apps.FrejuBundles.model.Category',
        name: 'getCategory',
        associationKey: 'categories'
    }]
});

