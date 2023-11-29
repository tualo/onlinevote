Ext.define('Tualo.OnlineVote.controller.Decryption', {
    extend: 'Ext.app.ViewController',
    alias: 'controller.onlinevote_decryption_controller',

    onReady: function () {
        /*
        console.log('init',this.lookup('panel'));
        var bbar = this.lookup('bbar'),
            card = this.lookup('panel').getLayout(),

            // Lazily create the Indicator (wired to the card layout)
            indicator = card.getIndicator();

        // Render it into our bottom toolbar (bbar)
        bbar.insert(1, indicator);
        */

    },

    showNext: function () {
        this.doCardNavigation(1);
    },

    showPrevious: function (btn) {
        this.doCardNavigation(-1);
    },
    onUpload: function () {
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
                    handler: function (btn) {
                        btn.up('window').hide();
                    }
                },
                {
                    text: 'Hochladen',
                    handler: function (btn) {
                        var form = btn.up('form');
                        if (form.getForm().isValid()) {
                            form.getForm().submit({
                                url: "onlinevote/pgp/upload",
                                timeout: 600000,
                                scope: this,
                                waitMsg: 'Die Datei wird hochgeladen...',
                                success: function (form, o) {
                                    me.getViewModel().getStore('pgpkeys').load({
                                        callback: function () {
                                            me.calcKeys();
                                        }
                                    });
                                },
                                failure: function (form, action) {
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
                        btn.up('window').hide();
                        me.calcKeys();
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
        var wnd = Ext.create('Ext.Window', {
            modal: true,
            title: 'Privaten Schlüssel importieren',
            layout: 'fit',
            closeAction: 'close',
            items: [form]
        });
        wnd.show();
        //wnd.resizeMe();
    },
    calcKeys: function () {
        var me = this,
            vm = me.getViewModel(),
            store = vm.getStore('pgpkeys'),
            countKeys = 0,
            progressMax = 0,
            decrypted = 0,
            countPriatveKeys = 0;
        store.each(function (rec) {
            countKeys++;
            if (rec.get('has_privatekey') == "vorhanden") {
                progressMax+=rec.get('total')*1-rec.get('blocked')*1;
                decrypted+=rec.get('decrypted')*1;
                countPriatveKeys++;
            }
        });
        vm.set('countKeys', countKeys);
        vm.set('progressMax', progressMax);
        vm.set('decrypted', decrypted);
        vm.set('progress', decrypted/progressMax);
        vm.set('countPriatveKeys', countPriatveKeys);
        me.getView().down('#panel').getComponent('card-4').getComponent('progressbar').updateText( decrypted+' von '+progressMax+' Stimmzetteln entschlüsselt');
    },

    decrypt: function (res) {
        let next = true,
            me = this,
            l = me.getView().down('#panel').getLayout();
        if (typeof res == 'object') {
            if (res.count == 0) {
                next = 0;
                this.getViewModel().set('showwait',false);
                this.getViewModel().getStore('pgpkeys').load({
                    callback: function () {
                        me.calcKeys();
                    }
                });
                l.setActiveItem(5);
                me.getView().down('#card-prev').setDisabled(true);
                me.getView().down('#card-next').setDisabled(false);
            }
        }

        if (next) {
            this.getViewModel().set('showwait',true);
            Tualo.Ajax.request({
                url: './onlinevote/decrypt',
                // showWait: true,
                timeout: 300000,
                scope: this,
                json: function (o) {
                    if (o.success == true) {
                        this.getViewModel().getStore('pgpkeys').load({
                            callback: function () {
                                me.calcKeys();
                            }
                        });
                        this.calcKeys();
                        this.decrypt(o);
                    } else {
                        this.getView().down('#card-next').setDisabled(true);
                        this.getViewModel().getStore('pgpkeys').load({
                            callback: function () {
                                me.calcKeys();
                            }
                        });
                        //this.download(name+'-private.key.asc',privateKeyArmored); 
                    }
                }
            });
        }
    },

    countVotes: function (res) {
        let next = true;
        if (typeof res == 'object') {
            if (res.count == 0) {
                next = 0;
                this.getViewModel().getStore('pgpkeys').load({
                    callback: function () {
                        me.calcKeys();
                    }
                });
            }
        }

        if (next)
            Tualo.Ajax.request({
                url: './onlinevote/counts',
                showWait: true,
                scope: this,
                json: function (o) {
                    if (o.success == true) {
                        this.getViewModel().getStore('pgpkeys').load({
                            callback: function () {
                                me.calcKeys();
                            }
                        });
                    } else {
                        this.getViewModel().getStore('pgpkeys').load({
                            callback: function () {
                                me.calcKeys();
                            }
                        });
                        this.getView().down('#card-next').setDisabled(true);
                        //this.download(name+'-private.key.asc',privateKeyArmored); 
                    }
                }
            });
    },

    sync_blockedvoters: function () {
        Tualo.Ajax.request({
            url: './onlinevote/sync_blockedvoters',
            showWait: true,
            scope: this,
            json: function (o) {
                if (o.success == false) {
                    Ext.toast({
                        html: o.msg,
                        title: 'Fehler',
                        align: 't',
                        iconCls: 'fa fa-warning'
                    });
                    this.getView().down('#card-next').setDisabled(true);
                }
                this.getViewModel().getStore('pgpkeys').load();

            }
        });
    },

    remove_voter_references: function () {

        Tualo.Ajax.request({
            url: './onlinevote/remove_voter_references',
            showWait: true,
            scope: this,
            json: function (o) {
                if (o.success == false) {
                    Ext.toast({
                        html: o.msg,
                        title: 'Fehler',
                        align: 't',
                        iconCls: 'fa fa-warning'
                    });
                    this.getView().down('#card-next').setDisabled(true);
                }
                this.getViewModel().getStore('pgpkeys').load();

            }
        });

    },

    doCardNavigation: function (incr) {
        var me = this,
            vm = me.getViewModel(),
            l = me.getView().down('#panel').getLayout(),
            c = l.activeItem.itemId,
            i = l.activeItem.itemId.split('card-')[1],
            next = parseInt(i, 10) + incr;
        if (c == 'card-0') {
            l.setActiveItem(next);
            me.getView().down('#card-prev').setDisabled(next === 0);
            me.getView().down('#card-next').setDisabled(next === 5);
        }else if (c == 'card-1') {
            me.sync_blockedvoters();
            me.calcKeys();
            l.setActiveItem(next);
            me.getView().down('#card-prev').setDisabled(next === 0);
            me.getView().down('#card-next').setDisabled(next === 5);
        }else if (c == 'card-2') {
            me.remove_voter_references();
            me.calcKeys();
            l.setActiveItem(next);
            me.getView().down('#card-prev').setDisabled(next === 0);
            me.getView().down('#card-next').setDisabled(next === 5);
        }else if (c == 'card-3') {
            if (vm.get('countPriatveKeys') == 0) {
                return;
            }
            me.calcKeys();
            l.setActiveItem(next);
            me.getView().down('#card-prev').setDisabled(next === 0);
            me.getView().down('#card-next').setDisabled(next === 5);
        }else if (c == 'card-4') {
            me.decrypt();
            me.calcKeys();
            me.getView().down('#card-prev').setDisabled(true);
            me.getView().down('#card-next').setDisabled(true);
        }else if (c == 'card-5') {
            me.count();
            me.getView().down('#card-prev').setDisabled(next === 0);
            me.getView().down('#card-next').setDisabled(next === 6);
        }else if (c == 'card-6') {
            me.getView().down('#card-prev').setDisabled(true);
            me.getView().down('#card-next').setDisabled(true)
        }
    }
});