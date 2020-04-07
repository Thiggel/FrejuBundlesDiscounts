//

Ext.define('Shopware.apps.FrejuBundles.view.detail.Bundle', {
    extend: 'Shopware.model.Container',
    padding: 20,

    configure: function() {
        return {
            controller: 'FrejuBundles',
            associations: [ 'categories' ]
        };
    }
});