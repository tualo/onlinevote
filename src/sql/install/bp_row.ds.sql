DELIMITER;

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
        'bp_row',
        'Stimmzettel-Inhaltszeilen',
        'row_name',
        0,
        'row_name',
        'row_name',
        'pos',
        0,
        '',
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
        '',
        'XlsxWriter',
        'bp_row {DATE} {TIME}',
        0,
        1,
        '',
        '',
        1000,
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
        'bp_row',
        'active',
        NULL,
        NULL,
        NULL,
        NULL,
        NULL,
        'YES',
        NULL,
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
        NULL,
        1,
        NULL,
        NULL
    ),
    (
        'bp_row',
        'pos',
        NULL,
        NULL,
        NULL,
        NULL,
        NULL,
        'NO',
        NULL,
        NULL,
        NULL,
        'int',
        '',
        'int(11)',
        NULL,
        10,
        0,
        NULL,
        'select,insert,update,references',
        1,
        NULL,
        1,
        NULL,
        NULL
    ),
    (
        'bp_row',
        'prefix',
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
        'varchar(25)',
        25,
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
        'bp_row',
        'row_name',
        NULL,
        NULL,
        NULL,
        1,
        NULL,
        'NO',
        NULL,
        NULL,
        NULL,
        'varchar',
        'PRI',
        'varchar(255)',
        255,
        NULL,
        NULL,
        'utf8mb3',
        'select,insert,update,references',
        1,
        NULL,
        1,
        NULL,
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
        'bp_row',
        'active',
        'DE',
        'Aktiv',
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
        ''
    ),
    (
        'bp_row',
        'pos',
        'DE',
        'pos',
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
        '',
        '',
        0,
        '',
        ''
    ),
    (
        'bp_row',
        'prefix',
        'DE',
        'CSS-Prefix',
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
        ''
    ),
    (
        'bp_row',
        'row_name',
        'DE',
        'Zeilenname',
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
        ''
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
        'bp_row',
        'active',
        'DE',
        'active',
        'checkbox',
        'Allgemein',
        999,
        0,
        1,
        0,
        '',
        1.00
    ),
    (
        'bp_row',
        'pos',
        'DE',
        'pos',
        'displayfield',
        'Allgemein',
        999,
        1,
        1,
        0,
        '',
        1.00
    ),
    (
        'bp_row',
        'prefix',
        'DE',
        'prefix',
        'textfield',
        'Allgemein',
        999,
        0,
        1,
        1,
        '',
        1.00
    ),
    (
        'bp_row',
        'row_name',
        'DE',
        'row_name',
        'textfield',
        'Allgemein',
        999,
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
    ('bp_row', 'bp_row', 'row_name', 'row_name', '');

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
    ('administration', 'bp_row', 1, 1, 0, 1, 0),
    ('_default_', 'bp_row', 1, 0, 0, 0, 0);