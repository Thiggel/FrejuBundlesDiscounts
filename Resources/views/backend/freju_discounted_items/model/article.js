Ext.define('Shopware.apps.FrejuDiscountedItems.model.Article', {

    extend: 'Shopware.apps.Base.model.Article',

    configure: function() {
        return {
            related: 'Shopware.apps.FrejuDiscountedItems.view.detail.Article'
        }
    }
});