Ext.define('Tualo.routes.Syncform',{
    statics: {
        load: async function() {
            return [
                {
                    name: 'Onlinewahl Synchronisation',
                    path: '#onlinevote/syncform'
                }
            ]
        }
    }, 
    url: 'onlinevote/syncform',
    handler: {
        action: function( ){
            Ext.getApplication().addView('Tualo.OnlineVote.Syncform');
        },
        before: function ( action,cnt) {
            Ext.require('Tualo.OnlineVote.Viewport',function(){
                action.resume();
            },this);
        }
    }
});