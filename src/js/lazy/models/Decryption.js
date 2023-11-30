Ext.define('Tualo.OnlineVote.models.Decryption', {
    extend: 'Ext.app.ViewModel',
    alias: 'viewmodel.onlinevote_decryption_model',
    
    data:{
        currentWMState: 'unkown',
        countKeys: 0,
        countPriatveKeys: 0,
        progressMax: 0,
        decrypted: 0,
        progress: 0,
        showwait: false,
    },
    formulas: {
        estimatedTimeText: function(get){
            let time = get('estimatedTime');
            if (time){
                if(Math.round(time)>0){
                    return 'ca. '+Math.round(time)+' Minuten verbleiben';
                }else{
                    return 'wenige Sekunden verbleiben';
                }
            }
            return '';
        }
    },
    stores: {
        pgpkeys: {
            type: 'pgpkeys_store',
            autoLoad: true
        }
    }
});