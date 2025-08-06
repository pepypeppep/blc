# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Guidelines for YOU

1. ALWAYS read PLAN.md first, focusing on:

-   Completed milestones
-   Current priorities
-   Pending tasks
-   Any blockers or challenges

2. Summarize key insights relevant to the current task.
3. Confirm your understanding to human before taking any action or writing code. The human will review `PLAN.md` first.

After completing some task in `PLAN.md`, update `PLAN.md` to reflect:

-   check completed task
-   Changes made
-   New progress
-   Remaining work
-   Updated timelines (if applicable)

## Command for you

if human ask you with following command, please do it

-   'list task' : list unfinished tasks
-   'update claude' : if your knowledge about project is updated, update CLAUDE.md

## Project Overview

This is a comprehensive Learning Management System (LMS) built with Laravel 11, featuring modular architecture and multi-role support (Admin, Instructor, Student, Mentor, Mentee).

## Architecture

### Core Structure

-   **Framework**: Laravel 11.x
-   **Architecture**: Modular using `nwidart/laravel-modules`
-   **Database**: MySQL with Eloquent ORM
-   **Frontend**: Blade templates with Alpine.js and Livewire components
-   **Authentication**: Laravel Sanctum for API tokens
-   **Queue**: Redis for background jobs
-   **Cache**: Redis for performance optimization

### Directory Structure

```
lms/
├── app/                    # Core Laravel application
├── Modules/               # Feature modules
├── resources/             # Views, assets, lang files
├── database/              # Migrations, seeders, factories
├── routes/               # Web and API routes
└── storage/              # Logs, cache, uploads
```

## Active Modules

### Core Modules

1. **Course** - Course management, categories, levels
2. **CertificateBuilder** - Certificate creation and layout
3. **CertificateRecognition** - Certificate validation
4. **Coaching** - Coaching program management
5. **Mentoring** - Mentorship program (Mentor-Mentee)
6. **InstructorEvaluation** - Instructor rating system
7. **BasicPayment** - Payment processing
8. **UserManagement** - User roles and permissions

### Module Structure

Each module follows this pattern:

```
Modules/[ModuleName]/
├── app/
│   ├── Http/Controllers/
│   ├── Models/
│   ├── Services/
│   └── Providers/
├── database/
│   ├── migrations/
│   └── seeders/
├── resources/
│   ├── views/
│   └── lang/
├── routes/
│   └── web.php
└── module.json
```

## Database Schema

### Key Tables

-   **users**: Core user information
-   **courses**: Course management
-   **mentoring_programs**: Mentorship programs
-   **mentoring_sessions**: Individual sessions
-   **certificates**: Certificate records
-   **payments**: Payment transactions
-   **evaluations**: Instructor evaluations

### Relationships

-   User → Courses (instructor/student)
-   User → MentoringPrograms (mentor/mentee)
-   Course → Certificates
-   MentoringProgram → Sessions
-   User → Evaluations

## Development Guidelines

### Code Standards

-   **PSR-12** coding standards
-   **Laravel best practices** for controllers, models, and views
-   **Type hints** for all method parameters and return types
-   **Docblocks** for all classes and methods
-   **Repository pattern** for database operations

### Security

-   **Role-based access control** using Laravel Gates and Policies
-   **Input validation** using Form Requests
-   **SQL injection prevention** via Eloquent ORM
-   **XSS protection** through Blade templating
-   **CSRF protection** via Laravel tokens

### Testing

-   **Feature tests** for all endpoints
-   **Unit tests** for service classes
-   **Browser tests** for critical user flows
-   **Database seeders** for consistent test data

## Common Commands

```bash
# Module management
php artisan module:list
php artisan module:make [ModuleName]

# Database operations
php artisan migrate:fresh --seed
php artisan module:seed [ModuleName]

# Development
php artisan serve
npm run dev
php artisan queue:work

# Testing
php artisan test
php artisan test --filter [TestName]
```

## Environment Setup

### Required Extensions

-   PHP 8.2+
-   MySQL 8.0+
-   Redis
-   Composer
-   Node.js & NPM

### Environment Variables

