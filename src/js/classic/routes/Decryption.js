Ext.define('Tualo.routes.Decryption',{
    statics: {
        load: async function() {
            return [
                {
                    name: 'Onlinewahl Entschlüsseln',
                    path: '#onlinevote/decryption'
                }
            ]
        }
    }, 
    url: 'onlinevote/decryption',
    handler: {
        action: function( ){
            console.log('action');

            Ext.getApplication().addView('Tualo.OnlineVote.Decryption');
        },
        before: function ( action,cnt) {
            console.log('before');
            let fn = Ext.require, txt = 'Tualo.OnlineVote'+'.Decryption';
            fn(txt,function(){
                console.log('resume');
                action.resume();
            },this);
        }
    }
});