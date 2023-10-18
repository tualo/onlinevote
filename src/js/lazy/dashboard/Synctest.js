Ext.define('Tualo.OnlineVote.dashboard.Synctest', {
    requires: [
        // 'Ext.chart.CartesianChart'
    ],
    extend: 'Ext.dashboard.Part',
    alias: 'part.tualodashboard_onlinevote_synctest',
 
    viewTemplate: {
        layout: 'fit',
        title: 'Synctest',
        
        items: [
            {
                xtype: 'panel',
                listeners: {
                    boxready: async function(me){
                        let data = await fetch('./onlinevote/syncsetup').then((response)=>{return response.json()});
                        console.log(data);
                        if (data.success){
                            me.add({
                                xtype: 'panel',
                                html: 'Synctest.. OK!'
                            })
                        }else{
                            me.add({
                                xtype: 'panel',
                                html: 'Synctest.. ERROR!'
                            })
                        }
        
                    }
                },
            }
        ]
    }
});