```env
APP_NAME="LMS Platform"
APP_ENV=local
APP_KEY=
APP_DEBUG=true

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=lms
DB_USERNAME=root
DB_PASSWORD=

REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379
```

## Frontend Stack

### Technologies

-   **Blade Templates** - Laravel's templating engine
-   **Alpine.js** - Lightweight JavaScript framework
-   **Livewire** - Full-stack framework for dynamic interfaces
-   **Tailwind CSS** - Utility-first CSS framework
-   **Laravel Mix** - Asset compilation

### Component Structure

```
resources/views/
├── layouts/
│   ├── app.blade.php
│   └── guest.blade.php
├── components/
│   ├── alerts.blade.php
│   ├── modals.blade.php
│   └── forms/
└── dashboard/
    ├── admin/
    ├── instructor/
    └── student/
```

## API Structure

### Authentication

-   **Sanctum tokens** for API authentication
-   **Rate limiting** to prevent abuse
-   **CORS** configuration for cross-origin requests

### Response Format

```json
{
    "success": true,
    "message": "Operation completed successfully",
    "data": {...},
    "errors": []
}
```

### Error Handling

-   **Consistent error format** across all endpoints
-   **HTTP status codes** following REST conventions
-   **Validation errors** with field-specific messages
-   **Logging** for debugging and monitoring

## Deployment

### Production Checklist

-   [ ] Environment variables configured
-   [ ] Database migrations run
-   [ ] Cache and config cleared
-   [ ] Storage linked
-   [ ] SSL certificate installed
-   [ ] Queue workers configured
-   [ ] Monitoring enabled

### Server Requirements

-   **Web Server**: Nginx or Apache
-   **PHP**: 8.2+ with required extensions
-   **Database**: MySQL 8.0+ or PostgreSQL
-   **Cache**: Redis
-   **Queue**: Redis or Database
-   **SSL**: Let's Encrypt or commercial certificate

## Troubleshooting

### Common Issues

1. **Permission errors**: Check storage and bootstrap/cache permissions
2. **Queue not processing**: Verify Redis connection and queue workers
3. **Session issues**: Check session driver configuration
4. **Email not sending**: Verify mail driver settings
5. **Module not found**: Run `composer dump-autoload`

### Debug Commands

```bash
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear
php artisan optimize:clear
```

## Support

For technical issues:

1. Check Laravel logs in `storage/logs/`
2. Review module-specific documentation
3. Verify database migrations
4. Check environment configuration
5. Test with fresh database seed

## Project Architecture

This is a large, modular Laravel 11 application built using the `nwidart/laravel-modules` package. The application is
divided into a core `app` directory and numerous feature-specific modules located in the `Modules/` directory. Each
module is a self-contained unit with its own routes, controllers, models, and views, promoting a clean separation of
concerns.

Key modules include:

-   **`CertificateBuilder`**: Manages the creation and layout of certificates.
-   **`CertificateRecognition`**: Handles certificate validation and recognition.
-   **`Course`**: Core module for managing courses, categories, and levels.
-   **`Coaching`**: Module for managing coaching functionalities.
-   **`Mentoring`**: Facilitates a mentoring program between Mentees and Mentors. Mentees can create and manage their
    mentoring progress, while Mentors can review, approve, and evaluate them. Admins have oversight of all mentoring
    activities.
-   **`InstructorEvaluation`**: Handles instructor evaluation and rating functionalities.
-   **`BasicPayment`**: Handles payment processing and integration with various gateways.
-   **`User` Management**: Core user management is in `app/`, with additional customer-related logic in the `Customer`
    module.

### Mentoring Module Architecture

The `Mentoring` module is designed to manage the entire lifecycle of a mentoring relationship between a mentee and a
mentor.

**Directory Structure:**

