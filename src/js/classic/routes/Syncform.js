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
            console.log('action');

            Ext.getApplication().addView('Tualo.OnlineVote.Syncform');
        },
        before: function ( action,cnt) {
            console.log('before');
            let fn = Ext.require, txt = 'Tualo.OnlineVote'+'.Syncform';
            fn(txt,function(){
                console.log('resume');
                action.resume();
            },this);
        }

    }
});