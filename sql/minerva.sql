CREATE DATABASE if NOT EXISTS minerva;
USE minerva;

CREATE TABLE users (
id INT AUTO_INCREMENT PRIMARY KEY,
name VARCHAR(100) NOT NULL,
email VARCHAR(150) NOT NULL UNIQUE,
password VARCHAR(255) NOT NULL,
role ENUM('student','teacher') NOT NULL,
created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);



CREATE TABLE classrooms (
id INT AUTO_INCREMENT PRIMARY KEY,
name VARCHAR(100) NOT NULL,
description TEXT,
teacher_id INT NOT NULL,
FOREIGN KEY (teacher_id) REFERENCES users(id) ON DELETE CASCADE
);


CREATE TABLE classroom_students (
    classroom_id INT NOT NULL,
    student_id INT NOT NULL,
    enrolled_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (classroom_id, student_id),
    FOREIGN KEY (classroom_id) REFERENCES classrooms(id) ON DELETE CASCADE,
    FOREIGN KEY (student_id) REFERENCES users(id) ON DELETE CASCADE
);


CREATE TABLE works (
id INT AUTO_INCREMENT PRIMARY KEY,
classroom_id INT NOT NULL,
title VARCHAR(150) NOT NULL,
description TEXT,
deadline DATE,
file_path VARCHAR(255),
created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
FOREIGN KEY (classroom_id) REFERENCES classrooms(id) ON DELETE CASCADE
);
CREATE TABLE work_assignments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    work_id INT,
    student_id INT,
    FOREIGN KEY (work_id) REFERENCES works(id),
    FOREIGN KEY (student_id) REFERENCES users(id)
);

CREATE TABLE submissions (
id INT AUTO_INCREMENT PRIMARY KEY,
work_id INT NOT NULL,
student_id INT NOT NULL,
content TEXT,
submitted_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
FOREIGN KEY (work_id) REFERENCES works(id) ON DELETE CASCADE,
FOREIGN KEY (student_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE grades (
id INT AUTO_INCREMENT PRIMARY KEY,
submission_id INT NOT NULL,
score FLOAT NOT NULL,
comment TEXT,
FOREIGN KEY (submission_id) REFERENCES submissions(id) ON DELETE CASCADE
);

CREATE TABLE attendance (
id INT AUTO_INCREMENT PRIMARY KEY,
classroom_id INT NOT NULL,
student_id INT NOT NULL,
date DATE NOT NULL,
status BOOLEAN NOT NULL,
FOREIGN KEY (classroom_id) REFERENCES classrooms(id) ON DELETE CASCADE,
FOREIGN KEY (student_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE chat_messages (
id INT AUTO_INCREMENT PRIMARY KEY,
classroom_id INT NOT NULL,
user_id INT NOT NULL,
message TEXT NOT NULL,
created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
FOREIGN KEY (classroom_id) REFERENCES classrooms(id) ON DELETE CASCADE,
FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);