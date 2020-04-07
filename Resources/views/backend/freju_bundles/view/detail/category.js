Ext.define('Shopware.apps.FrejuBundles.view.detail.Category', {
    extend: 'Shopware.grid.Association',
    alias: 'widget.product-view-detail-category',
    height: 300,
    title: 'Category',

    configure: function() {
        return {
            controller: 'FrejuBundles',
            columns: {
                name: {}
            }
        };
    }
});