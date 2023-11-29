Ext.define('Tualo.OnlineVote.models.Ballotbox', {
    extend: 'Ext.app.ViewModel',
    alias: 'viewmodel.onlinevote_ballotbox_model',
    
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