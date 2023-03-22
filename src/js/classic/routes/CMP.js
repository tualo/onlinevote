Ext.define('TualoOffice.routes.OnlineVoteCMP',{
    url: 'cmp_wm',
    handler: {
        action: function( ){
            TualoOffice.getApplication().addView('Tualo.OnlineVote.Viewport');
        },
        before: function ( action) {
            action.resume();
        }
    }
});