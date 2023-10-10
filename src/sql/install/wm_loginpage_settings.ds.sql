DELIMITER;

REPLACE INTO
    `ds` (
        `table_name`,
        `title`,
        `reorderfield`,
        `use_history`,
        `searchfield`,
        `displayfield`,
        `sortfield`,
        `searchany`,
        `hint`,
        `overview_tpl`,
        `sync_table`,
        `writetable`,
        `globalsearch`,
        `listselectionmodel`,
        `sync_view`,
        `syncable`,
        `cssstyle`,
        `read_table`,
        `existsreal`,
        `class_name`,
        `special_add_panel`,
        `read_filter`,
        `listxtypeprefix`,
        `phpexporter`,
        `phpexporterfilename`,
        `combined`,
        `allowForm`,
        `alternativeformxtype`,
        `character_set_name`,
        `default_pagesize`,
        `listviewbaseclass`,
        `showactionbtn`
    )
VALUES
    (
        'wm_loginpage_settings',
        'WM Einstellungen',
        '',
        0,
        'id',
        'id',
        'id',
        1,
        'jjk',
        '',
        '',
        '',
        0,
        'tualomultirowmodel',
        '',
        0,
        '',
        '',
        1,
        'Wahlsystem',
        '',
        '',
        'listview',
        'XlsxWriter',
        '{GUID}',
        0,
        1,
        '',
        '',
        100,
        'Tualo.DataSets.ListView',
        1
    ) ;

REPLACE INTO `ds_column` (
        `table_name`,
        `column_name`,
        `default_value`,
        `default_max_value`,
        `default_min_value`,
        `is_primary`,
        `update_value`,
        `is_nullable`,
        `is_referenced`,
        `referenced_table`,
        `referenced_column_name`,
        `data_type`,
        `column_key`,
        `column_type`,
        `character_maximum_length`,
        `numeric_precision`,
        `numeric_scale`,
        `character_set_name`,
        `privileges`,
        `existsreal`,
        `deferedload`,
        `writeable`,
        `note`,
        `hint`
    )
VALUES
    (
        'wm_loginpage_settings',
        'backendurl',
        NULL,
        10000000,
        0,
        0,
        NULL,
        'YES',
        'NO',
        NULL,
        NULL,
        'varchar',
        '',
        'varchar(150)',
        150,
        NULL,
        NULL,
        'utf8mb3',
        'select,insert,update,references',
        1,
        0,
        1,
        '',
        NULL
    ),
    (
        'wm_loginpage_settings',
        'id',
        NULL,
        10000000,
        0,
        1,
        NULL,
        'NO',
        'NO',
        NULL,
        NULL,
        'int',
        'PRI',
        'int(11)',
        NULL,
        10,
        0,
        NULL,
        'select,insert,update,references',
        1,
        0,
        1,
        '',
        NULL
    ),
    (
        'wm_loginpage_settings',
        'index_redirect',
        NULL,
        10000000,
        0,
        0,
        NULL,
        'YES',
        'NO',
        NULL,
        NULL,
        'varchar',
        '',
        'varchar(150)',
        150,
        NULL,
        NULL,
        'utf8mb3',
        'select,insert,update,references',
        1,
        0,
        1,
        '',
        NULL
    ),
    (
        'wm_loginpage_settings',
        'interrupted',
        NULL,
        10000000,
        0,
        0,
        NULL,
        'YES',
        'NO',
        NULL,
        NULL,
        'tinyint',
        '',
        'tinyint(4)',
        NULL,
        3,
        0,
        NULL,
        'select,insert,update,references',
        1,
        0,
        1,
        '',
        NULL
    ),
    (
        'wm_loginpage_settings',
        'sendsms',
        NULL,
        10000000,
        0,
        0,
        NULL,
        'YES',
        'NO',
        NULL,
        NULL,
        'tinyint',
        '',
        'tinyint(4)',
        NULL,
        3,
        0,
        NULL,
        'select,insert,update,references',
        1,
        0,
        1,
        '',
        NULL
    ),
    (
        'wm_loginpage_settings',
        'smsauth',
        NULL,
        10000000,
        0,
        0,
        NULL,
        'YES',
        'NO',
        NULL,
        NULL,
        'varchar',
        '',
        'varchar(150)',
        150,
        NULL,
        NULL,
        'utf8mb3',
        'select,insert,update,references',
        1,
        0,
        1,
        '',
        NULL
    ),
    (
        'wm_loginpage_settings',
        'starttime',
        NULL,
        10000000,
        0,
        0,
        NULL,
        'YES',
        'NO',
        NULL,
        NULL,
        'datetime',
        '',
        'datetime',
        NULL,
        NULL,
        NULL,
        NULL,
        'select,insert,update,references',
        1,
        0,
        1,
        '',
        NULL
    ),
    (
        'wm_loginpage_settings',
        'stoptime',
        NULL,
        10000000,
        0,
        0,
        NULL,
        'YES',
        'NO',
        NULL,
        NULL,
        'datetime',
        '',
        'datetime',
        NULL,
        NULL,
        NULL,
        NULL,
        'select,insert,update,references',
        1,
        0,
        1,
        '',
        NULL
    ),
    (
        'wm_loginpage_settings',
        'tmgurl',
        NULL,
        10000000,
        0,
        0,
        NULL,
        'YES',
        'NO',
        NULL,
        NULL,
        'varchar',
        '',
        'varchar(150)',
        150,
        NULL,
        NULL,
        'utf8mb3',
        'select,insert,update,references',
        1,
        0,
        1,
        '',
        NULL
    ),
    (
        'wm_loginpage_settings',
        'wsapitoken',
        NULL,
        10000000,
        0,
        0,
        NULL,
        'YES',
        'NO',
        NULL,
        NULL,
        'varchar',
        '',
        'varchar(150)',
        150,
        NULL,
        NULL,
        'utf8mb3',
        'select,insert,update,references',
        1,
        0,
        1,
        '',
        NULL
    ),
    (
        'wm_loginpage_settings',
        'wsregistertoken',
        NULL,
        10000000,
        0,
        0,
        NULL,
        'YES',
        'NO',
        NULL,
        NULL,
        'varchar',
        '',
        'varchar(150)',
        150,
        NULL,
        NULL,
        'utf8mb3',
        'select,insert,update,references',
        1,
        0,
        1,
        '',
        NULL
    ),
    (
        'wm_loginpage_settings',
        'wszapitoken',
        NULL,
        10000000,
        0,
        0,
        NULL,
        'YES',
        'NO',
        NULL,
        NULL,
        'varchar',
        '',
        'varchar(50)',
        50,
        NULL,
        NULL,
        'utf8mb3',
        'select,insert,update,references',
        1,
        0,
        1,
        '',
        NULL
    );

