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
            console.log('action');

            Ext.getApplication().addView('Tualo.OnlineVote.Ballotbox');
        },
        before: function ( action,cnt) {
            console.log('before');
            let fn = Ext.require, txt = 'Tualo.OnlineVote'+'.Ballotbox';
            fn(txt,function(){
                console.log('resume');
                action.resume();
            },this);
        }
    }
});