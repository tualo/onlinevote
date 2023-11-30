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
                                html: 'Der Remote-Server ist erreichbar!'
                            })
                        }else{
                            me.add({
                                xtype: 'panel',
                                html: 'Der Remote-Server ist nicht erreichbar!'
                            })
                        }
                        if (data.starttime==null){
                            me.add({
                                xtype: 'panel',
                                html: 'Der Wahlzeitraum ist nicht konfiguriert!'
                            })
                        }else{
                            let start = Ext.util.Format.date( new Date(data.starttime), 'd.m.Y H:i:s');
                            let stop = Ext.util.Format.date( new Date(data.stoptime), 'd.m.Y H:i:s');
                            me.add({
                                xtype: 'panel',
                                html: [
                                    'Wahlzeitraum:',
                                    start,
                                    stop
                                ].join('<br>')
                            })
                        }
                        me.add({
                            xtype: 'panel',
                            html: 'Der Webserver hat die Zeitzone: '+data.timezone
                        });
        
                        let php_time = (new Date(data.php_time)).getTime();
                        let db_time = (new Date(data.db_time)).getTime();

                        me.add({
                            xtype: 'panel',
                            html: 'Die Datenbank- und Webserverzeit weicht '+ Math.round((php_time-db_time)/1000) +' Sekunden voneinander ab!'
                        });
        
                    }
                },
            }
        ]
    }
});