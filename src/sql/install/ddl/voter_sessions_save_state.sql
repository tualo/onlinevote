delimiter ;
create table if not exists voter_sessions_save_state (  
    session_id varchar(36) primary key not null, 
    created_at datetime not null );