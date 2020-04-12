Ext.define('Shopware.apps.FrejuDiscounts.view.detail.Article', {
    extend: 'Shopware.grid.Association',
    alias: 'widget.discount-view-detail-article',
    height: 300,
    title: 'Assoziierte Produkte',

    configure: function() {
        return {
            controller: 'FrejuDiscounts',
            columns: {
                name: {}
            }
        };
    }
});