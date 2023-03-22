Ext.define('Tualo.routes.OnlineVote',{
    url: 'onlinevote',
    handler: {
        action: function( ){
            Ext.getApplication().addView('Tualo.OnlineVote.Viewport');
        },
        before: function ( action) {
            action.resume();
        }
    }
});