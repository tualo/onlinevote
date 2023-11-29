Ext.define('Tualo.OnlineVote.Ballotbox', {
    extend: 'Ext.panel.Panel',
    requires: [
      'Tualo.OnlineVote.controller.Ballotbox',
      'Tualo.OnlineVote.models.Ballotbox'
    ],
    layout: 'card',
    component: 'onlinevote',
    controller: 'onlinevote_ballotbox_controller',
    viewModel: {
      type: 'onlinevote_ballotbox_model'
    },
    listeners: {
      boxReady: 'onBoxReady'
    },
    items: [
      {
        xtype: 'panel',
        title: 'Wahlurnen',
        scrollable: 'y',
        tools: [
          {
            //type:'add',
            glyph: 'xf055@FontAwesome' ,
            tooltip: 'Eine Wahlrune anlegen',
            handler: 'onAppend'
          },
          {
            //type:'add',
            glyph: 'xf13e@FontAwesome' ,
            tooltip: 'Einen privaten Schlüssel importieren',
            handler: 'onUpload'
          },
          {
            //type:'add',
            glyph: 'xf15c@FontAwesome' ,
            tooltip: 'Bericht abrufen',
            handler: 'pzReport'
          },
          {
            //type:'add',
            glyph: 'xf0c6@FontAwesome' ,
            tooltip: 'Berechtigungen prüfen',
            handler: 'sync_blockedvoters'
          },
          {
            //type:'add',
            glyph: 'xf12d@FontAwesome' ,
            tooltip: 'Wählerkennung entfernen',
            handler: 'remove_voter_references'
          },
          {
            //type:'add',
            glyph: 'xf00a@FontAwesome' ,
            tooltip: 'Entschlüsseln',
            handler: 'decrypt'
          },
          {
            //type:'add',
            glyph: 'f042@FontAwesome' ,
            tooltip: 'Auszählen',
            handler: 'countVotes'
          }
        ],
        items: [
          
          {
            xtype: 'dataview',
            //itemTpl: '{fingerprint}',
            itemSelector: 'div.cmp_wm-dataview-multisort-item',
            tpl: [
              '<tpl for=".">',
              '<div class="cmp_wm-dataview-multisort-item">',
                '<div style="color: black; font-size: 1.2em; font-weight: bold; min-height: 100px;">{__displayfield}</div>',
                '<div>',
                  '<tpl if="values.invalid &gt; 0">',
                    '<br>',
                    '<br>',
                    '<i class="fas fa-exclamation-circle" style="color: red; font-size: 3em;"></i>',
                    '<br>',
                    '<br>',
                    '<span style="color: red; font-size: 1.2em;">Die Integrität der Wahlurne wurde verletzt.</span>',
                  '</tpl>',
                  '<tpl if="values.invalid == 0">',
                    '<br>',
                    '<br>',
                    '<i class="fas fa-check-circle"  style="color: green; font-size: 3em;"></i>',
                    '<br>',
                    '<br>',
                    '<span style="color: green; font-size: 1.2em;">Die Integrität der Wahlurne ist unversehrt.</span>',
                  '</tpl>',
                '</div>',

                '<br>',
                '<br>',

                '<div style="color: gray; font-size: 0.8em; font-weight: normal; text-overflow: ellipsis; width: 134px; white-space: nowrap; overflow: hidden;">Fingerprint: {fingerprint}</div>',
                '<div style="color: gray; font-size: 0.8em; font-weight: normal;">Stimmen: {total}</div>',
                
                '<br>',
                '<br>',

                '<tpl if="values.invalid &gt; 0">',
                  '<div style="color: red; font-size: 0.8em; font-weight: bold; min-height: 100px;">Diese Urne darf nicht entschlüsselt werden</div>',
                  '<br>',
                  '<br>',
                  '</tpl>',
                '<tpl if="values.invalid == 0">',
                  '<tpl if="values.has_privatekey == \'vorhanden\'">',
                    '<div style="color: gray; font-size: 0.8em; font-weight: bold; min-height: 100px;">Diese Urne kann entschlüsselt werden</div>',
                    '<br>',
                    '<div style="color: gray; font-size: 0.8em; font-weight: bold; min-height: 100px;">Bereits {encrypted} Stimmzettel entschlüsselt</div>',
                  '</tpl>',
                  '<tpl if="values.has_privatekey != \'vorhanden\'">',
                    '<div style="color: gray; font-size: 0.8em; font-weight: bold; min-height: 100px;">Diese Urne kann noch nicht entschlüsselt werden</div>',
                    '<br>',
                    '<br>',
                  '</tpl>',
                '</tpl>',
              '</div>',

              '</tpl>'
          ],

            listeners: {
                
            },
            bind:{
              store: '{pgpkeys}'
            },
            emptyText: 'Es sind noch keine Wahlurnen anlegt'

          }
          
        ]
      }
    ]
  });
  