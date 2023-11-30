Ext.define('Tualo.OnlineVote.dashboard.OnlineChart', {
    requires: [
        'Ext.chart.CartesianChart'
    ],
    extend: 'Ext.dashboard.Part',
    alias: 'part.tualodashboard_onlinechart',
 
    viewTemplate: {
        layout: 'fit',
        title: 'Onlinewahl',
        // header: false,
        items: [{
            xtype: 'cartesian',
            height: 400,
            innerPadding: '0 10 0 10',
            //background: '#F1495B',
            store: {
                type:'pgpkeys_store',
                autoLoad: true,
            },
            legend: {
                docked: 'bottom'
            },
            axes: [{
                type: 'numeric',
                position: 'left',
                minimum: 0,
                fields: [ 'encrypted', 'decrypted','blocked'],
                title: {
                    text: 'Anzahl',
                    fontSize: 15
                },
                grid: {
                    odd: {
                        fillStyle: 'rgba(255, 255, 255, 0.06)'
                    },
                    even: {
                        fillStyle: 'rgba(0, 0, 0, 0.03)'
                    }
                }
            }, {
                type: 'category',
                position: 'bottom',
                title: {
                    text: 'Urnen',
                    fontSize: 15
                },
                fields: 'keyid'
            }],
            series: {
                type: 'bar',
                xField: 'keyid',
                yField: ['encrypted', 'decrypted','blocked']
            }
        }]
    }
});