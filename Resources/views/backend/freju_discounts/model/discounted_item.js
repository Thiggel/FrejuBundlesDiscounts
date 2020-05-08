Ext.define('Shopware.apps.FrejuDiscounts.model.DiscountedItem', {

    extend: 'Shopware.data.Model',

    configure: function() {
        return {
            related: 'Shopware.apps.FrejuDiscounts.view.detail.Article'
        }
    }
});
