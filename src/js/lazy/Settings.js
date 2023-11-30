Ext.define('Tualo.OnlineVote.Settings', {
    extend: 'Ext.Container',
    requires: [
        'Tualo.OnlineVote.controller.Settings',
        'Tualo.OnlineVote.models.Settings'
    ],
    layout: 'fit',
    controller: 'onlinevote_settings_controller',
    viewModel: {
        type: 'onlinevote_settings_model'
    },
    listeners:{
        boxReady: 'onReady'
    },
    items: [{
        xtype: 'form',
        shadow: 'true',
        bodyPadding: 15,
        defaults: {
            anchor: '100%',
            labelWidth: 120
        },
        items: [
            {
                xtype: 'panel',
                 
                bind: {
                    html: '{formtext}'
                }
            },
            {
                xtype: 'fieldset',
                title: 'Wahlzeitraum',
                fieldDefaults: {
                    msgTarget: 'under',
                    labelAlign: 'top'
                },
                items: [
                    
                    {
                        xtype: 'fieldcontainer',
                        fieldLabel: 'Start',
                        layout: 'hbox',
                        items:  [
                            {
                                xtype: 'datefield',
                                name: 'startdate',
                                flex: 1,
                                format: 'd.m.Y',
                                submitFormat: 'Y-m-d',
                                bind: {
                                    value: '{startdate}'
                                }
                            },{
                                xtype: 'splitter'
                            },
                            {
                                xtype: 'timefield',
                                name: 'starttime',
                                flex: 1,
                                format: 'H:i:s',
                                bind: {
                                    value: '{starttime}'
                                }
                            }/*,
                            {
                                xtype: 'button',
                                text: 'Jetzt',
                                handler: 'onNow'
                            }*/
                        ]

                    },


                    {
                        xtype: 'fieldcontainer',
                        fieldLabel: 'Ende',
                        layout: 'hbox',
                        items:  [
                            {
                                xtype: 'datefield',
                                name: 'stopdate',
                                flex: 1,
                                format: 'd.m.Y',
                                bind: {
                                    value: '{stopdate}'
                                }
                            },{
                                xtype: 'splitter'
                            },
                            {
                                xtype: 'timefield',
                                name: 'stoptime',
                                flex: 1,
                                format: 'H:i:s',
                                bind: {
                                    value: '{stoptime}'
                                }
                            }/*,
                            {
                                xtype: 'button',
                                text: 'Jetzt',
                                handler: 'onNow'
                            }*/
                        ]

                    },

                    {
                        xtype: 'checkbox',
                        name: 'interrupted',
                        fieldLabel: 'Unterbrochen'
                    }
                ]
            }
        ],

        bbar: {
            reference: 'bbar',
            items: ['->',{
                itemId: 'card-prev',
                text: 'Speichern',
                handler: 'save'
            }
            ]
        }
    }]
});