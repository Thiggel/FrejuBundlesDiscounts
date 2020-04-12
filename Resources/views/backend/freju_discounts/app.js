//

Ext.define('Shopware.apps.FrejuDiscounts', {
    extend: 'Enlight.app.SubApplication',

    name:'Shopware.apps.FrejuDiscounts',

    loadPath: '{url action=load}',
    bulkLoad: true,

    controllers: [ 'Main' ],

    views: [
        'list.Window',
        'list.Discount',

        'detail.Article',
        'detail.Discount',
        'detail.Window'
    ],

    models: [ 'Discount', 'Article' ],
    stores: [ 'Discount' ],

    launch: function() {
        return this.getController('Main').mainWindow;
    }
});