```
Modules/Mentoring/
├── app/
│ ├── Http/Controllers/
│ │ ├── MenteeController.php # Handles actions initiated by the mentee.
│ │ ├── MentorController.php # Handles actions initiated by the mentor.
│ │ └── MentoringController.php # Handles actions for the admin view.
│ └── Models/
│ ├── Mentoring.php # Core model representing the mentoring relationship.
│ ├── MentoringReview.php # Model for mentor's review of the mentoring.
│ └── MentoringSession.php # Model for individual mentoring sessions.
├── database/
│ └── migrations/ # Database schema for the mentoring tables.
├── resources/
│ └── views/ # Blade templates for the module.
└── routes/
├── api.php # API routes for the module.
└── web.php # Web routes for mentee, mentor, and admin panels.
```

**Workflow:**

1. **Initiation (Mentee)**: A mentee initiates a mentoring request, which creates a `Mentoring` record with a `draft`
   status. The mentee adds `MentoringSession` entries to document their activities.
2. **Submission (Mentee)**: Once the mentee completes their documentation, they submit it for approval. The `Mentoring`
   status changes to `submitted`.
3. **Review (Mentor)**: The mentor reviews the submitted `Mentoring` and its associated `MentoringSession`s. The mentor
   can `approve` or `reject` the submission.
4. **Evaluation (Mentor)**: After approval, the mentor provides a final evaluation by creating a `MentoringReview`
   record.
5. **Oversight (Admin)**: An administrator can monitor all mentoring activities from a dedicated panel.

### Mentoring Module Database Schema

The database schema for the Mentoring module consists of three tables: `mentoring`, `mentoring_sessions`, and `mentoring_reviews`.

**`mentoring` table**

This table stores the core information about the mentoring relationship.

| Column                       | Type                     | Description                                                                                                 |
| ---------------------------- | ------------------------ | ----------------------------------------------------------------------------------------------------------- |
| `id`                         | `bigint`, auto-increment | Primary key.                                                                                                |
| `title`                      | `string`                 | The title of the mentoring program.                                                                         |
| `description`                | `text`, nullable         | A description of the mentoring program.                                                                     |
| `purpose`                    | `text`, nullable         | The purpose of the mentoring program.                                                                       |
| `total_session`              | `integer`                | The total number of mentoring sessions.                                                                     |
| `mentor_availability_letter` | `string`                 | Path to the mentor's availability letter.                                                                   |
| `final_report`               | `string`, nullable       | Path to the final report.                                                                                   |
| `mentor_id`                  | `bigint`, unsigned       | Foreign key to the `users` table (mentor).                                                                  |
| `mentee_id`                  | `bigint`, unsigned       | Foreign key to the `users` table (mentee).                                                                  |
| `status`                     | `string`                 | The status of the mentoring program (e.g., `Draft`, `Submitted`, `Approved`, `Rejected`). Default: `Draft`. |
| `reason`                     | `string`, nullable       | The reason for rejection.                                                                                   |
| `created_at`                 | `timestamp`              | Timestamp of creation.                                                                                      |
| `updated_at`                 | `timestamp`              | Timestamp of last update.                                                                                   |

**`mentoring_sessions` table**

This table stores information about each individual mentoring session.

| Column                   | Type                     | Description                                                                   |
| ------------------------ | ------------------------ | ----------------------------------------------------------------------------- |
| `id`                     | `bigint`, auto-increment | Primary key.                                                                  |
| `activity`               | `string`                 | The activity performed during the session.                                    |
| `description`            | `text`, nullable         | A description of the session.                                                 |
| `image`                  | `string`, nullable       | Path to an image related to the session.                                      |
| `mentoring_date`         | `dateTime`               | The date and time of the session.                                             |
| `mentoring_note`         | `text`                   | Notes from the mentoring session.                                             |
| `mentoring_instructions` | `text`                   | Instructions for the mentoring session.                                       |
| `mentoring_id`           | `bigint`, unsigned       | Foreign key to the `mentoring` table.                                         |
| `status`                 | `string`                 | The status of the session (e.g., `pending`, `completed`). Default: `pending`. |
| `created_at`             | `timestamp`              | Timestamp of creation.                                                        |
| `updated_at`             | `timestamp`              | Timestamp of last update.                                                     |

**`mentoring_reviews` table**

This table stores the mentor's review of the mentoring program.

