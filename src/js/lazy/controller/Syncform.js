Ext.define('Tualo.OnlineVote.controller.Syncform', {
    extend: 'Ext.app.ViewController',
    alias: 'controller.onlinevote_syncform_controller',

    onReady: async function () {
        let me = this,
            view = me.getView(),
            vm = view.getViewModel(),
            setup = await fetch('./onlinevote/syncsetup').then((response)=>{return response.json()}),
            state = await fetch('./onlinevote/state').then((response)=>{return response.json()});
        if (setup.success==false){
            Ext.toast({
                html: setup.msg,
                title: 'Fehler',
                align: 't',
                iconCls: 'fa fa-warning'
            });
        }else{            
            view.getForm().setValues(setup);
            view.enable();
        }
        if (state.success){
            vm.set('interrupted',state.interrupted || false);
            if(state.starttime){
                vm.set('starttime', Ext.util.Format.date( new Date(state.starttime), 'H:i:s') );
                vm.set('startdate', Ext.util.Format.date( new Date(state.starttime), 'Y-m-d') );
            }
            if(state.starttime){
                vm.set('stoptime', Ext.util.Format.date( new Date(state.stoptime), 'H:i:s') );
                vm.set('stopdate', Ext.util.Format.date( new Date(state.stoptime), 'Y-m-d') );
            }
        }else{
            Ext.toast({
                html: state.msg,
                title: 'Fehler',
                align: 't',
                iconCls: 'fa fa-warning'
            });
            view.disable();
        }
        if ((new Date(state.starttime))<=(new Date())){
            view.disable();
            Ext.toast({
                html: "Achtung: Die Wahl wurde bereits gestartet!",
                title: 'Fehler',
                align: 't',
                iconCls: 'fa fa-warning'
            });
        }


    },
    sync:  async function(btn){
        let me = this,
        view = me.getView(),
        x =  view.disable(),
        syncremote = await fetch('./onlinevote/syncremote').then((response)=>{return response.json()})
        if (syncremote.success==false){
            Ext.toast({
                html: syncremote.msg,
                title: 'Fehler',
                align: 't',
                iconCls: 'fa fa-warning'
            });
        }else{
            Ext.toast({
                html: "Sync erfolgreich",
                title: 'OK',
                align: 't',
            }); 
        }
        view.enable()

    }
});