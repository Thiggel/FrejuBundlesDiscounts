Ext.define('Shopware.apps.FrejuBundles.model.Article', {

    extend: 'Shopware.apps.Base.model.Article',

    configure: function() {
        return {
            related: 'Shopware.apps.FrejuBundles.view.detail.Article'
        }
    }
});