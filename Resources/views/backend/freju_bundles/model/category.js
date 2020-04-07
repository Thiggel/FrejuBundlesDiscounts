Ext.define('Shopware.apps.FrejuBundles.model.Category', {

    extend: 'Shopware.apps.Base.model.Category',

    configure: function() {
        return {
            related: 'Shopware.apps.FrejuBundles.view.detail.Category'
        }
    }
});