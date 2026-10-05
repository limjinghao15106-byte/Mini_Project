CREATE database projectUSER;
show databases;
use projectUSER;

CREATE TABLE users (
    ID int auto_increment primary key,
    username varchar(225) not null ,
    email varchar(225) not null ,
    role  enum ('Admin' , 'Staff' , 'User') default 'user',
    create_at timestamp default CURRENT_TIMESTAMP,
    hashedPassword varchar(255) not null
    
);

CREATE TABLE posts (
    id int auto_increment primary key,
    title varchar(225) not null ,
    body  text , 
    user_id int  not null ,
    status varchar(225) not null,
    created_at timestamp default CURRENT_TIMESTAMP



);

    
CREATE TABLE follows (
    following_user_id int auto_increment primary key,
    created_at timestamp default CURRENT_TIMESTAMP
);

