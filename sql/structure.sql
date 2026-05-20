CREATE DATABASE peersync;
USE peersync;
DROP DATABASE peersync;
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(150) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM(
        'student',
        'tutor',
        'admin'
    ) DEFAULT 'student',
    points INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
CREATE TABLE help_requests (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    technology VARCHAR(100) NOT NULL,
    status ENUM(
        'pending',
        'assigned',
        'resolved'
    ) DEFAULT 'pending',
    comment TEXT NULL,
    student_id INT NOT NULL,
    tutor_id INT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    resolved_at TIMESTAMP NULL,
    FOREIGN KEY (student_id)
    REFERENCES users(id)
    ON DELETE CASCADE,
    FOREIGN KEY (tutor_id)
    REFERENCES users(id)
    ON DELETE SET NULL
);

CREATE TABLE reviews (
    id INT AUTO_INCREMENT PRIMARY KEY,
    rating INT NOT NULL,
    comment TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT check_rating
    CHECK (rating >= 1 AND rating <= 5),
     help_request_id INT UNIQUE NOT NULL,
    FOREIGN KEY (help_request_id)
    REFERENCES help_requests(id)
    ON DELETE CASCADE
);

CREATE TABLE badges (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) UNIQUE NOT NULL,
    description TEXT NOT NULL,
    required_points INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE user_badges (
    id INT AUTO_INCREMENT PRIMARY KEY,
    earned_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    user_id INT NOT NULL,
    badge_id INT NOT NULL,
    FOREIGN KEY (user_id)
    REFERENCES users(id)
    ON DELETE CASCADE,
    FOREIGN KEY (badge_id)
    REFERENCES badges(id)
    ON DELETE CASCADE
);

INSERT INTO users (
    name,
    email,
    password,
    role,
    points
)

VALUES

(
    'Ayoub Sofi',
    'ayoub@gmail.com',
    '123456',
    'student',
    0
),

(
    'Sara Amrani',
    'sara@gmail.com',
    '123456',
    'tutor',
    120
),

(
    'Yassine Alaoui',
    'yassine@gmail.com',
    '123456',
    'tutor',
    80
),

(
    'Admin ENAA',
    'admin@gmail.com',
    '123456',
    'admin',
    0
);

INSERT INTO help_requests (
    student_id,
    tutor_id,
    title,
    description,
    technology,
    status,
    comment
)

VALUES

(
    1,
    2,
    'Problem with PHP OOP',
    'I do not understand inheritance in PHP',
    'PHP',
    'assigned',
    NULL
),

(
    1,
    3,
    'Need help with SQL JOIN',
    'I have problem with INNER JOIN',
    'SQL',
    'resolved',
    'Thank you for your help'
),

(
    1,
    NULL,
    'JavaScript DOM issue',
    'I cannot update DOM dynamically',
    'JavaScript',
    'pending',
    NULL
);


INSERT INTO reviews (
    help_request_id,
    rating,
    comment
)VALUES
(
    2,
    5,
    'Excellent tutor and very helpful'
);

INSERT INTO badges (
    name,
    description,
    required_points
)VALUES('PHP Expert',
    'Complete 5 PHP sessions',
    100),('SQL Master',
    'Help students in SQL',
    150
),('Community Hero',
    'Complete many tutoring sessions',
    300);
INSERT INTO user_badges (
    user_id,
    badge_id
)VALUES(2, 1),(3, 2);