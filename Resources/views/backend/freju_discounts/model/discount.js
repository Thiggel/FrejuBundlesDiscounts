Ext.define('Shopware.apps.FrejuDiscounts.model.Discount', {
    extend: 'Shopware.data.Model',

    configure: function() {
        return {
            controller: 'FrejuDiscounts',
            detail: 'Shopware.apps.FrejuDiscounts.view.detail.Discount'
        };
    },


    fields: [
        { name : 'id', type: 'int', useNull: true },
        { name : 'active', type: 'boolean' },
        { name : 'startDate', type: 'string' },
        { name : 'endDate', type: 'string' },
        { name : 'name', type: 'string' },
        { name : 'description', type: 'string' },
        { name : 'badge', type: 'string' },
        { name : 'color', type: 'string' }
    ],
});

