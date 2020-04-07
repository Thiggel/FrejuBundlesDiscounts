//

Ext.define('Shopware.apps.FrejuBundles.view.list.Bundle', {
    extend: 'Shopware.grid.Panel',
    alias:  'widget.bundle-listing-grid',
    region: 'center',

    configure: function() {
        return {
            detailWindow: 'Shopware.apps.FrejuBundles.view.detail.Window'
        };
    }
});
