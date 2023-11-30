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
    },
    save:   function(){
    }

});