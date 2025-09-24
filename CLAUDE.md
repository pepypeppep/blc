# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

This is a Laravel-based Learning Management System (LMS) called "SkillGro" with modular architecture using nwidart/laravel-modules. The system supports multiple user roles (Admin, Instructor, Student) with features like course management, payments, certifications, and live classes.

## Tech Stack

### Core Framework & Runtime Environment
- **Laravel Version**: 11.31
- **PHP Version**: 8.2+ 
- **Database**: MariaDB/MySQL
- **Web Server**: Nginx PHP 8.2-FPM

### Frontend Stack
- **Build Tool**: Vite 6.0 (Modern build system with hot reload)
- **CSS Framework**: Tailwind CSS 3.4.13
- **JavaScript**: Vanilla JS with Axios 1.7.4 for API calls
- **Asset Processing**: Autoprefixer 10.4.20, PostCSS 8.4.47

### Modular Architecture
- **Module System**: nwidart/laravel-modules v10.0
- **Module Structure**: Each module has independent routes, controllers, models, and views

### Authentication & Security
- **SSO Integration**: Keycloak (Indonesian Government SSO)
- **Multi-Guard**: Web, Admin, and SSO-API guards
- **Authorization**: Spatie Laravel Permission v6.1
- **Security**: HTML Purifier, CORS protection, encrypted sessions


## URL Structure and Routes

### Route Architecture
The application uses **nwidart/laravel-modules** with 74 route files across 30+ modules. Routes are organized by user role and functionality with clear URL prefixes.

### Core URL Patterns

#### **Frontend Public Routes** (No prefix)
```
/                           # Homepage
/courses                    # Course listing
/course/{slug}             # Course detail
/blog                       # Blog listing
/blog/{slug}               # Blog detail
/article                    # Article listing
/article/{slug}            # Article detail
/about-us                   # About page
/contact                    # Contact page
/faqs                       # FAQ page
/page/{slug}               # Custom pages
```

#### **Student Dashboard Routes** (`/student/`)
```
/student/dashboard                   # Student dashboard
/student/certificates               # Student certificates
/student/learning/{slug}            # Course learning interface
/student/enrolled-courses           # Enrolled courses
/student/quiz-attempts              # Quiz history
/student/continuing-education       # Continuing education
/student/pengetahuan                # Knowledge management
/student/follow-up-action           # RTL (Rencana Tindak Lanjut)
/student/reviews                    # Student reviews
```

#### **Instructor Dashboard Routes** (`/instructor/`)
```
/instructor/dashboard                # Instructor dashboard
/instructor/courses                  # Course management
/instructor/payout                   # Payment withdrawal
/instructor/announcements           # Announcements
/instructor/my-sells                 # Sales reports
/instructor/lesson-question          # Q&A management
```

#### **Admin Panel Routes** (`/admin/`)
```
/admin/dashboard                     # Admin dashboard
/admin/login                         # Admin login
/admin/logout                        # Admin logout
/admin/course                        # Course management
/admin/orders                        # Order management
/admin/settings                      # System settings
/admin/role                          # Role management
/admin/admin                         # Admin user management
```

#### **API Routes** (`/api/`)
```
/api/home                           # API homepage
/api/courses                        # Course APIs
/api/articles                       # Article APIs
/api/student-learning               # Student learning APIs
/api/student-quiz                   # Quiz APIs
/api/mentoring                      # Mentoring APIs
/api/coaching                       # Coaching APIs
/api/notifications                 # Notification APIs
```

### Module-Specific Route Patterns

#### **Learning Management Modules**
- **Course Module**: `/admin/course/`, `/api/course/`
- **CertificateBuilder**: `/admin/certificate/`, `/api/certificate/`
- **CertificateRecognition**: `/admin/certificate-recognition/`

