Ext.define('Tualo.OnlineVote.models.Decryption', {
    extend: 'Ext.app.ViewModel',
    alias: 'viewmodel.onlinevote_decryption_model',
    
    data:{
        currentWMState: 'unkown',
        countKeys: 0,
        countPriatveKeys: 0,
    },
    stores: {
        pgpkeys: {
            type: 'pgpkeys_store',
            autoLoad: true
        }
    }
});