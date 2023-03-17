Ext.define('Tualo.OnlineVote.controller.Viewport', {
    extend: 'Ext.app.ViewController',
    alias: 'controller.onlinevote_viewport_controller',

    onBoxReady: function(){
        console.log('onBoxReady');
        //this.generateKey('Urne 1, '+this.defaultName());
    },

    onAppend: function(){
        Ext.MessageBox.prompt ( "Urne anlegen", "Geben Sie bitte nun den Namen für diese Urne ein", function(type,text){
            if(type=='ok'){
                this.generateKey(text);
                alert('Der Download des privates Schlüssels startet automatisch. Bitte verwahren Sie diese Datei an einem sicheren Ort bis zum Ende der Wahlfrist.');
                
            }
        }, this, false, "Urne - "+this.defaultName() ) 
    },

    onUpload: function(){
        var me = this;
    var form = Ext.create('Ext.form.Panel', {
        labelWidth: 75,
        url: './',
        frame: false,
        fileUpload: true,
        anchor: '100% 100%',
        defaults: {
          width: 300
        },
        bodyPadding: 15,
        defaultType: 'textfield',
        buttons: [
          {
            text: 'Abbrechen',
            handler: function(btn){
              btn.up('tualowindow').hide();
            }
          },
          {
            text: 'Hochladen',
            handler: function(btn){
              var form = btn.up('form');
              if (form.getForm().isValid()) {
                form.getForm().submit({
                  url:  "wm/pgp/upload",
                  timeout: 600000,
                  scope: this,
                  waitMsg: 'Die Datei wird hochgeladen...',
                  success: function(form, o) {
                    me.getViewModel().getStore('pgpkeys').load();
                  },
                  failure: function(form, action) {
                      switch (action.failureType) {
                          case Ext.form.action.Action.CLIENT_INVALID:
                              Ext.Msg.alert(
                                  'Failure',
                                  'Form fields may not be submitted with invalid values'
                              );
                              break;
                          case Ext.form.action.Action.CONNECT_FAILURE:
                              Ext.Msg.alert('Failure', 'Ajax communication failed');
                              break;
                          case Ext.form.action.Action.SERVER_INVALID:
                             Ext.Msg.alert('Failure', action.result.msg);
                     }
                  }
                });
              }
              btn.up('tualowindow').hide();
            }
          }
        ],
        items: [
          Ext.create('Ext.panel.Panel', {
            anchor: '100%',
            autoHeight: true,
            border: false,
            bodyStyle: 'background: transparent;',
            html: 'Bitte wählen Sie einen privaten Schlüssel aus.'
          }), {
            xtype: 'filefield',
            fieldLabel: 'Datei',
            name: 'userfile',
            value: '',
            anchor: '100%'
          }
        ]
      });
      var wnd = Ext.create('Ext.tualo.Window',{
        modal: true,
        title: 'Privaten Schlüssel importieren',
        layout: 'fit',
        closeAction: 'close',
        items: [form]
      });
      wnd.show();
      wnd.resizeMe();
    },

    decrypt: function(res){
      let next = true;
      if (typeof res=='object'){
        if (res.count==0){
           next=0;
           this.getViewModel().getStore('pgpkeys').load();
        }
      }

      if (next){
        Tualo.Ajax.request({
          url: './cmp_wm/decrypt',
          // showWait: true,
          timeout: 300000,
          scope: this,
          json: function(o){
              if (o.success==true){
                this.getViewModel().getStore('pgpkeys').load();
                  this.decrypt(o);
              }else{
                  this.getViewModel().getStore('pgpkeys').load();
                  //this.download(name+'-private.key.asc',privateKeyArmored); 
              }
          }
        });
      }
    },

    countVotes: function(res){
      let next = true;
      if (typeof res=='object'){
        if (res.count==0){
           next=0;
           this.getViewModel().getStore('pgpkeys').load();
        }
      }

      if (next)
      Tualo.Ajax.request({
        url: './cmp_wm/counts',
        showWait: true,
        scope: this,
        json: function(o){
            if (o.success==true){
              this.getViewModel().getStore('pgpkeys').load();
            }else{
                this.getViewModel().getStore('pgpkeys').load();
                //this.download(name+'-private.key.asc',privateKeyArmored); 
            }
        }
      });
    },

    sync_blockedvoters: function( ){
      Tualo.Ajax.request({
        url: './cmp_wm/sync_blockedvoters',
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
          }
          this.getViewModel().getStore('pgpkeys').load();
            
        }
      });
    },

    remove_voter_references: function( ){
      
      Tualo.Ajax.request({
        url: './cmp_wm/remove_voter_references',
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
          }
          this.getViewModel().getStore('pgpkeys').load();
            
        }
      });

    },


    pzReport: function(){
      let recs = this.getViewModel().getStore('pgpkeys').getRange();
      if (recs.length>0){
        window.open("./pugreportpdf/pgpkeys/pruefziffer/"+recs[0].get('__id'));
      }
    },

    

    generateKey: function(name){
        (async () => {

          let keyPair = await window.crypto.subtle.generateKey(
            {
              name: "RSA-OAEP",
              modulusLength: 4096,
              publicExponent: new Uint8Array([1, 0, 1]),
              hash: "SHA-256",
            },
            true,
            ["encrypt", "decrypt"]
          )
          const publicKey = await window.crypto.subtle.exportKey('spki',keyPair.publicKey);
          const privatKey = await window.crypto.subtle.exportKey('pkcs8',keyPair.privateKey);

          let body = window.btoa(String.fromCharCode(...new Uint8Array(publicKey)));
          body = body.match(/.{1,64}/g).join('\n');
          const publicKeyPEM = (`-----BEGIN PUBLIC KEY-----\n${body}\n-----END PUBLIC KEY-----`);

          body = window.btoa(String.fromCharCode(...new Uint8Array(privatKey)));
          body = body.match(/.{1,64}/g).join('\n');
          const privatKeyPEM = (`-----BEGIN PRIVATE KEY-----\n${body}\n-----END PRIVATE KEY-----`);


          this.download(name+'-private.key.asc',privatKeyPEM); 

          let response = await fetch('./onlinevote/append/publickey', {
              method: 'POST',
              headers: {
                  'Accept': 'application/json',
                  'Content-Type':'application/x-www-form-urlencoded'
              },
              body: (new URLSearchParams({
                publickey: publicKeyPEM,
                name: name
              })).toString()
          }).json();
          if (response.success){

            this.getViewModel().getStore('pgpkeys').load();
            this.download(name+'-private.key.asc',privateKeyArmored); 
          }

        })();

    },

    download(filename, text) {
        var element = document.createElement('a');
        element.setAttribute('href', 'data:text/plain;charset=utf-8,' + encodeURIComponent(text));
        element.setAttribute('download', filename);
        element.style.display = 'none';
        document.body.appendChild(element);
        element.click();
        document.body.removeChild(element);
    },

    defaultName: function(get){
        try{
            return fullname
        }catch(e){
            
        }
    },

    syncform: function(){
      this.getView().getLayout().setActiveItem(1);
    }
});

/*

  */