| Column                         | Type                     | Description                                         |
| ------------------------------ | ------------------------ | --------------------------------------------------- |
| `id`                           | `bigint`, auto-increment | Primary key.                                        |
| `is_use_planned_session`       | `boolean`                | Whether the planned session was used. Default: `0`. |
| `planned_session_changed`      | `text`, nullable         | Description of changes to the planned session.      |
| `is_target`                    | `boolean`                | Whether the target was achieved. Default: `0`.      |
| `target_description`           | `text`, nullable         | Description of the target achievement.              |
| `discipline`                   | `integer`                | Rating for discipline. Default: `0`.                |
| `discipline_description`       | `text`, nullable         | Description of the discipline rating.               |
| `teamwork`                     | `integer`                | Rating for teamwork. Default: `0`.                  |
| `teamwork_description`         | `text`, nullable         | Description of the teamwork rating.                 |
| `initiative`                   | `integer`                | Rating for initiative. Default: `0`.                |
| `initiative_description`       | `text`, nullable         | Description of the initiative rating.               |
| `material_mastery`             | `integer`                | Rating for material mastery. Default: `0`.          |
| `material_mastery_description` | `text`, nullable         | Description of the material mastery rating.         |
| `mentoring_id`                 | `bigint`, unsigned       | Foreign key to the `mentoring` table.               |
| `created_at`                   | `timestamp`              | Timestamp of creation.                              |
| `updated_at`                   | `timestamp`              | Timestamp of last update.                           |

**Foreign Key Relationships**

-   The `mentoring` table's `mentor_id` and `mentee_id` columns are foreign keys to the `id` column on the `users` table.
-   The `mentoring_sessions` table's `mentoring_id` column is a foreign key to the `id` column on the `mentoring` table.
-   The `mentoring_reviews` table's `mentoring_id` column is a foreign key to the `id` column on the `mentoring` table.

**Model Relationships**

-   **`Mentoring` model:**
    -   `mentor()`: Defines a `belongsTo` relationship with the `User` model (mentor).
    -   `mentee()`: Defines a `belongsTo` relationship with the `User` model (mentee).
    -   `mentoringSessions()`: Defines a `hasMany` relationship with the `MentoringSession` model.
-   **`MentoringSession` model:**
    -   `mentoring()`: Defines a `belongsTo` relationship with the `Mentoring` model.
-   **`MentoringReview` model:**
    -   `mentoring()`: Defines a `belongsTo` relationship with the `Mentoring` model.

**Routing**

The Mentoring module has the following routes:

-   **Web Routes (`routes/web.php`):**
    -   **Mentee Routes (prefix: `/student/mentee`, as: `student.mentee.`):**
        -   `GET /`: `MenteeController@index` (view all mentorings)
        -   `GET /create`: `MenteeController@create` (show create form)
        -   `POST /`: `MenteeController@store` (store new mentoring)
        -   `PUT /session/update`: `MenteeController@updateSession` (update mentoring session)
        -   `PUT /{mentoring}/report`: `MenteeController@updateFinalReport` (update final report)
        -   `GET /{id}`: `MenteeController@show` (show mentoring details)
        -   `GET /{id}/document/{type}`: `MenteeController@showDocument` (view document)
        -   `GET /{id}/img`: `MenteeController@viewImage` (view image)
        -   `PUT /{id}/submit`: `MenteeController@submitForApproval` (submit for approval)
    -   **Mentor Routes (prefix: `/student/mentor`, as: `student.mentor.`):**
        -   `GET /`: `MentorController@index` (view all mentorings)
        -   `GET /{id}/show`: `MentorController@show` (show mentoring details)
        -   `POST /{id}/approve`: `MentorController@approve` (approve mentoring)
        -   `POST /{id}/reject`: `MentorController@reject` (reject mentoring)
        -   `POST /{id}/review`: `MentorController@review` (review mentoring)
        -   `GET /{id}/evaluasi`: `MentorController@evaluasi` (show evaluation form)
        -   `POST /{id}/evaluasi`: `MentorController@evaluasiStore` (store evaluation)
        -   `POST /{id}/kirim-evaluasi`: `MentorController@kirimEvaluasi` (send evaluation)
        -   `PUT /session/update`: `MentorController@updateSession` (update mentoring session)
    -   **Admin Routes (prefix: `/admin/mentoring`, as: `admin.mentoring.`):**
        -   `GET /`: `MentoringController@index` (view all mentorings)
        -   `GET /{id}/show`: `MentoringController@show` (show mentoring details)
        -   `GET /{id}/img`: `MentoringController@viewImage` (view image)
        -   `GET /{id}/document/{type}`: `MentoringController@showDocument` (view document)
