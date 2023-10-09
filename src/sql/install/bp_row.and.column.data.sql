DELIMITER ;

INSERT INTO bp_row (row_name, active, pos, prefix) VALUES ('0', 1, 0, '');
INSERT INTO bp_row (row_name, active, pos, prefix) VALUES ('1', 1, 1, '');

INSERT INTO bp_column (column_name, active, pos, row_name, prefix) VALUES ('column-bgkl', 1, 0, '0', '');
INSERT INTO bp_column (column_name, active, pos, row_name, prefix) VALUES ('column-pic', 1, 1, '1', '-auto');
INSERT INTO bp_column (column_name, active, pos, row_name, prefix) VALUES ('column1', 1, 2, '1', '');
INSERT INTO bp_column (column_name, active, pos, row_name, prefix) VALUES ('column2', 1, 3, '1', '');


INSERT INTO bp_column_definition (column_field, column_name, pos, active, htmltag) VALUES ('barcode', 'column1', 0, 0, 'p');
INSERT INTO bp_column_definition (column_field, column_name, pos, active, htmltag) VALUES ('bgkl', 'column-bgkl', 0, 1, 'span');
INSERT INTO bp_column_definition (column_field, column_name, pos, active, htmltag) VALUES ('bgkl', 'column2', 1, 1, 'p');
INSERT INTO bp_column_definition (column_field, column_name, pos, active, htmltag) VALUES ('firm1', 'column2', 0, 1, 'p');
INSERT INTO bp_column_definition (column_field, column_name, pos, active, htmltag) VALUES ('person1', 'column1', 1, 1, 'p');
INSERT INTO bp_column_definition (column_field, column_name, pos, active, htmltag) VALUES ('person2', 'column1', 2, 0, 'p');
INSERT INTO bp_column_definition (column_field, column_name, pos, active, htmltag) VALUES ('person_function', 'column1', 3, 1, 'p');
INSERT INTO bp_column_definition (column_field, column_name, pos, active, htmltag) VALUES ('picture', 'column-pic', 0, 1, 'img');
