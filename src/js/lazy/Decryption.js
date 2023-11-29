Ext.define('Tualo.OnlineVote.Decryption', {
    extend: 'Ext.Container',
    controller: 'onlinevote_decryption_controller',
    requires: [
        'Tualo.OnlineVote.controller.Decryption',
        'Tualo.OnlineVote.models.Decryption'
    ],
    height: 300,
    layout: 'fit',
    width: 400,

    viewModel: {
        type: 'onlinevote_decryption_model'
    },
    listeners:{
        boxReady: 'onReady'
    },
    items: [{
        xtype: 'panel',
        itemId: 'panel',
        shadow: 'true',
        bodyPadding: 15,

        layout: {
            type: 'card',
        },

        items: [{
            itemId: 'card-0',
            html: '<h2>Digitale Urnen entschlüsseln</h2><p>Schritt 1 von 6</p><p>Bitte klicken Sie auf Weiter, um den Vorgang zu beginnen</p>'
        }, {
            itemId: 'card-1',
            html: '<h2>Berechtigungen prüfen</h2><p>Schritt 2 von 6</p><p>Klicken Sie auf Weiter, um die Berechtigten zu prüfen.</p>'
        },  {
            itemId: 'card-2',
            html: '<h2>Wählerkennungen entfernen</h2><p>Schritt 3 von 6</p><p>Klicken Sie auf Weiter, um die Wählerkennungen zu entfernen.</p>'
        }, {
            itemId: 'card-3',
            items  : [{
                bind:{
                    html: '<h2>Import der Schlüssel</h2><p>Schritt 4 von 6</p><p>Es gibt {countKeys} Urnen, davon können {countPriatveKeys} entschlüsselt werden.</p>'
                }
            },{
                xtype: 'button',
                text: 'Schlüssel importieren',
                handler: 'onUpload'
            }]
        }, {
            itemId: 'card-4',
            items  : [{
                bind:{
                    html: '<h2>Entschlüsseln</h2><p>Schritt 5 von 6</p><p>Klicken Sie auf Weiter, um das Entschlüsseln zu starten.</p>'
                }
            },{
                xtype: 'progressbar',
                bind: {
                    value: '{progress}'
                }
            }]
        }, {
            itemId: 'card-5',
            html: '<h2>Auszählen</h2><p>Schritt 6 von 6</p><p>Klicken Sie auf Weiter, um die Stimmenauszählung zu starten.</p>'
        }],

        bbar: {
            reference: 'bbar',
            items: ['->',{
                itemId: 'card-prev',
                text: '&laquo; Zurück',
                handler: 'showPrevious',
                disabled: true
            },
            {
                itemId: 'card-next',
                text: 'Weiter &raquo;',
                handler: 'showNext'
            }
            ]
        }
    }]
});