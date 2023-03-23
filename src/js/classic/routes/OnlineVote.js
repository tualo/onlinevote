Ext.define('Tualo.routes.OnlineVote',{
    url: 'onlinevote',
    handler: {
        action: function( ){
            Ext.getApplication().addView('Tualo.OnlineVote.Viewport');
        },
        before: function ( action) {
            let ping = Ext.getApplication().sessionPing;
            if (ping.success===true) action.stop();
            action.resume();
        }
    }
});