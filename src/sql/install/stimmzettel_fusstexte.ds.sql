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
        'stimmzettel_fusstexte',
        'Stimmzettel-Fusstexte',
        '',
        0,
        'name',
        'name',
        'name',
        1,
        'Fusstexte für Stimmzettel',
        '',
        '',
        '',
        0,
        'cellmodel',
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
        'stimmzettel_fusstexte',
        'id',
        '{:uuid()}',
        10000000,
        0,
        1,
        '',
        'NO',
        '0',
        '',
        '',
        'varchar',
        'PRI',
        'varchar(36)',
        36,
        0,
        0,
        'utf8',
        'select,insert,update,references',
        1,
        0,
        1,
        '\'\'',
        '\'\''
    ),
    (
        'stimmzettel_fusstexte',
        'name',
        '',
        10000000,
        0,
        0,
        '',
        'NO',
        '',
        '',
        '',
        'varchar',
        '',
        'varchar(255)',
        255,
        0,
        0,
        'utf8',
        'select,insert,update,references',
        1,
        0,
        1,
        '',
        ''
    ),
    (
        'stimmzettel_fusstexte',
        'text',
        '',
        10000000,
        0,
        0,
        '',
        'YES',
        '',
        '',
        '',
        'longtext',
        '',
        'longtext',
        4294967295,
        0,
        0,
        'utf8',
        'select,insert,update,references',
        1,
        0,
        1,
        '',
        ''
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
        'stimmzettel_fusstexte',
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
        '',
        '',
        0,
        '',
        'NULL'
    ),
    (
        'stimmzettel_fusstexte',
        'name',
        'DE',
        'Kurzbezeichnung',
        'gridcolumn',
        '',
        999,
        '',
        '',
        0,
        1,
        '',
        '',
        1.00,
        '',
        '',
        0,
        '',
        'NULL'
    ),
    (
        'stimmzettel_fusstexte',
        'text',
        'DE',
        'Text',
        'gridcolumn',
        '',
        999,
        '',
        '',
        0,
        1,
        '',
        '',
        1.00,
        '',
        '',
        0,
        '',
        'NULL'
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
        'stimmzettel_fusstexte',
        'id',
        'DE',
        'ID',
        'displayfield',
        'Allgemein',
        0,
        0,
        1,
        0,
        '',
        1.00
    ),
    (
        'stimmzettel_fusstexte',
        'name',
        'DE',
        'Kurzbezeichnung',
        'textfield',
        'Allgemein',
        1,
        0,
        1,
        0,
        '',
        1.00
    ),
    (
        'stimmzettel_fusstexte',
        'text',
        'DE',
        'Text',
        'textarea',
        'Allgemein',
        2,
        0,
        1,
        0,
        '',
        1.00
    );

INSERT
    IGNORE INTO `ds_dropdownfields` (
        `table_name`,
        `name`,
        `idfield`,
        `displayfield`,
        `filterconfig`
    )
VALUES
    ('stimmzettel_fusstexte', 'id', 'id', 'name', '');

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
        'stimmzettel_fusstexte',
        1,
        1,
        1,
        1,
        0
    ),
    ('_default_', 'stimmzettel_fusstexte', 1, 0, 0, 0, 0);