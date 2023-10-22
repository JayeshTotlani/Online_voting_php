-- Create roles table
CREATE TABLE roles (
    roleId int primary key,
    role varchar(30)
);

-- Insert roles into the roles table
INSERT INTO roles (roleId, role) VALUES
(1, 'Admin'),
(2, 'User');

-- Modify user_details table to include roleId as a foreign key
CREATE TABLE user_details (
    userID int primary key auto_increment,
    firstname varchar(30),
    lastname varchar(30),
    username varchar(30) unique key,
    email varchar(50),
    password varchar(100),
    roleId int,
    foreign key (roleId) references roles(roleId)
);

-- Polls Table
CREATE TABLE polls (
    poll_id INT AUTO_INCREMENT PRIMARY KEY,
    question TEXT NOT NULL,
    status BOOLEAN DEFAULT true,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Options Table
CREATE TABLE options (
    option_id INT AUTO_INCREMENT PRIMARY KEY,
    poll_id INT,
    option_text TEXT NOT NULL,
    votes INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (poll_id) REFERENCES polls(poll_id)
);
