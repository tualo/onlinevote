Ext.define('TualoOffice.routes.OnlineVote',{
    url: 'onlinevote',
    handler: {
        action: function( ){
            TualoOffice.getApplication().addView('Tualo.OnlineVote.Viewport');
        },
        before: function ( action) {
            action.resume();
        }
    }
});