Ext.define('Shopware.apps.FrejuDiscounts.model.Article', {

    extend: 'Shopware.apps.Base.model.Article',

    configure: function() {
        return {
            related: 'Shopware.apps.FrejuDiscounts.view.detail.Article'
        }
    }
});