-   **API Routes (`routes/api.php`):**
    -   **Authenticated API Routes (prefix: `/api/mentoring`, as: `api.mentoring.`):**
        -   `GET /show/document/{id}/{type}`: `MentoringController@showDocument` (show document)
        -   `GET /show/document/session/{id}/{type}`: `MentoringController@showDocumentSession` (show document session)

# Course Module Documentation

## Overview

The Course module is a core component of the LMS system built on Laravel 11, providing comprehensive course management functionality including creation, editing, categorization, and certification features.

## Architecture

-   **Framework**: Laravel 11
-   **Pattern**: Modular architecture using `nwidart/laravel-modules`
-   **Location**: `/Modules/Course/`
-   **Type**: Feature module with self-contained MVC structure

## Module Structure

```
Modules/Course/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── CourseController.php          # Main course controller
│   │   │   ├── CourseCategoryController.php  # Category management
│   │   │   ├── CourseSubCategoryController.php
│   │   │   ├── CourseContentController.php   # Content management
│   │   │   ├── CourseReviewController.php
│   │   │   ├── CourseLevelController.php
│   │   │   ├── CourseLanguageController.php
│   │   │   ├── CourseTosController.php
│   │   │   ├── CourseVerificationController.php
│   │   │   └── CourseDeleteRequestController.php
│   │   └── Requests/
│   ├── Models/
│   ├── Services/
│   └── Providers/
├── database/
│   ├── migrations/
│   └── seeders/
├── resources/
│   ├── views/
│   │   ├── course/
│   │   │   ├── index.blade.php
│   │   │   ├── create.blade.php
│   │   │   ├── edit.blade.php
│   │   │   ├── more-information.blade.php  # Step 2 - Certificate selection
│   │   │   ├── navigation.blade.php
│   │   │   └── components/
│   │   └── layouts/
├── routes/
│   ├── web.php                             # All web routes
│   └── api.php
├── lang/                                   # Translation files
├── composer.json                           # Module dependencies
├── module.json                            # Module configuration
└── package.json                           # Frontend dependencies
```

## Core Models

### Course Model

-   **Table**: `courses`
-   **Key Fields**:
    -   `id`, `title`, `slug`, `description`
    -   `category_id`, `sub_category_id`
    -   `instructor_id`, `price`, `discount_price`
    -   `certificate_id` (foreign key to CertificateBuilder)
    -   `status`, `is_approved`, `published_at`
    -   `capacity`, `from_date`, `to_date`
    -   `output`, `outcome`, `learning_method`

### Related Models

-   **CourseCategory**: Course categorization
-   **CourseSubCategory**: Subcategory management
-   **CourseLevel**: Course difficulty levels
-   **CourseLanguage**: Available languages
-   **CourseReview**: Student reviews and ratings
-   **CourseContent**: Course materials and structure

## Route Structure

### Admin Routes (Web)

```php
// Main course management
Route::resource('course', CourseController::class);

// Category management
Route::resource('course-category', CourseCategoryController::class);
Route::resource('course-sub-category', CourseSubCategoryController::class);

// Course creation wizard
Route::get('courses/create/{id}/step/{step}', [CourseController::class, 'createStep']);
Route::post('courses/store-step', [CourseController::class, 'storeStep']);

// Certificate selection (Step 2)
Route::get('courses/create/{id}/step/2', [CourseController::class, 'moreInformation']);

// Course verification
Route::get('course-verification/{id}', [CourseVerificationController::class, 'index']);
```

## Course Creation Process

### Multi-Step Wizard

