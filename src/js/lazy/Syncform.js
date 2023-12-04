Ext.define('Tualo.OnlineVote.Syncform', {
    extend: 'Ext.form.Panel',
    alias: 'widget.onlinevote_syncform',
     
    listeners:{
      boxReady: 'onReady'
    },
    defaults: {
        anchor: '100%'
    },
    controller: 'onlinevote_syncform_controller',
    viewModel: {
        type: 'onlinevote_syncform_model'
    },
    requires: [
        'Tualo.OnlineVote.controller.Syncform',
        'Tualo.OnlineVote.models.Syncform'
    ],
    bodyPadding: '25px',
    disabled: true,
    items: [
        {
            xtype: 'textfield',
            value: 'https://wahl.software/wm/',
            fieldLabel: 'URL',
            name: 'api_url'
        },
        {
            xtype: 'textfield',
            value: '',
            fieldLabel: 'Benutzername',
            name: 'api_username'
        },
        {
            xtype: 'textfield',
            inputType: 'password',
            value: '',
            fieldLabel: 'Passwort',
            name: 'api_password'
        },
        {
            xtype: 'textfield',
            value: '',
            fieldLabel: 'System',
            name: 'api_client'
        },
        {
            xtype: 'hiddenfield',
            value: '',
            fieldLabel: 'Token',
            name: 'api_token'
        }

    ],
    buttons: [
        {
            text: "Abbrechen",
            handler: function(btn){

            }
        },
        {
            text: "Sync",
            handler: 'sync'
        },
        {
            text: "Einrichten",
            handler: function(btn){
                let v = btn.up('form').getForm().getValues();
                v.domain = window.location.hostname;
                console.log(v);

                Tualo.Ajax.request({
                    url: './onlinevote/setuphandshake',
                    params: v,
                    showWait: true,
                    scope: this,
                    json: function(o){
                        if (o.success==false){
                            Ext.toast({
                                html: o.msg,
                                title: 'Fehler',
                                align: 't',
                                iconCls: 'fa fa-warning'
                            });
                        }else{
                            Ext.toast({
                                html: "Anmeldung erfolgreich",
                                title: 'OK',
                                align: 't',
                            }); 
                        }
                    }
                  });

            }
        }
    ]
});