INSERT
    IGNORE INTO `ds_column_list_label` (
        `table_name`,
        `column_name`,
        `language`,
        `label`,
        `xtype`,
        `editor`,
        `position`,
        `summaryrenderer`,
        `summarytype`,
        `hidden`,
        `active`,
        `renderer`,
        `filterstore`,
        `flex`,
        `direction`,
        `align`,
        `grouped`,
        `listfiltertype`,
        `hint`
    )
VALUES
    (
        'wm_loginpage_settings',
        'backendurl',
        'DE',
        'backendurl',
        'gridcolumn',
        '',
        999,
        '',
        '',
        1,
        1,
        '',
        '',
        1.00,
        'ASC',
        'left',
        0,
        '',
        NULL
    ),
    (
        'wm_loginpage_settings',
        'id',
        'DE',
        'id',
        'gridcolumn',
        '',
        0,
        '',
        '',
        0,
        1,
        '',
        '',
        1.00,
        'ASC',
        'left',
        0,
        '',
        NULL
    ),
    (
        'wm_loginpage_settings',
        'index_redirect',
        'DE',
        'index_redirect',
        'gridcolumn',
        '',
        999,
        '',
        '',
        1,
        1,
        '',
        '',
        1.00,
        'ASC',
        'left',
        0,
        '',
        NULL
    ),
    (
        'wm_loginpage_settings',
        'interrupted',
        'DE',
        'interrupted',
        'gridcolumn',
        '',
        999,
        '',
        '',
        1,
        1,
        '',
        '',
        1.00,
        'ASC',
        'left',
        0,
        '',
        NULL
    ),
    (
        'wm_loginpage_settings',
        'sendsms',
        'DE',
        'sendsms',
        'gridcolumn',
        '',
        999,
        '',
        '',
        1,
        1,
        '',
        '',
        1.00,
        'ASC',
        'left',
        0,
        '',
        NULL
    ),
    (
        'wm_loginpage_settings',
        'smsauth',
        'DE',
        'smsauth',
        'gridcolumn',
        '',
        999,
        '',
        '',
        1,
        1,
        '',
        '',
        1.00,
        'ASC',
        'left',
        0,
        '',
        NULL
    ),
    (
        'wm_loginpage_settings',
        'starttime',
        'DE',
        'starttime',
        'gridcolumn',
        '',
        999,
        '',
        '',
        1,
        1,
        '',
        '',
        1.00,
        'ASC',
        'left',
        0,
        '',
        NULL
    ),
    (
        'wm_loginpage_settings',
        'stoptime',
        'DE',
        'stoptime',
        'gridcolumn',
        '',
        999,
        '',
        '',
        1,
        1,
        '',
        '',
        1.00,
        'ASC',
        'left',
        0,
        '',
        NULL
    ),
    (
        'wm_loginpage_settings',
        'tmgurl',
        'DE',
        'tmgurl',
        'gridcolumn',
        '',
        999,
        '',
        '',
        1,
        1,
        '',
        '',
        1.00,
        'ASC',
        'left',
        0,
        '',
        NULL
    ),
    (
        'wm_loginpage_settings',
        'wsapitoken',
        'DE',
        'wsapitoken',
        'gridcolumn',
        '',
        999,
        '',
        '',
        1,
        1,
        '',
        '',
        1.00,
        'ASC',
        'left',
        0,
        '',
        NULL
    ),
    (
        'wm_loginpage_settings',
        'wsregistertoken',
        'DE',
        'wsregistertoken',
        'gridcolumn',
        '',
        999,
        '',
        '',
        1,
        1,
        '',
        '',
        1.00,
        'ASC',
        'left',
        0,
        '',
        NULL
    ),
    (
        'wm_loginpage_settings',
        'wszapitoken',
        'DE',
        'wszapitoken',
        'gridcolumn',
        '',
        999,
        '',
        '',
        1,
        1,
        '',
        '',
        1.00,
        'ASC',
        'left',
        0,
        '',
        NULL
    );

