Ext.define('Shopware.apps.FrejuDiscountedItems.view.detail.Article', {
    extend: 'Shopware.grid.Panel',
    alias: 'widget.discounted-item-view-detail-article',
    height: 300,
    title: 'Assoziierte Produkte',

    configure: function() {
        return {
            controller: 'FrejuDiscountedItems',
            columns: {
                name: {}
            }
        };
    }
});