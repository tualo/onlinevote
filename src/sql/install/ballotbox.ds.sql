DELIMITER ;

INSERT INTO
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
        'ballotbox',
        NULL,
        NULL,
        0,
        NULL,
        NULL,
        NULL,
        1,
        NULL,
        NULL,
        NULL,
        NULL,
        0,
        'cellmodel',
        NULL,
        0,
        NULL,
        NULL,
        1,
        'Wahlsystem',
        NULL,
        NULL,
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
    ) ON DUPLICATE KEY
UPDATE
    title =
VALUES
(title),
    reorderfield =
VALUES
(reorderfield),
    use_history =
VALUES
(use_history),
    searchfield =
VALUES
(searchfield),
    displayfield =
VALUES
(displayfield),
    sortfield =
VALUES
(sortfield),
    searchany =
VALUES
(searchany),
    hint =
VALUES
(hint),
    overview_tpl =
VALUES
(overview_tpl),
    sync_table =
VALUES
(sync_table),
    writetable =
VALUES
(writetable),
    globalsearch =
VALUES
(globalsearch),
    listselectionmodel =
VALUES
(listselectionmodel),
    sync_view =
VALUES
(sync_view),
    syncable =
VALUES
(syncable),
    cssstyle =
VALUES
(cssstyle),
    alternativeformxtype =
VALUES
(alternativeformxtype),
    read_table =
VALUES
(read_table),
    class_name =
VALUES
(class_name),
    special_add_panel =
VALUES
(special_add_panel),
    existsreal =
VALUES
(existsreal),
    character_set_name =
VALUES
(character_set_name),
    read_filter =
VALUES
(read_filter),
    listxtypeprefix =
VALUES
(listxtypeprefix),
    phpexporter =
VALUES
(phpexporter),
    phpexporterfilename =
VALUES
(phpexporterfilename),
    combined =
VALUES
(combined),
    default_pagesize =
VALUES
(default_pagesize),
    allowForm =
VALUES
(allowForm),
    listviewbaseclass =
VALUES
(listviewbaseclass),
    showactionbtn =
VALUES
(showactionbtn),
    modelbaseclass =
VALUES
(modelbaseclass);

INSERT
    IGNORE INTO `ds_column` (
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
        'ballotbox',
        'ballotpaper',
        NULL,
        10000000,
        0,
        0,
        NULL,
        'NO',
        'NO',
        NULL,
        NULL,
        'text',
        '',
        'text',
        65535,
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
        'ballotbox',
        'blocked',
        '0',
        0,
        0,
        0,
        '',
        'YES',
        'NO',
        '',
        '',
        'tinyint',
        'MUL',
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
        'ballotbox',
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
        'varchar',
        'PRI',
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
    ),
    (
        'ballotbox',
        'isvalid',
        '0',
        0,
        0,
        0,
        '',
        'YES',
        'NO',
        '',
        '',
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
        'ballotbox',
        'keyname',
        NULL,
        10000000,
        0,
        1,
        NULL,
        'NO',
        'NO',
        NULL,
        NULL,
        'varchar',
        'PRI',
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
    ),
    (
        'ballotbox',
        'saveerror',
        '0',
        0,
        0,
        0,
        '',
        'YES',
        'NO',
        '',
        '',
        'tinyint',
        'MUL',
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
        'ballotbox',
        'saveerrorid',
        '\'\'',
        0,
        0,
        0,
        '',
        'YES',
        'NO',
        '',
        '',
        'varchar',
        'MUL',
        'varchar(36)',
        36,
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
        'ballotbox',
        'stimmzettel',
        'NULL',
        0,
        0,
        0,
        '',
        'YES',
        'NO',
        '',
        '',
        'varchar',
        '',
        'varchar(15)',
        15,
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
        'ballotbox',
        'stimmzettel_id',
        NULL,
        NULL,
        NULL,
        NULL,
        NULL,
        'YES',
        NULL,
        NULL,
        NULL,
        'varchar',
        '',
        'varchar(10)',
        10,
        NULL,
        NULL,
        'utf8mb3',
        'select,insert,update,references',
        1,
        NULL,
        1,
        NULL,
        NULL
    ),
    (
        'ballotbox',
        'voter_id',
        'NULL',
        0,
        0,
        0,
        '',
        'YES',
        'NO',
        '',
        '',
        'varchar',
        '',
        'varchar(20)',
        20,
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
        'ballotbox',
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
        'ballotbox',
        'keyname',
        'DE',
        'keyname',
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
        'ballotbox',
        'id',
        'DE',
        'id',
        'displayfield',
        'Allgemein',
        0,
        0,
        1,
        1,
        '',
        1.00
    ),
    (
        'ballotbox',
        'keyname',
        'DE',
        'keyname',
        'displayfield',
        'Allgemein',
        0,
        0,
        1,
        1,
        '',
        1.00
    );

INSERT
    IGNORE INTO `ds_reference_tables` (
        `table_name`,
        `reference_table_name`,
        `columnsdef`,
        `active`,
        `constraint_name`,
        `searchable`,
        `autosync`,
        `position`,
        `path`,
        `existsreal`,
        `tabtitle`
    )
VALUES
    (
        'ballotbox',
        'pgpkeys',
        '{\"ballotbox__keyname\":\"pgpkeys__keyname\"}',
        0,
        'fk_ballotbox_pgpkeys',
        0,
        1,
        99999,
        '',
        1,
        ''
    );