#### **Professional Development Modules**
- **Coaching**: `/student/coachee/`, `/student/coach/`, `/admin/coaching/`
- **Mentoring**: `/student/mentee/`, `/student/mentor/`, `/admin/mentoring/`
- **InstructorEvaluation**: `/admin/instructor-evaluation/`

#### **Commerce & Payment Modules**
- **Order**: `/admin/orders/`
- **BasicPayment**: `/admin/payment/`, `/api/payment/`
- **Currency**: `/admin/currency/`
- **Coupon**: `/admin/coupon/`
- **Refund**: `/admin/refund/`
- **PaymentWithdraw**: `/admin/withdraw/`

### Authentication & Middleware

#### **Authentication Guards**
- **`web`** - Standard web authentication
- **`admin`** - Admin panel authentication
- **`auth:sso-api`** - SSO API authentication
- **`auth:sanctum`** - Laravel Sanctum API authentication

#### **Key Middleware Groups**
- **`maintenance.mode`** - Maintenance mode control
- **`auth:admin`** - Admin authentication
- **`auth`** - User authentication
- **`verified`** - Email verification
- **`role:instructor`** - Instructor role check
- **`approved.instructor`** - Approved instructor status
- **`translation`** - Multi-language support

### Special URL Patterns

#### **File Management**
- `/laravel-filemanager/` - Admin file manager
- `/frontend-filemanager/` - Frontend file manager

#### **Public Resources**
- `/public/certificate/{uuid}` - Public certificate view
- `/public/mentoring/{uuid}` - Public mentoring certificate
- `/stream-video/{fileId}` - Video streaming
- `/secure-video/{hash}` - Secure video access

#### **API Integration**
- `/auth/sso/callback` - SSO authentication callback
- `/callback/course/{enrollment}` - Bantara integration callback
- `/callback/mentoring/{mentoring}` - Mentoring callback


## Architecture Overview

### System Architecture
This is a comprehensive Learning Management System (LMS) built specifically for BKPPSDM (Badan Kepegawaian dan Pengembangan Sumber Daya Manusia) with a focus on government employee training and continuing education. The system follows a modular architecture using nwidart/laravel-modules and supports multi-tenant organizational structure.

### Core Domain Models
The system is built around these core entities:

#### User Management (`app/Models/User.php`)
- **Multi-role system**: Student, Instructor, Admin
- **ASN Integration**: PNS, PPPK, Lainnya employee types
- **Organizational Hierarchy**: Instansi → UNOR → Users
- **Profile Management**: Education, experience, skills, performance evaluation
- **JP Tracking**: J Kredit Pendidikan (Education Credit Points)

#### Course Structure (`app/Models/Course.php`)
```
Course (approval workflow: pending → approved/rejected)
├── CourseChapter (content organization)
│   ├── CourseChapterItem (individual content items)
│   │   ├── CourseChapterLesson (video, pdf, text content)
│   │   └── Quiz (assessments with due dates)
│   └── FollowUpAction (RTL - Rencana Tindak Lanjut)
└── CourseProgress (student progress tracking)
```

#### Order & Enrollment System
```
User → Cart → Order → OrderItem → Enrollment → Certificate

```

### Modular Structure
The application uses a modular architecture with the following key modules:

- **Course**: Course management, lessons, chapters, quizzes, content organization
- **Order**: Enrollment, payments, transactions, order processing
- **CertificateBuilder**: Certificate templates and generation
- **CertificateRecognition**: Certificate verification and recognition
- **BasicPayment**: Multiple payment gateways (PayPal, Stripe, Razorpay)
- **Coaching**: Executive coaching program management
- **Mentoring**: Professional mentoring system
- **InstructorEvaluation**: Performance evaluation and feedback
- **Live Classes**: Zoom and Jitsi integration for real-time sessions
- **Global Setting**: Site configuration, themes, system-wide settings
- **Language**: Multi-language support (Indonesian, English)
- **Currency**: Multi-currency handling
- **Location**: Geographic and organizational data management
- **PendidikanLanjutan**: Continuing education programs
- **Pengumuman**: Announcement system