1. **Step 1**: Basic Information

    - Course title, description, category
    - Pricing, capacity, dates

2. **Step 2**: More Information (Certificate Selection)

    - Certificate selection via modal
    - Course levels, languages
    - Partner instructors
    - Learning outcomes

3. **Step 3**: Content & Curriculum

    - Course chapters and lessons
    - Upload materials
    - Quiz integration

4. **Step 4**: Settings & Publishing
    - Final settings
    - Review and publish

### Certificate Selection Implementation

#### Backend (CourseController)

```php
// In createStep() method for step 2
$certificates = CertificateBuilder::get();
return view('course::course.more-information', compact(
    'categories',
    'courseId',
    'course',
    'levels',
    'category',
    'languages',
    'certificates'  // Available certificates
));
```

#### Frontend (more-information.blade.php)

-   **Modal Interface**: Certificate selection through Bootstrap modal
-   **Visual Selection**: Thumbnail previews of certificates
-   **Interactive**: JavaScript functions for selection
-   **Validation**: Required field with validation messages

#### Key Components

-   **Certificate Modal**: Grid layout (4 columns) displaying certificate thumbnails
-   **Selection Logic**: `chooseCertificate(id)` function updates hidden input
-   **Preview Display**: Selected certificate image shown in `#certificateBg`
-   **Data Storage**: Certificate ID saved to `courses.certificate_id`

## Validation Rules

### Course Validation

```php
// Certificate validation in CourseController
'certificate' => ['required', 'exists:certificate_builders,id'],
'certificate.required' => __('Certificate is required'),
'certificate.exists' => __('Certificate does not exist'),
```

## Frontend Features

### JavaScript Integration

-   **Certificate Selection**: Interactive modal with visual previews
-   **Form Validation**: Real-time validation feedback
-   **AJAX Integration**: Dynamic content loading
-   **Responsive Design**: Mobile-friendly interface

### UI Components

-   **Bootstrap Modals**: Certificate selection interface
-   **Select2**: Enhanced dropdown for categories and instructors
-   **Summernote**: Rich text editor for descriptions
-   **Date Pickers**: For course scheduling

## Database Relationships

### Course Model Relationships

```php
// Course.php relationships
public function category()
{
    return $this->belongsTo(CourseCategory::class);
}

public function certificate()
{
    return $this->belongsTo(CertificateBuilder::class, 'certificate_id');
}

public function instructor()
{
    return $this->belongsTo(User::class, 'instructor_id');
}

public function partnerInstructors()
{
    return $this->hasMany(CoursePartnerInstructor::class);
}
```

## API Endpoints

### Course Management API

```php
// Course listing with filtering
GET /api/courses
GET /api/courses/{id}
POST /api/courses
PUT /api/courses/{id}
DELETE /api/courses/{id}

// Certificate-related endpoints
GET /api/certificates
GET /api/certificates/{id}/background
```

## Integration Points

### Certificate Builder Integration

-   **Model**: CertificateBuilder from CertificateBuilder module
-   **Routes**: Uses `admin.certificate-builder.getBg` for certificate images
-   **Validation**: Integrates with certificate existence validation

### User Management Integration

-   **Instructors**: Links to User model for course instructors
-   **Students**: Enrollment management through pivot tables
-   **Partner Instructors**: Multiple instructor support

## Configuration

### Module Configuration (module.json)

```json
{
    "name": "Course",
    "alias": "course",
    "description": "Course management module",
    "keywords": ["course", "learning", "education"],
    "version": "1.0.0",
    "order": 1,
    "providers": ["Modules\\Course\\app\\Providers\\CourseServiceProvider"],
    "aliases": {},
    "files": [],
    "requires": []
}
```

## Usage Examples

### Creating a Course with Certificate

```php
// Controller example
$course = Course::create([
    'title' => 'Advanced Laravel Development',
    'description' => 'Comprehensive Laravel course',
    'category_id' => 1,
    'certificate_id' => 5,  // Selected certificate
    'instructor_id' => 10,
    'price' => 99.99,
    'status' => 'active'
]);
```

### Certificate Selection in View

