Ext.define('Tualo.routes.OnlineVote',{
    url: 'onlinevote',
    handler: {
        action: function( ){
            Ext.getApplication().addView('Tualo.OnlineVote.Viewport');
        },
        before: function ( action) {
            let ping = Ext.getApplication().sessionPing;
            if (ping.success===false) action.stop();

            setTimeout(function(){
            action.resume();
            },2000);
        }
    }
});