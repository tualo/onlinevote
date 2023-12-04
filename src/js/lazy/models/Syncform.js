Ext.define('Tualo.OnlineVote.models.Syncform', {
    extend: 'Ext.app.ViewModel',
    alias: 'viewmodel.onlinevote_syncform_model',
    data:{
        interrupted: false,

        startdate: '2022-01-01',
        starttime: '00:00:00',

        stopdate: '2022-01-01',
        stoptime: '00:00:00'
    },
    formulas: {
        formtext: function(get){
            let interrupted = get('interrupted');
            if (interrupted){
                return  '<h2>Einstellungen</h2><p style="color: red;">Die Wahl wurde unterbrochen</p><p>Bitte stellen Sie hier den Zeitraum ein, in dem die Wahl aktiv sein soll.</p>';
            }
            return '<h2>Einstellungen</h2><p>Bitte stellen Sie hier den Zeitraum ein, in dem die Wahl aktiv sein soll.</p>';
        }
    },
    stores: {
        pgpkeys: {
            type: 'pgpkeys_store',
            autoLoad: true
        }
    }
});