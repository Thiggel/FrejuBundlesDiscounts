//

Ext.define('Shopware.apps.FrejuBundles', {
    extend: 'Enlight.app.SubApplication',

    name:'Shopware.apps.FrejuBundles',

    loadPath: '{url action=load}',
    bulkLoad: true,

    controllers: [ 'Main' ],

    views: [
        'list.Window',
        'list.Bundle',

        'detail.Bundle',
        'detail.Window',
        'detail.Article'
    ],

    models: [ 'Bundle', 'Article' ],
    stores: [ 'Bundle' ],

    launch: function() {
        return this.getController('Main').mainWindow;
    }
});