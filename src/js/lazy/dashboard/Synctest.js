Ext.define('Tualo.OnlineVote.dashboard.Synctest', {
    requires: [
        // 'Ext.chart.CartesianChart'
    ],
    extend: 'Ext.dashboard.Part',
    alias: 'part.tualodashboard_onlinevote_synctest',
 
    viewTemplate: {
        title: 'Synctest',
        layout:{
            type: 'vbox',
            align: 'center'
        },
        items: [
            {
                xtype: 'panel',
                listeners: {
                    boxready: async function(me){
                        let data = await fetch('./onlinevote/state').then((response)=>{return response.json()});
                        console.log(data);
                        if (data.success){
                            me.add({
                                xtype: 'panel',
                                html: 'Der Server ist erreichbar!'
                            })
                        }else{
                            me.add({
                                xtype: 'panel',
                                html: 'Der Server ist nicht erreichbar!'
                            })
                        }
                        if (data.starttime==null){
                            me.add({
                                xtype: 'panel',
                                html: 'Der Wahlzeitraum ist nicht konfiguriert!'
                            })
                        }else{
                            me.add({
                                xtype: 'panel',
                                html: 'Der Wahlzeitraum ('+data.starttime+' bis '+data.stoptime+') ist konfiguriert!'
                            })
                        }
        
                    }
                },
            }
        ]
    }
});