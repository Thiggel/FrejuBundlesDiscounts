Ext.define('Shopware.apps.FrejuBundles.view.detail.Article', {
    extend: 'Shopware.grid.Association',
    alias: 'widget.product-view-detail-article',
    height: 300,
    title: 'Assoziierte Produkte',

    configure: function() {
        return {
            controller: 'FrejuBundles',
            columns: {
                name: {}
            }
        };
    }
});