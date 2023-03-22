Ext.define('Tualo.OnlineVote.models.Viewport', {
    extend: 'Ext.app.ViewModel',
    alias: 'viewmodel.onlinevote_viewport_model',
    data:{
        currentWMState: 'unkown'
    },
    stores: {
        pgpkeys: {
            type: 'pgpkeys_store',
            autoLoad: true
        }
    }
});