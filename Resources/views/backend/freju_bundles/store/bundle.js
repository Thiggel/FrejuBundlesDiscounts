//

Ext.define('Shopware.apps.FrejuBundles.store.Bundle', {
    extend:'Shopware.store.Listing',

    configure: function() {
        return {
            controller: 'FrejuBundles'
        };
    },

    model: 'Shopware.apps.FrejuBundles.model.Bundle'
});