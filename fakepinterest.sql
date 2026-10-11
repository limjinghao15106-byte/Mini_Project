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
CREATE TABLE categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title varchar(225),
    category enum ('Manga-style', 'neo-pop', 'semi-realism','realism')
);
-------------------categories------------------------------------------
use projectUSER;
INSERT INTO categories(title , category) VALUES ('Manga-style','Manga-style'),
                                                ('neo-pop','neo-pop'),
                                                ('semi-realism','semi-realism'),
                                                ('realism','realism');
------------------------------------------------------------------------------------------
CREATE TABLE posts (
    id int auto_increment primary key,
    title varchar(225) not null ,
    image varchar(225), 
    category_id int ,
    created_at timestamp default CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES categories(id)
);

---------------------------------------------------------------- post-------------------------------------------------
use projectUSER;

INSERT INTO posts (title ,image , category_id) VALUES 
('girl','https://i.pinimg.com/1200x/27/9a/26/279a268ad2b07effa8d79e466386b8d9.jpg',  1 ),
('Bocchi' , 'https://i.pinimg.com/1200x/0e/7e/76/0e7e765c0b0a5d4ffe89ea07dbe216c5.jpg' , 2),
('semi realism fieren', 'https://i.pinimg.com/736x/c3/8f/13/c38f13a73824433ae4763adbb9be4b8c.jpg' ,3),
('realism' , 'https://i.pinimg.com/736x/a6/2d/7a/a62d7a53a7635590bbb8a20162ce17d3.jpg' , 4),


('takamura' , 'https://i.pinimg.com/1200x/35/0d/75/350d751bd3340f445c3207b42cd69c37.jpg' , 1),
('prowler' , 'https://i.pinimg.com/736x/57/27/bb/5727bb1c83c44d2f7206ef7cc432e831.jpg' ,2),
('ball' , 'https://i.pinimg.com/736x/7a/69/90/7a6990c5acb7cf96f1a8f990eff73c61.jpg' , 3),
('squidward' , 'https://i.pinimg.com/1200x/0d/26/10/0d26104fc7e3df579f5f30eb55e99d4e.jpg' , 4),

('Zaraki Kenpachi' , 'https://i.pinimg.com/736x/9e/2b/c5/9e2bc56fae17815cb4d6668546e8c4b2.jpg' , 1),
('Fragpunk' , 'https://i.pinimg.com/1200x/e7/a7/b1/e7a7b1f115da34d6525bd04b85a6581b.jpg' , 2),
('黒髪' , 'https://i.pinimg.com/1200x/4d/4a/08/4d4a08ad3e1e422a4d4087f54d6aaee2.jpg' , 3),
('Leon' , 'https://i.pinimg.com/736x/5c/52/9b/5c529bdd753b3d795dca3ff578aaf875.jpg',4),

('Sekiro' , 'https://i.pinimg.com/1200x/e0/3e/ef/e03eef92125b5613c826376902bc5aaf.jpg' , 1),
('Neo' , 'https://i.pinimg.com/736x/2d/61/d6/2d61d6a988012f04283964729bd42c25.jpg' , 2),
('Man' , 'https://i.pinimg.com/736x/69/a9/d2/69a9d26d9d3a8f48d475b826a1b533d1.jpg' , 3),
('portrait' , 'https://i.pinimg.com/1200x/cd/fc/07/cdfc070f45defec2e5f5e1611667d936.jpg' , 4)


-------------------------------------------------------------------------------------------------------------------------
CREATE TABLE    saved_post(
    pin_id int auto_increment primary key,
    user_id int not null,
    post_id int not null,
    saved_at TIMESTAMP default CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(ID),
    FOREIGN KEY (post_id) REFERENCES posts(id)

);

-- gives you the post’s own data.
SELECT posts.id, posts.title, posts.image, categories.title AS category_name
-- pulls the category’s name, but renames it so i don’t confuse it with the post’s title.
FROM posts
-- links each post to the correct category.
JOIN categories ON posts.category_id = categories.id;
