# 📚 Education Management System - e-Communication Book (Raw PHP + MySQL)

A backend system built with **raw PHP** (no framework) to handle teacher-submitted communication records for students, including:

- Subject activity tracking
- Character trait flags
- Freeform notes
- Full CRUD for:
  - Students
  - Teachers
  - Subjects
- Communication book search by date, semester, quarter, academic year

---

## 🚀 Requirements

- XAMPP (Apache + MySQL + PHP >= 7.4)
- Composer (optional, not used here)
- phpMyAdmin or any SQL client for importing seed data

---

## 📁 Folder Structure

```
/xampp/htdocs/communication-book/
│
├── config/
│   └── database.php
│
├── controllers/
│   ├── CommunicationController.php
│   ├── StudentController.php
│   ├── TeacherController.php
│   └── SubjectController.php
│
├── models/
│   ├── StudentModel.php
│   ├── TeacherModel.php
│   └── SubjectModel.php
│
├── migrations/
│   └── seed_data.sql
│
├── public/
│   ├── index.php
│   └── .htaccess
│
└── README.md
```

---

## ⚙️ Setup Instructions

### 1. Copy or Clone the Project into XAMPP's htdocs

```bash
# inside your XAMPP htdocs directory
git clone <this-repo> communication-book
```

Or manually copy the files to:

```
C:\xampp\htdocs\communication-book\
```

---

### 2. Create the MySQL Database

In phpMyAdmin or your SQL client:

```sql
CREATE DATABASE communication_book;
```

Then import `migrations/seed_data.sql` into that DB. It includes:

- Table creation
- Some initial grades, students, subjects, and teachers

---

### 3. Configure DB Connection

Edit `config/database.php`:

```php
private $host = "localhost";
private $db_name = "communication_book";
private $username = "root";
private $password = ""; // use your XAMPP password if needed
```

---

### 4. Enable Apache mod_rewrite

Make sure Apache’s `mod_rewrite` is enabled in XAMPP settings.

Also make sure `.htaccess` inside `/public` has this:

```apache
RewriteEngine On
RewriteCond %{REQUEST_FILENAME} !-f
RewriteRule ^ index.php [QSA,L]
```

---

### 5. Visit Your API

Navigate to:

```
http://localhost/communication-book/public/
```

You’re ready to hit the REST endpoints.

---

## 🔌 API Endpoints

### 🎯 Communication Records

| Method | Endpoint | Description |
|--------|----------|-------------|
| POST   | `/api/communication-records` | Create a record |
| GET    | `/api/communication-records?student_id=1&date=...&semester=...` | Get one by filter |
| PUT    | `/api/communication-records?id=12` | Update a record |
| DELETE | `/api/communication-records?id=12` | Delete a record |

**Required GET Params:**

- `student_id`
- `date`
- `semester`
- `quarter`
- `academic_year`

---

### 🧑‍🎓 Students CRUD

| Method | Endpoint |
|--------|----------|
| GET    | `/api/students` |
| GET    | `/api/students?id=1` |
| POST   | `/api/students` |
| PUT    | `/api/students?id=1` |
| DELETE | `/api/students?id=1` |

```json
POST /api/students
{
  "full_name": "Abonket Meseret",
  "grade_id": 1,
  "section": "A"
}
```

---

### 🧑‍🏫 Teachers CRUD

Same pattern via `/api/teachers`

```json
POST /api/teachers
{
  "full_name": "Marta Gebre",
  "email": "marta@example.com"
}
```

---

### 📘 Subjects CRUD

Same pattern via `/api/subjects`

```json
POST /api/subjects
{
  "name": "English"
}
```

---

## 🔍 Search Communication Book

**Endpoint:**

```http
GET /api/communication-records?student_id=1&date=2025-06-23&semester=1&quarter=1&academic_year=2022-2023
```

**Response:**

```json
{
  "status": "success",
  "record": {
    "id": 12,
    "student_id": 1,
    "teacher_id": 1,
    "date": "2025-06-23",
    "semester": 1,
    "quarter": 1,
    "academic_year": "2022-2023",
    "need_extra_help_on": "Maths",
    "secure_tuition_fee_for": "",
    "letter_about": "Parent Day",
    "teacher_note": "Doing better",
    "parent_comment": "Thank you",
    "parent_signed": true,
    "subjects": [
      {
        "subject_id": 1,
        "subject": "English",
        "homework": true,
        "worksheet": false,
        "test_exam": true
      }
    ],
    "traits": {
      "GSD": true,
      "KPH": false,
      "GSA": true
    }
  }
}
```

---

## 🧪 Testing

Use any REST tool:

- [Postman](https://www.postman.com)
- [Thunder Client](https://www.thunderclient.com)
- [curl](https://curl.se/)

---

## 💡 Future Ideas

- Upload scanned worksheets
- PDF export of reports
- Admin dashboard (view all records)
- Teacher login with JWT
- Activity logs & timestamps

---

## 🧠 Stack

- PHP (vanilla)
- MySQL
- Apache (via XAMPP)
- Modular MVC-style structure

---