```blade
{{-- Certificate selection button --}}
<button type="button" class="btn btn-primary"
        data-toggle="modal" data-target="#certificateModal">
    {{ __('Choose Certificate') }}
</button>

{{-- Hidden input for selected certificate --}}
<input type="hidden" name="certificate"
       value="{{ $course?->certificate_id }}">
```

# Course Module Certificate Selection Analysis

## Overview

This document analyzes the certificate selection form implementation in the Course module, specifically for step 2 of course creation (`admin/courses/create/{id}/step/2`).

## File Structure

### Controller Implementation

**File**: `Modules/Course/app/Http/Controllers/CourseController.php`

**Key Components**:

-   **Certificate Data Retrieval**: Line 182 uses `CertificateBuilder::get()` to fetch all available certificates
-   **View Data**: Passes `certificates` to the view alongside other course data
-   **Validation**: Lines 288-311 include validation rules for certificate selection
-   **Database Storage**: Lines 363-364 store the selected certificate ID in the course record

### View Implementation

**File**: `Modules/Course/resources/views/course/more-information.blade.php`

**Certificate Selection UI Components**:

#### 1. Form Field Structure

```html
<div class="col-md-3 my-4">
    <div class="form-group">
        <label for="level">{{ __('Certificate') }} <code>*</code></label>
        <div>
            <div id="certificateBg"></div>
            <input
                type="hidden"
                name="certificate"
                value="{{ $course?->certificate_id }}"
                class="form-control"
            />
            <button
                type="button"
                class="btn btn-primary mt-3"
                data-toggle="modal"
                data-target="#certificateModal"
            >
                {{ __('Choose Certificate') }}
            </button>
        </div>
    </div>
</div>
```

#### 2. Certificate Modal

```html
<div
    class="modal fade"
    id="certificateModal"
    data-backdrop="static"
    data-keyboard="false"
>
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ __('Choose Certificate') }}</h5>
            </div>
            <div class="modal-body row">
                <div class="col">
                    <div class="row">
                        @foreach ($certificates as $certificate)
                        <div class="col-md-3 mb-3">
                            <div class="card">
                                <img
                                    src="{{ route('admin.certificate-builder.getBg', $certificate->id) }}"
                                    alt="{{ $certificate->name }}"
                                />
                                <div class="card-body">
                                    <button
                                        class="btn btn-primary"
                                        onclick="chooseCertificate({{ $certificate->id }})"
                                    >
                                        {{ __('Choose') }}
                                    </button>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button
                    type="button"
                    class="btn btn-secondary"
                    data-dismiss="modal"
                >
                    {{ __('Close') }}
                </button>
                <button
                    type="button"
                    class="btn btn-primary"
                    onclick="chooseCertificateSave()"
                >
                    {{ __('Save') }}
                </button>
            </div>
        </div>
    </div>
</div>
```

#### 3. JavaScript Functions

```javascript
// Certificate selection logic
function chooseCertificate(id) {
    $('input[name="certificate"]').val(id);
    $('#certificateBg').html(
        '<img src="{{ route('admin.certificate-builder.getBg', ':id') }}"
             alt="" style="width: 100%; height: auto;" />'
        .replace(':id', id)
    );
}

function chooseCertificateSave() {
    $('#certificateModal').modal('hide');
}

// Display existing certificate on page load
@if ($course?->certificate_id != null)
    $('#certificateBg').html(
        '<img src="{{ route('admin.certificate-builder.getBg', $course->certificate_id) }}"
             alt="" style="width: 100%; height: auto;" />'
    );
@endif
```

## Data Flow

1. **Controller**: Fetches certificates using `CertificateBuilder::get()`
2. **View**: Displays certificates in a modal grid layout
3. **Selection**: User clicks certificate → JavaScript updates hidden field
4. **Preview**: Selected certificate image displays in `#certificateBg`
5. **Submission**: Certificate ID submitted with form data
6. **Storage**: Certificate ID saved to `courses.certificate_id` field

## Dependencies

-   **CertificateBuilder Model**: From `Modules\CertificateBuilder\app\Models\CertificateBuilder`
-   **Route**: `admin.certificate-builder.getBg` for certificate background images
-   **Frontend**: Bootstrap modal, jQuery, custom JavaScript functions