### Key Components

#### Controllers
- `app/Http/Controllers/Admin/`: Admin panel controllers
- `app/Http/Controllers/Frontend/`: Public-facing controllers
- `app/Http/Controllers/Auth/`: Authentication controllers

#### Models
- Core models in `app/Models/`: User, Course, Enrollment, etc.
- Module-specific models in respective `Modules/{ModuleName}/app/Models/`

#### View Structure
- `resources/views/admin/`: Admin panel templates
- `resources/views/frontend/`: Public-facing templates
- `resources/views/components/`: Reusable Blade components

#### Helper Functions
- `app/Helpers/helper.php`: Global helper functions for file uploads, API responses, etc.

### Authentication & Authorization
- Uses Laravel Sanctum for API authentication
- Spatie Laravel Permission for role-based access control
- Multi-guard system (web, admin)
- Social login integration with Keycloak

### Frontend Stack
- **Vite** for asset building and hot reload
- **Tailwind CSS** for styling
- **Blade** templating engine
- **Axios** for API calls

### Key Features
- Multi-language support (Indonesian, English)
- Multi-currency support
- Course progress tracking with completion certificates
- Certificate generation with digital signing
- Live class integration (Zoom, Jitsi)
- Payment gateway integration with multiple providers
- User profile management with education/experience tracking
- Q&A system for courses with threaded discussions
- Review and rating system
- Nine-box performance evaluation integration
- JP (J Kredit Pendidikan) tracking and reporting
- RTL (Rencana Tindak Lanjut) management

## Development Notes

### File Upload Handling
- Uses `file_upload()` helper function in `app/Helpers/helper.php:34`
- Supports image optimization with Spatie Image Optimizer
- Stores files in private disk with validation

### API Integration
- External API integration for UNOR and Instansi data sync
- Bantara API integration for external services
- Google API for various services

### Module Development
- Each module follows the nwidart/laravel-modules structure
- Modules have their own routes, controllers, models, and views
- Module activation/deactivation through admin panel

### Database Configuration
- Required configuration table entries for Bantara API integration
- Seeding includes sample data for development

## Environment Setup

After fresh installation, ensure these config table entries are set:

| Key | Value |
|-----|-------|
| bantara_url | https://bantara.inidev.my.id |
| bantara_key | {bantara_api_key} |
| bantara_callback_key | {bantara_callback_key} |


## Directory Structure

### Root Directory
```
/home/kominfosys/Code/BKPPSDM/lms/
├── app/                          # Core application code
│   ├── Console/                  # Artisan commands
│   ├── Exceptions/               # Exception handling
│   ├── Http/                     # HTTP layer (controllers, middleware, requests)
│   ├── Models/                   # Core Eloquent models
│   ├── Providers/                # Service providers
│   ├── Services/                 # Business logic services
│   ├── Traits/                   # Reusable traits
│   ├── View/Components/          # Blade components
│   └── Helpers/helper.php        # Global helper functions
├── bootstrap/                    # Bootstrap files
├── config/                       # Configuration files
├── database/                     # Database files
│   ├── factories/               # Model factories
│   ├── migrations/              # Database migrations
│   └── seeders/                 # Database seeders
├── Modules/                      # Modular architecture (30+ modules)
├── public/                       # Public assets
├── resources/                   # Frontend resources
│   ├── js/                      # JavaScript files
│   ├── css/                     # CSS files
│   ├── views/                   # Blade templates
│   └── lang/                    # Language files
├── routes/                       # Route definitions
├── storage/                      # Application storage
├── tests/                        # Test files
└── vendor/                       # Composer dependencies
```

