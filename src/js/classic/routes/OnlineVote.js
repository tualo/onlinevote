Ext.define('Tualo.routes.OnlineVote',{
    statics: {
        load: async function() {
            return [
                {
                    name: 'Onlinewahl X',
                    path: '#onlinevote'
                }
            ]
        }
    }, 
    url: 'onlinevote',
    handler: {
        action: function( ){
            Ext.getApplication().addView('Tualo.OnlineVote.Viewport');
        },
        before: function ( action,cnt) {
            Ext.require('Tualo.OnlineVote.Viewport',function(){
                action.resume();
            },this);
            /*
            let ping = Ext.getApplication().sessionPing;
            if (!cnt) cnt=1;
            if (!ping){
                if (cnt<10){
                    Ext.defer(this.before,500,this,[action,++cnt]);
                }else{
                    action.stop();
                }
            }else{
                if (ping.success===false){
                    action.stop();
                }else{
                    
                }
            }*/
        }
    }
});