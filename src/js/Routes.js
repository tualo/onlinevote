Ext.define('TualoOffice.routes.OnlineVote',{
    url: 'onlinevote',
    handler: {
        action: function(type,tablename,id){
            TualoOffice.getApplication().addView('Tualo.OnlineVote.Viewport');
        },
        before: function (type,tablename,xid,action) {
            action.resume();
        }
    }
});