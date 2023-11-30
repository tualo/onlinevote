Ext.define('Tualo.routes.Settings',{
    statics: {
        load: async function() {
            return [
                {
                    name: 'Onlinewahl Einstellungen',
                    path: '#onlinevote/settings'
                }
            ]
        }
    }, 
    url: 'onlinevote/settings',
    handler: {
        action: function( ){
            console.log('action');

            Ext.getApplication().addView('Tualo.OnlineVote.Settings');
        },
        before: function ( action,cnt) {
            console.log('before');
            let fn = Ext.require, txt = 'Tualo.OnlineVote'+'.Settings';
            fn(txt,function(){
                console.log('resume');
                action.resume();
            },this);
        }
    }
});