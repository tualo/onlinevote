delimiter ;

create table blocked_voters (
    voter_id varchar(36), 
    stimmzettel varchar(36),
    primary key(voter_id,stimmzettel)
);