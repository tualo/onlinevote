delimiter ; 

CREATE OR REPLACE VIEW `view_readtable_pgpkeys_valid` AS
select
    `a`.`keyname` AS `keyname`,
    cast(
        sum(if(`a`.`x` <> `a`.`hash_value`, 1, 0)) as signed
    ) AS `invalid`,
    count(0) AS `total`,
    sum(`a`.`blocked`) AS `blocked`,
    sum(`a`.`decrypted`) AS `decrypted`
from
    (
        select
            `ballotbox`.`keyname` AS `keyname`,
            `ballotbox`.`id` AS `id`,
            `ballotbox`.`blocked` AS `blocked`,
            `ballotbox`.`voter_id` AS `voter_id`,
            md5(
                concat(
                    `ballotbox_blockchain`.`last_hash`,
                    md5(`ballotbox`.`ballotpaper`)
                )
            ) AS `x`,
            `ballotbox_blockchain`.`id` AS `bbid`,
            `ballotbox_blockchain`.`hash_value` AS `hash_value`,
            if(`ballotbox_decrypted`.`id` is null, 0, 1) AS `decrypted`
        from
            (
                (
                    `ballotbox_blockchain`
                    join `ballotbox` on(
                        (
                            `ballotbox_blockchain`.`ballotpaper_id`,
                            `ballotbox_blockchain`.`keyname`
                        ) = (`ballotbox`.`id`, `ballotbox`.`keyname`)
                    )
                )
                left join `ballotbox_decrypted` on(
                    (`ballotbox`.`id`, `ballotbox`.`keyname`) = (
                        `ballotbox_decrypted`.`id`,
                        `ballotbox_decrypted`.`keyname`
                    )
                )
            )
        where
            `ballotbox`.`saveerror` = 0
        group by
            `ballotbox`.`keyname`,
            `ballotbox`.`id`
    ) `a`
group by
    `a`.`keyname` 
;

CREATE
    OR REPLACE VIEW `view_readtable_pgpkeys` AS
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
    ifnull(`view_readtable_pgpkeys_valid`.`decrypted`, 0) AS `decrypted`,
    (
        ifnull(`view_readtable_pgpkeys_valid`.`total`, 0) -
        ifnull(`view_readtable_pgpkeys_valid`.`blocked`, 0) -
        ifnull(`view_readtable_pgpkeys_valid`.`decrypted`, 0)
    ) AS `encrypted`
from
    (
        `pgpkeys`
        left join `view_readtable_pgpkeys_valid` on(
            `view_readtable_pgpkeys_valid`.`keyname` = `pgpkeys`.`keyname`
        )
    );


create or replace view view_readtable_pgpkeys_intern as
select 
    pgpkeys.keyname,
    pgpkeys.keyid,
    pgpkeys.fingerprint,
    pgpkeys.username,
    pgpkeys.publickey,
    pgpkeys.privatekey, 
    if (pgpkeys.privatekey is not null and pgpkeys.privatekey<>'', 'vorhanden', 'nicht vorhanden' ) has_privatekey,
    ifnull(view_readtable_pgpkeys_valid.invalid,0) invalid,
    ifnull(view_readtable_pgpkeys_valid.total,0) total,
    ifnull(view_readtable_pgpkeys_valid.decrypted,0) `decrypted`
from 
    pgpkeys 
    left join view_readtable_pgpkeys_valid
        on view_readtable_pgpkeys_valid.keyname = pgpkeys.keyname;
;

call fill_ds_column('pgpkeys');