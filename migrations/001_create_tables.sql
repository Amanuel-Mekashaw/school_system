CREATE TABLE IF NOT EXISTS grades (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL,
    level INT NOT NULL
);

CREATE TABLE IF NOT EXISTS students (
    id INT AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(100) NOT NULL,
    grade_id INT NOT NULL,
    section VARCHAR(50),
    FOREIGN KEY (grade_id) REFERENCES grades(id)
);

CREATE TABLE IF NOT EXISTS teachers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL -- store hashed password
);

CREATE TABLE IF NOT EXISTS subjects (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL
);

CREATE TABLE IF NOT EXISTS communication_records (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT NOT NULL,
    teacher_id INT NOT NULL,
    date DATE NOT NULL,
    semester INT NOT NULL,
    quarter INT NOT NULL,
    academic_year VARCHAR(20) NOT NULL,
    need_extra_help_on TEXT,
    secure_tuition_fee_for TEXT,
    letter_about TEXT,
    teacher_note TEXT,
    parent_comment TEXT,
    parent_signed BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (student_id) REFERENCES students(id),
    FOREIGN KEY (teacher_id) REFERENCES teachers(id)
);

CREATE TABLE IF NOT EXISTS communication_subjects (
    id INT AUTO_INCREMENT PRIMARY KEY,
    communication_record_id INT NOT NULL,
    subject_id INT NOT NULL,
    homework BOOLEAN DEFAULT FALSE,
    worksheet BOOLEAN DEFAULT FALSE,
    test_exam BOOLEAN DEFAULT FALSE,
    FOREIGN KEY (communication_record_id) REFERENCES communication_records(id),
    FOREIGN KEY (subject_id) REFERENCES subjects(id)
);

CREATE TABLE IF NOT EXISTS communication_traits (
    id INT AUTO_INCREMENT PRIMARY KEY,
    communication_record_id INT NOT NULL,
    trait_code VARCHAR(10) NOT NULL,
    value BOOLEAN NOT NULL DEFAULT FALSE,
    FOREIGN KEY (communication_record_id) REFERENCES communication_records(id)
);

CREATE TABLE IF NOT EXISTS traits_reference (
    code VARCHAR(10) PRIMARY KEY,
    description TEXT NOT NULL
);
