//

Ext.define('Shopware.apps.FrejuDiscounts.view.detail.Discount', {
    extend: 'Shopware.model.Container',
    padding: 20,

    configure: function() {
        return {
            controller: 'FrejuDiscounts',
            associations: [ 'relatedProducts' ]
        };
    }
});