## Key Features

-   **Visual Selection**: Thumbnail images of certificates
-   **Modal Interface**: Clean popup for certificate selection
-   **Preview Display**: Shows selected certificate image
-   **Validation**: Required field validation
-   **Responsive Grid**: 4-column layout in modal (col-md-3)

## Implementation Notes

-   Uses Laravel's route helper for certificate image URLs
-   Follows modular architecture pattern
-   Integrates with existing form validation
-   Provides clear visual feedback for selection
-   Supports both new selection and existing value display

### Certificate Generation and Signing Workflow

The certificate signing process is handled via an external service called "Bantara".

1. **Request Initiation**: The process starts in `app/Http/Controllers/Frontend/StudentDashboardController.php` within
   the `requestSignCertificate` method.
2. **PDF Generation**: This method generates a two-page PDF from Blade views
   (`frontend.student-dashboard.certificate.index` and `frontend.student-dashboard.certificate.summary`).
3. **API Call**: The generated PDF is sent to the Bantara API for digital signing. The signers are retrieved from the
   `course_signers` table and sent along with the request.
4. **Callback**: Bantara calls a webhook, `api/bantara-callback/{enrollmentID}`, which is handled by the
   `bantaraCallback` method in `app/Http/Controllers/Api/CertificateApiController.php`.
5. **Storing Certificate**: The callback method receives the signed PDF and stores it in the `private` storage disk. The
   path is saved in the `certificate_path` column of the `enrollments` table.

## Best Practices

### Security

-   **Authorization**: Role-based access control
-   **Validation**: Comprehensive input validation
-   **CSRF Protection**: Built-in Laravel CSRF tokens
-   **SQL Injection**: Eloquent ORM parameter binding

### Performance

-   **Eager Loading**: Prevents N+1 queries
-   **Pagination**: Large dataset handling
-   **Caching**: Certificate data caching
-   **Indexing**: Database indexes on foreign keys

### Maintainability

-   **Modular Code**: Separation of concerns
-   **Consistent Naming**: Laravel naming conventions
-   **Documentation**: Comprehensive code comments
-   **Testing**: Unit and feature tests included

## Troubleshooting

### Common Issues

1. **Certificate Images Not Loading**: Check `certificate-builder.getBg` route
2. **Validation Errors**: Ensure certificate exists in database
3. **Modal Not Opening**: Check Bootstrap and jQuery dependencies
4. **Data Not Saving**: Verify form field names match validation rules

### Debug Commands

```bash
# Check course data
php artisan tinker
>>> $course = Course::find(1);
>>> $course->certificate;

# Check available certificates
>>> CertificateBuilder::all();
```

## Future Enhancements

### Planned Features

-   **Advanced Filtering**: Multi-criteria course search
-   **Bulk Operations**: Batch course updates
-   **Analytics Dashboard**: Course performance metrics
-   **Mobile App API**: RESTful API for mobile applications
-   **AI Integration**: Smart course recommendations

## Common Commands

### Development Environment

To run the local development environment, which includes the PHP server, queue worker, log tailing, and Vite development
server, use the following command from `composer.json`:

```bash
composer run dev
```

This concurrently executes `php artisan serve`, `php artisan queue:listen`, `php artisan pail`, and `npm run dev`.

### Frontend Assets

-   To run the Vite development server: `npm run dev`
-   To build frontend assets for production: `npm run build`

### Testing and Linting

-   **Testing**: This project uses PHPUnit. Run the test suite with:

```bash
./vendor/bin/phpunit
```

-   **Linting**: This project uses Laravel Pint for code style. Check for issues with:

```bash
./vendor/bin/pint --test
```

And fix them with:

```bash
./vendor/bin/pint
```

### Database Seeding

To reset the database and run all seeders, use:

```bash
php artisan migrate:fresh --seed
```

The `README.md` also specifies additional custom commands for data synchronization:

```bash
php artisan unor:sync
php artisan instansi:sync
php artisan user:sync {instansi_id}
```
