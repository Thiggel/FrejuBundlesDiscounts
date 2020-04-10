//

Ext.define('Shopware.apps.FrejuDiscountedItems', {
    extend: 'Enlight.app.SubApplication',

    name:'Shopware.apps.FrejuDiscountedItems',

    loadPath: '{url action=load}',
    bulkLoad: true,

    controllers: [ 'Main' ],

    views: [
        'list.Window',
        'list.DiscountedItem',

        'detail.DiscountedItem',
        'detail.Window'
    ],

    models: [ 'DiscountedItem' ],
    stores: [ 'DiscountedItem' ],

    launch: function() {
        return this.getController('Main').mainWindow;
    }
});