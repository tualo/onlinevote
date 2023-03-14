Ext.define('Tualo.OnlineVote.Syncform', {
    extend: 'Ext.form.Panel',
    alias: 'widget.onlinevote_syncform',
     
    listeners:{
      boxReady: 'onBoxReady'
    },
    defaults: {
        anchor: '100%'
    },
    items: [
        {
            xtype: 'textfield',
            value: 'https://ihk-vollversammlungswahl.de/current/',
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
            handler: function(btn){

                Tualo.Ajax.request({
                    url: './wm/syncremote',
                    showWait: true,
                    timeout: 300000,
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
                                html: "Sync erfolgreich",
                                title: 'OK',
                                align: 't',
                            }); 
                        }
                        console.log(o.success);
                    }
                  });

            }
        },
        {
            text: "Einrichten",
            handler: function(btn){
                let v = btn.up('form').getForm().getValues();
                console.log(v);
                v.domain = window.location.hostname;

                Tualo.Ajax.request({
                    url: './wm-registerclientapi',
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