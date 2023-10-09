delimiter ;

CREATE OR REPLACE VIEW `view_readtable_pgpkeys` AS
select
    `pgpkeys`.`keyname` AS `keyname`,
    `pgpkeys`.`keyid` AS `keyid`,
    `pgpkeys`.`fingerprint` AS `fingerprint`,
    `pgpkeys`.`username` AS `username`,
    `pgpkeys`.`publickey` AS `publickey`,
    '' AS `privatekey`,
    if(
        `pgpkeys`.`privatekey` is not null
        and `pgpkeys`.`privatekey` <> '',
        'vorhanden',
        'nicht vorhanden'
    ) AS `has_privatekey`,
    ifnull(`view_readtable_pgpkeys_valid`.`invalid`, 0) AS `invalid`,
    ifnull(`view_readtable_pgpkeys_valid`.`total`, 0) AS `total`,
    ifnull(`view_readtable_pgpkeys_valid`.`blocked`, 0) AS `blocked`,
    ifnull(`view_readtable_pgpkeys_valid`.`encrypted`, 0) AS `encrypted`
from
    (
        `pgpkeys`
        left join `view_readtable_pgpkeys_valid` on(
            `view_readtable_pgpkeys_valid`.`keyname` = `pgpkeys`.`keyname`
        )
    );
    