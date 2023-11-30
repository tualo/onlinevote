Ext.define('Tualo.OnlineVote.dashboard.Synctest', {
    requires: [
        // 'Ext.chart.CartesianChart'
    ],
    extend: 'Ext.dashboard.Part',
    alias: 'part.tualodashboard_onlinevote_synctest',
 
    
    viewTemplate: {
        title: 'Status',
        items: [
            {
                xtype: 'grid',
                store: {
                    type: 'json',
                    fields: [
                        {name: 'id', type: 'int'},
                        {name: 'name', type: 'string'},
                        {name: 'value', type: 'string'},
                        {name: 'color', type: 'string'},
                        {name: 'bold', type: 'boolean'}
                    ]
                },
                //height: 500,
                //border: true,
                hideHeaders: true,
                columns: [
                    { text: 'Name',  dataIndex: 'name', flex: 1 },
                    { text: 'Wert', dataIndex: 'value', flex: 1, align: 'right' ,renderer: function(value,meta,record){
                        if (record.get('bold')){
                            return '<span style="font-weight: bold;color:'+record.get('color')+';">'+value+'</span>';
                        }else{
                            return '<span style="color:'+record.get('color')+';">'+value+'</span>';
                        }
                    }}
                //   { text: 'Farbe', dataIndex: 'color', flex: 1 },
                //   { text: 'Fett', dataIndex: 'bold', flex: 1 }
                ],
                listeners: {
                    boxready: async function(me){
                        let data = await fetch('./onlinevote/state').then((response)=>{return response.json()}),
                            list = [];
                        if (data.remoteError===false){
                            list.push({
                                name: 'Remote-Server',
                                value: 'erreichbar',
                                color: 'green',
                                bold: false
                            });
                        }
                        if (data.remoteError===true){
                            list.push({
                                name: 'Remote-Server',
                                value: 'nicht erreichbar',
                                color: 'red',
                                bold: true
                            });
                        }   
                        if (data.starttime==null){
                            list.push({
                                name: 'Wahlzeitraum',
                                value: 'nicht konfiguriert',
                                color: 'red',
                                bold: true
                            });
                        }else{
                            let start = Ext.util.Format.date( new Date(data.starttime), 'd.m.Y H:i:s');
                            let stop = Ext.util.Format.date( new Date(data.stoptime), 'd.m.Y H:i:s');

                            if (
                                (new Date(data.starttime)).getTime()>(new Date(data.stoptime)).getTime()
                            ){
                                list.push({
                                    name: 'Wahlzeitraum',
                                    value: 'fehlerhaft konfiguriert',
                                    color: 'red',
                                    bold: true
                                });
 
                            }else{


                                if (
                                    (new Date(data.starttime)).getTime()<=Date.now() &&
                                    (new Date(data.stoptime)).getTime()>=Date.now()
                                ){
                                    list.push({
                                        name: 'Wahl',
                                        value: 'aktiv',
                                        color: 'green',
                                        bold: true
                                    });

                                }else if (
                                    (new Date(data.starttime)).getTime()>Date.now()
                                ){
                                    list.push({
                                        name: 'Wahl',
                                        value: 'nicht gestartet',
                                        color: 'darkgrey',
                                        bold: true
                                    });
                                }else if (
                                    (new Date(data.stoptime)).getTime()<Date.now()
                                ){
                                    list.push({
                                        name: 'Wahl',
                                        value: 'beendet',
                                        color: 'orange',
                                        bold: true
                                    });
                                }
                            }

                            list.push({
                                name: 'Start',
                                value: start,
                                color: 'black',
                                bold: true
                            });

                            list.push({
                                name: 'Ende',
                                value: stop,
                                color: 'black',
                                bold: true
                            });

                        }
                       

                        list.push({ 
                            name: 'Webserver-Zeitzone',
                            value: ''+data.timezone,
                            color: 'black',
                            bold: false
                        });
                        list.push({
                            name: 'Zeitabweichung',
                            value: Math.abs(Math.round((new Date(data.php_time)).getTime()-(new Date(data.db_time)).getTime())/1000)+' Sekunden',
                            color: (Math.round(Math.abs(Math.round((new Date(data.php_time)).getTime()-(new Date(data.db_time)).getTime())/1000)/100)>0?'red':'black'),
                            bold: false
                        });
                        list.push({
                            name: 'aktive Wähler',
                            value: data.active_voters,
                            color: 'black',
                            bold: false
                        });
                        
                        me.getStore().loadData(list);
                    }
                }
            }
        ]
    }
});