INSERT
    IGNORE INTO `ds_column_form_label` (
        `table_name`,
        `column_name`,
        `language`,
        `label`,
        `xtype`,
        `field_path`,
        `position`,
        `hidden`,
        `active`,
        `allowempty`,
        `fieldgroup`,
        `flex`
    )
VALUES
    (
        'wm_loginpage_settings',
        'backendurl',
        'DE',
        'Backend URL',
        'textfield',
        'Allgemein/Angaben',
        4,
        0,
        1,
        1,
        '',
        1.00
    ),
    (
        'wm_loginpage_settings',
        'id',
        'DE',
        'ID',
        'displayfield',
        'Allgemein/Angaben',
        0,
        0,
        1,
        1,
        '',
        1.00
    ),
    (
        'wm_loginpage_settings',
        'index_redirect',
        'DE',
        'index_redirect',
        'displayfield',
        'Allgemein/Angaben',
        7,
        1,
        1,
        1,
        '',
        1.00
    ),
    (
        'wm_loginpage_settings',
        'interrupted',
        'DE',
        'Unterbrochen',
        'checkbox',
        'Allgemein',
        999,
        0,
        1,
        1,
        '',
        1.00
    ),
    (
        'wm_loginpage_settings',
        'sendsms',
        'DE',
        'Send-SMS',
        'displayfield',
        'Allgemein/Angaben',
        2,
        1,
        1,
        1,
        '',
        1.00
    ),
    (
        'wm_loginpage_settings',
        'smsauth',
        'DE',
        'SMS-Auth',
        'displayfield',
        'Allgemein/Angaben',
        1,
        1,
        1,
        1,
        '',
        1.00
    ),
    (
        'wm_loginpage_settings',
        'starttime',
        'DE',
        'Starttime',
        'datetimefield',
        'Allgemein/Angaben',
        8,
        0,
        1,
        1,
        '',
        1.00
    ),
    (
        'wm_loginpage_settings',
        'stoptime',
        'DE',
        'Stoptime',
        'datetimefield',
        'Allgemein/Angaben',
        9,
        0,
        1,
        1,
        '',
        1.00
    ),
    (
        'wm_loginpage_settings',
        'tmgurl',
        'DE',
        'tmgurl',
        'displayfield',
        'Allgemein/Angaben',
        3,
        1,
        1,
        1,
        '',
        1.00
    ),
    (
        'wm_loginpage_settings',
        'wsapitoken',
        'DE',
        'WS API-Token',
        'textfield',
        'Allgemein/Angaben',
        5,
        0,
        1,
        1,
        '',
        1.00
    ),
    (
        'wm_loginpage_settings',
        'wsregistertoken',
        'DE',
        'WS Registertoken',
        'textfield',
        'Allgemein/Angaben',
        6,
        0,
        1,
        1,
        '',
        1.00
    ),
    (
        'wm_loginpage_settings',
        'wszapitoken',
        'DE',
        'WSZ - API-Token',
        'textfield',
        'Allgemein/Angaben',
        10,
        0,
        1,
        1,
        '',
        1.00
    );

INSERT
    IGNORE INTO `ds_access` (
        `role`,
        `table_name`,
        `read`,
        `write`,
        `delete`,
        `append`,
        `existsreal`
    )
VALUES
    (
        'administration',
        'wm_loginpage_settings',
        0,
        1,
        1,
        1,
        0
    ),
    ('_default_', 'wm_loginpage_settings', 1, 0, 0, 0, 0);