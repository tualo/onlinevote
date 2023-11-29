delimiter ;

create table if not exists blocked_synced
(
    id integer not null primary key,ts datetime not null ,`count`integer not null
);