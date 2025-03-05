delimiter ;

create or replace view view_ballotbox_decrypted_sum as 

select 
    max(id) id ,
    keyname,
    stimmzettel,
    status,
    isvalid,
    count(*) anzahl

from 
(
    select 
        id,
        keyname,
        ballotpaper,
        isvalid,
        stimmzettel,
        if (ballotpaper='[]','Enthaltung',if(isvalid=0,'Ungültig','gültig')) status
    from ballotbox_decrypted   where keyname = (select min(keyname) kn from ballotbox_decrypted)
) x
group by     
    stimmzettel,
    status
;