### Module Structure (nwidart/laravel-modules)
Each module follows this standardized structure:
```
Modules/{ModuleName}/
├── app/                          # Module application code
│   ├── Enums/                   # Enumerations
│   ├── Http/                    # HTTP layer
│   │   ├── Controllers/         # Module controllers
│   │   ├── Middleware/          # Custom middleware
│   │   └── Requests/            # Form requests
│   ├── Interfaces/              # Service interfaces
│   ├── Models/                  # Module-specific models
│   ├── Policies/                # Authorization policies
│   ├── Providers/               # Module service providers
│   ├── Services/                # Business logic services
│   └── Traits/                  # Module-specific traits
├── config/                       # Module configuration
├── database/                     # Database files
│   ├── factories/               # Model factories
│   ├── migrations/              # Module migrations
│   └── seeders/                 # Module seeders
├── lang/                         # Language files
├── resources/                    # Frontend resources
│   ├── assets/                  # Module assets
│   └── views/                   # Module views
├── routes/                       # Module routes
│   ├── api.php                  # API routes
│   └── web.php                  # Web routes
└── tests/                        # Module tests
    ├── Feature/                 # Feature tests
    └── Unit/                    # Unit tests
```

### Key Modules by Category

#### Learning Management
- **Course**: Course creation, management, content organization
- **CertificateBuilder**: Certificate template design and generation
- **CertificateRecognition**: Certificate verification system
- **Coaching**: Executive coaching programs
- **Mentoring**: Professional mentoring system

#### Commerce & Payments
- **Order**: Purchase processing and enrollment
- **BasicPayment**: Payment gateway integration
- **Currency**: Multi-currency support
- **Coupon**: Discount management
- **Refund**: Refund processing
- **PaymentWithdraw**: Instructor payout system

#### User Management
- **Customer**: Customer profile management
- **InstructorRequest**: Instructor application workflow
- **InstructorEvaluation**: Performance feedback system
- **Location**: Geographic and organizational data

#### Content & Communication
- **Blog**: Content management system
- **Faq**: Frequently asked questions
- **ContactMessage**: Contact form management
- **NewsLetter**: Email subscription system
- **Pengumuman**: Announcement management
- **SocialLink**: Social media integration

#### System Administration
- **GlobalSetting**: System-wide configuration
- **SiteAppearance**: UI/Theme management
- **Language**: Multi-language support
- **PageBuilder**: Landing page creation
- **Menubuilder**: Navigation management
- **FooterSetting**: Footer content management
- **Installer**: System installation wizard

#### Analytics & Reporting
- **Badges**: Achievement system
- **Testimonial**: User feedback display
- **Brand**: Partner/brand management

#### Specialized Programs
- **PendidikanLanjutan**: Continuing education
- **Article**: Article management system

### View Organization
```
resources/views/
├── admin/                       # Admin panel views
│   ├── auth/                    # Authentication pages
│   ├── dashboard/              # Admin dashboard
│   ├── roles/                  # Role management
│   ├── settings/               # System configuration
│   └── partials/               # Reusable admin components
├── frontend/                    # Public-facing views
│   ├── auth/                    # User authentication
│   ├── dashboard/              # User dashboard
│   ├── instructor-dashboard/   # Instructor dashboard
│   ├── student-dashboard/      # Student dashboard
│   ├── pages/                   # Static pages
│   └── home/                   # Homepage variations
└── components/                  # Reusable Blade components
    ├── admin/                   # Admin components
    ├── frontend/                # Frontend components
    └── validation-error.blade.php # Form validation
```

### Database Architecture
The system uses a relational database with these key patterns:
- **Hierarchical relationships**: Instansi → UNOR → Users
- **Polymorphic content**: Lessons can be various media types
- **Soft deletes**: Courses and related content support soft deletion
- **UUID-based identification**: Secure enrollment and certificate tracking
- **Audit trails**: Order processing and approval workflows

## Code Style

- Follow Laravel 12 coding standards
- Use Laravel Pint for code formatting
- Blade templates use Tailwind CSS classes
- API responses follow consistent JSON structure
- Modular code organization with clear separation of concerns