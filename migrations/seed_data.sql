-- Grades
INSERT INTO grades (name, level) VALUES ('KG1', 1), ('Grade 1', 2);

-- Subjects
INSERT INTO subjects (name) VALUES ('English'), ('Maths'), ('Science');

-- Teachers
INSERT INTO teachers (full_name, email, password_hash) VALUES 
('Ms. Marta Gebre', 'marta@example.com', 'dummy');

-- Students
INSERT INTO students (full_name, grade_id, section) VALUES 
('Abonket Meseret', 1, 'A'), 
('Selam Berhanu', 1, 'B');

INSERT INTO grades (name, level) VALUES ('KG1', 1), ('Grade 1', 2);

INSERT INTO teachers (full_name, email) VALUES 
('Marta Gebre', 'marta@example.com'),
('Dereje Tesfaye', 'dereje@example.com');

INSERT INTO subjects (name) VALUES ('English'), ('Math'), ('Science');

INSERT INTO students (full_name, grade_id, section) VALUES 
('Abonket Meseret', 1, 'A'),
('Selam Berhanu', 1, 'B');
