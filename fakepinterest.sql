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

CREATE TABLE    saved_post(
    pin_id int auto_increment primary key,
    user_id int not null,
    post_id int not null,
    saved_at TIMESTAMP default CURRENT_TIMESTAMP,
    foreign key (user_id) references users(id),
    foreign key (post_id) references post(id)

);


CREATE TABLE categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title varchar(225),
    category enum ('kagenashi', 'neo-pop', 'semi-realism','Monochrome')
);

