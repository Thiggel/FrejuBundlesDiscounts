Ext.define('Shopware.apps.FrejuBundles.model.BundleType', {
    extend: 'Shopware.data.Model',

    configure: function() {
        return {
            detail: 'Shopware.apps.SwagProductAssoc.view.detail.BundleType'
        }
    },

    fields: [
        { name : 'id', type: 'int', useNull: true },
        { name : 'name', type: 'string' },
    ]
});