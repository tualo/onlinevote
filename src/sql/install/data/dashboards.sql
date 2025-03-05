delimiter;

insert
    ignore into dashboard_available_types (xtype, classname, description)
values
    (
        'tualodashboard_onlinechart',
        'Tualo.OnlineVote.dashboard.OnlineChart',
        ''
    );

insert
    ignore into dashboard_available_types (xtype, classname, description)
values
    (
        'tualodashboard_onlinevote_synctest',
        'Tualo.OnlineVote.dashboard.Synctest',
        ''
    );

insert
    ignore into dashboard (id, title, dashboard_type, position, configuration)
values
    (
        'addcd222-8ece-11ee-a5d5-ac1f6be4d7ba',
        'Wahlurnen',
        'tualodashboard_onlinechart',
        0,
        '{
    
}'
    );

insert
    ignore into dashboard (id, title, dashboard_type, position, configuration)
values
    (
        'c59eca3d-8ece-11ee-aac7-ac1f6bd9bb0c',
        'Status',
        'tualodashboard_onlinevote_synctest',
        1,
        '{
    
}'
    );