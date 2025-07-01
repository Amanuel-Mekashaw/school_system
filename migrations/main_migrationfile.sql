
-- ----------------------------
-- Create and Use Database
-- ----------------------------
DROP DATABASE IF EXISTS e_communication;
CREATE DATABASE e_communication;
USE e_communication;

-- ----------------------------
-- Create Tables
-- ----------------------------

-- Grades
CREATE TABLE grades (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL,
    level INT NOT NULL
);

-- Students
CREATE TABLE students (
    id INT AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(100) NOT NULL,
    grade_id INT,
    section VARCHAR(10),
    FOREIGN KEY (grade_id) REFERENCES grades(id)
);

-- Teachers
CREATE TABLE teachers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(100) NOT NULL,
    email VARCHAR(100),
    password_hash VARCHAR(255)
);

-- Subjects
CREATE TABLE subjects (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL
);

-- Communication Records
CREATE TABLE communication_records (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT,
    teacher_id INT,
    date DATE,
    semester INT,
    quarter INT,
    academic_year VARCHAR(20),
    need_extra_help_on TEXT,
    secure_tuition_fee_for TEXT,
    letter_about TEXT,
    teacher_note TEXT,
    parent_comment TEXT,
    parent_signed BOOLEAN,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (student_id) REFERENCES students(id),
    FOREIGN KEY (teacher_id) REFERENCES teachers(id)
);

-- Communication Subjects
CREATE TABLE communication_subjects (
    id INT AUTO_INCREMENT PRIMARY KEY,
    communication_record_id INT,
    subject_id INT,
    homework BOOLEAN DEFAULT FALSE,
    worksheet BOOLEAN DEFAULT FALSE,
    test_exam BOOLEAN DEFAULT FALSE,
    FOREIGN KEY (communication_record_id) REFERENCES communication_records(id),
    FOREIGN KEY (subject_id) REFERENCES subjects(id)
);

-- Communication Traits
CREATE TABLE communication_traits (
    id INT AUTO_INCREMENT PRIMARY KEY,
    communication_record_id INT,
    trait_code VARCHAR(10),
    value BOOLEAN,
    FOREIGN KEY (communication_record_id) REFERENCES communication_records(id)
);

-- Optional: Traits Reference
CREATE TABLE traits_reference (
    code VARCHAR(10) PRIMARY KEY,
    description TEXT
);

-- ----------------------------
-- Seed Data
-- ----------------------------

-- Grades
INSERT INTO grades (id, name, level) VALUES
(1, 'KG1', 1),
(2, 'KG2', 2),
(3, 'Grade 1', 3),
(4, 'Grade 2', 4);

-- Students
INSERT INTO students (id, full_name, grade_id, section) VALUES
(1, 'Abonket Meseret', 1, 'A'),
(2, 'Ruth Alemayehu', 1, 'A'),
(3, 'Tadesse Solomon', 2, 'B');

-- Teachers
INSERT INTO teachers (id, full_name, email, password_hash) VALUES
(1, 'Marta Gebre', 'marta@example.com', '$2y$10$testpasswordhash'),
(2, 'Daniel Tesfaye', 'daniel@example.com', '$2y$10$testpasswordhash');

-- Subjects
INSERT INTO subjects (id, name) VALUES
(1, 'English'),
(2, 'Maths'),
(3, 'Science'),
(4, 'Art');

-- Communication Record
INSERT INTO communication_records (
    id, student_id, teacher_id, date, semester, quarter, academic_year,
    need_extra_help_on, secure_tuition_fee_for, letter_about,
    teacher_note, parent_comment, parent_signed, created_at
) VALUES
(1, 1, 1, '2025-06-23', 1, 1, '2022-2023',
 'Maths', '', 'Sene 30 School Closing and Parent Day',
 'Thank you', 'Thank you too', 1, NOW());

-- Communication Subjects
INSERT INTO communication_subjects (
    id, communication_record_id, subject_id, homework, worksheet, test_exam
) VALUES
(1, 1, 1, 1, 0, 0),  -- English
(2, 1, 2, 0, 1, 0);  -- Maths

-- Communication Traits
INSERT INTO communication_traits (
    id, communication_record_id, trait_code, value
) VALUES
(1, 1, 'GSD', 1),
(2, 1, 'KPH', 0),
(3, 1, 'GSA', 1),
(4, 1, 'FLS', 0),
(5, 1, 'GCP', 1),
(6, 1, 'GJT', 0);

-- Traits Reference
INSERT INTO traits_reference (code, description) VALUES
('GSD', 'Gets stuff done'),
('KPH', 'Keeps personal hygiene'),
('GSA', 'Gets along with others'),
('FLS', 'Follows instructions'),
('CHW', 'Completes homework'),
('SGB', 'Shows good behavior'),
('GHS', 'Good handwriting skills'),
('LMT', 'Listens most of the time'),
('GCP', 'Good class participation'),
('GJT', 'Good judgment');
