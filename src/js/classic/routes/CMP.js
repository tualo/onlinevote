Ext.define('Tualo.routes.OnlineVoteCMP',{
    url: 'cmp_wm',
    handler: {
        action: function( ){
            Ext.getApplication().addView('Tualo.OnlineVote.Viewport');
        },
        before: function ( action) {
            action.resume();
        }
    }
});