# JeniusMinds – Interactive Quiz Platform

<div align="center">
    
[![Laravel](https://img.shields.io/badge/Laravel-12.x-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)](https://laravel.com)
[![PHP](https://img.shields.io/badge/PHP-8.4+-777BB4?style=for-the-badge&logo=php&logoColor=white)](https://php.net)
[![Tailwind CSS](https://img.shields.io/badge/Tailwind-CSS-06B6D4?style=for-the-badge&logo=tailwindcss&logoColor=white)](https://tailwindcss.com)

[![Alpine.js](https://img.shields.io/badge/Alpine.js-8BC5C3?style=for-the-badge&logo=alpinedotjs&logoColor=black)](https://alpinejs.dev)
[![License: MIT](https://img.shields.io/badge/License-MIT-00D4AA?style=for-the-badge)](https://opensource.org/licenses/MIT)
</div>

*JeniusMinds* is a Laravel-based web application designed to provide a dynamic, engaging, and educational quiz platform. It empowers users—such as teachers, students, or content creators—to create and take custom quizzes with various question types in a streamlined learning environment.

---

## Project Goals

JeniusMinds aims to offer an educational tool that:

* Encourages student engagement through gamified quizzes.
* Enables educators to easily design and manage custom quizzes.
* Supports different learning styles via diverse question formats.
* Provides immediate feedback and scoring to enhance the learning process.

---
## Teams
* Jonathan Alexander (03082230031) - Backend
* Misellin Mindany (03082230005) - Frontend, UI, Database

---

## Technology Stack

| Tool             | Description                                   |
| ---------------- | --------------------------------------------- |
| Laravel 12       | Backend framework                             |
| Tailwind CSS     | Utility-first CSS framework for styling       |
| Alpine.js        | Lightweight JavaScript framework for UI logic |
| MySQL / SQLite   | Relational database                           |
| Vite             | Frontend build tool and asset bundler         |
| Laravel Queues   | Background job handling (e.g., quiz results)  |

---

## Key Features

### User Management

* Secure registration and login system.
* User profile editing (name, password).

### Quiz Creation and Management

* Create quizzes by selecting a topic.
* Add multiple types of questions:
  * *Multiple Choice (Button):* Single correct answer.
  * *Checkboxes:* Multiple correct answers.
  * *Ordering (Reorder):* Arrange items in the correct sequence.
  * *Text Input (TypeAnswer):* User types a short answer.
* Temporary question saving via sessionStorage for safety.
* Edit or delete existing quizzes from the user dashboard.

### Taking a Quiz

* Clean, focused UI for answering questions.
* Easy navigation between questions (Previous/Next).
* Countdown timer for added challenge.
* Instant feedback with final score and time taken.
* Track quiz history.

### Homepage

* Browse available quizzes by subject/topic.
* Search quizzes.
* Join quizzes with one click.
---

## User Workflow

1. Register or log in to access your personal dashboard.
2. Create a new quiz by entering a subject.
3. Add questions using the supported types.
4. Publish and share your quiz with Code.
5. Other users take the quiz and get scored instantly.
6. View your results and time spent on each quiz.

---

## Folder Structure

```
JeniusMinds/
├── app/
│   └── Http/
│       └── Controllers/
│           ├── AuthController.php
│           ├── Controller.php      
│           ├── HomepageController.php
│           ├── ProfileController.php
│           └── QuizController.php
│       └── Middleware/
│           └── Authenticate.php
│       └── Quiz/
│           ├── Choice.php
│           ├── Question.php
│           ├── QuestionType.php
│           ├── QuizAttempt.php
│           ├── Subject.php
│           └── Topic.php
│       └── User.php
│      
├── database/
│       └── factories/
│           └── UserFactory.php
│       └── migrations/
│           ├── 0001_01_01_000000_create_users_table.php
│           ├── 2025_07_07_173133_create_quiz_table.php
│           └── 2025_07_20_202431_create_quiz_attempts_table.php
│       └── seeders/
│           ├── DatabaseSeeder.php
│           └── QuizSeeder.php
│
├── public/
│       └── assets/
│           ├── biology.png
│           ├── ...              
│
├── resources/
│   └── views/
│       ├── auth/
│       │   ├── signin.blade.php
│       │   └── signup.blade.php
│       ├── homepage/
│       │   └── index.blade.php
│       ├── layouts/
│       │   ├── app.blade.php
│       │   ├── quiz.blade.php
│       │   └── quizeditor.blade.php
│       ├── profile/
│       │   ├── editprofile.blade.php
│       │   ├── myprofile.blade.php
│       │   └── review.blade.php
│       ├── quiz/
│       │   └── partials/
│       │       ├── _button.blade.php
│       │       ├── _checkbox.blade.php
│       │       ├── _reorder.blade.php
│       │       └── _typeanswer.blade.php
│       │   ├── addbutton.blade.php
│       │   ├── addcheckbox.blade.php
│       │   ├── addreorder.blade.php
│       │   ├── addtypeanswer.blade.php
│       │   ├── editor.blade.php
│       │   └── show.blade.php            
│
└── routes/
    └── web.php                          
```

---

## Getting Started

### Requirements

Ensure your development environment includes:

* PHP >= 8.2
* Composer
* Node.js + npm
* MySQL
* Git

### Installation

1.  Clone the repository
    ```bash
    git clone https://github.com/n0thing19/JeniusMinds
    cd JeniusMinds
    ```

2.  Install PHP dependencies
    ```bash
    composer install
    ```
    
3. Install Node Dependencies
   ```bash
   npm install
   ```
   
4.  Set up environment variables
    ```bash
    copy .env.example .env
    ```

5.  Generate the application key
    ```bash
    php artisan key:generate
    ```

6.  Run migrations
    ```bash
    php artisan migrate
    ```

7.  Run seeder
    ```bash
    php artisan db:seed --class=QuizSeeder
    ```

8.  Start development server
    ```bash
    composer run dev
    ```

Visit the app at: `http://localhost:8000`

---

## Database Structure

| Table          | Description                                          |
| -------------- | ---------------------------------------------------- |
| `users`          | Stores user account information                      |
| `subjects`       | Quiz subject categories (e.g., Math, English)        |
| `topics`         | Individual quizzes, linked to users and subjects     |
| `question_types` | Defines available question formats                   |
| `questions`      | Stores question content, type, and related topic     |
| `choices`        | Options for each question, with correct answer flags |
| `quiz_attempts`  | Store completed user quiz history                    |

---

## License

This project is open-sourced under the [MIT License](LICENSE). You are free to use, modify, and distribute it for personal or commercial use.
