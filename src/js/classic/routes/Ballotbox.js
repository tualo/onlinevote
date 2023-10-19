Ext.define('Tualo.routes.Ballotbox',{
    statics: {
        load: async function() {
            return [
                {
                    name: 'Onlinewahl Urnen',
                    path: '#onlinevote/ballotbox'
                }
            ]
        }
    }, 
    url: 'onlinevote/ballotbox',
    handler: {
        action: function( ){
            Ext.getApplication().addView('Tualo.OnlineVote.Viewport');
        },
        before: function ( action,cnt) {
            Ext.require('Tualo.OnlineVote.Viewport',function(){
                action.resume();
            },this);
        }
    }
});