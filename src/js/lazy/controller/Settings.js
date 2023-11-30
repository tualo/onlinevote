Ext.define('Tualo.OnlineVote.controller.Settings', {
    extend: 'Ext.app.ViewController',
    alias: 'controller.onlinevote_settings_controller',

    onReady: async function () {
        let me = this,
            view = me.getView(),
            vm = view.getViewModel(),
            data = await fetch('./onlinevote/state').then((response)=>{return response.json()});
        if (data.success){
            vm.set('interrupted',data.interrupted || false);
            if(data.starttime){
                vm.set('starttime', Ext.util.Format.date( new Date(data.starttime), 'H:i:s') );
                vm.set('startdate', Ext.util.Format.date( new Date(data.starttime), 'Y-m-d') );
            }
            if(data.starttime){
                vm.set('stoptime', Ext.util.Format.date( new Date(data.stoptime), 'H:i:s') );
                vm.set('stopdate', Ext.util.Format.date( new Date(data.stoptime), 'Y-m-d') );
            }
        }
        view.enable();
    },
    save: async function(btn){
        let me = this,
            view = me.getView(),
            vm = view.getViewModel();
            view.disable();
        if (vm.get('stopdate') instanceof Date) vm.set('stopdate', Ext.util.Format.date( vm.get('stopdate'), 'Y-m-d') );
        if (vm.get('startdate') instanceof Date) vm.set('startdate', Ext.util.Format.date( vm.get('startdate'), 'Y-m-d') );
        if (vm.get('stoptime') instanceof Date) vm.set('stoptime', Ext.util.Format.date( vm.get('stoptime'), 'H:i:s') );
        if (vm.get('starttime') instanceof Date) vm.set('starttime', Ext.util.Format.date( vm.get('starttime'), 'H:i:s') );
        
        let o = await fetch('./onlinevote/savesesstings',{
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                interrupted: vm.get('interrupted'),
                starttime: vm.get('startdate')+' '+vm.get('starttime'),
                stoptime: vm.get('stopdate')+' '+vm.get('stoptime')
            })
        }).then((response)=>{return response.json()});
        if (o.success==false){
            Ext.toast({
                html: o.msg,
                title: 'Fehler',
                align: 't',
                iconCls: 'fa fa-warning'
            });
        }

        view.enable();
    }

});