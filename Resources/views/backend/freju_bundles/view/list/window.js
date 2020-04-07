//

Ext.define('Shopware.apps.FrejuBundles.view.list.Window', {
    extend: 'Shopware.window.Listing',
    alias: 'widget.bundle-list-window',
    height: 450,
    title : '{s name=window_title}Bundle listing{/s}',

    configure: function() {
        return {
            listingGrid: 'Shopware.apps.FrejuBundles.view.list.Bundle',
            listingStore: 'Shopware.apps.FrejuBundles.store.Bundle'
        };
    }
});