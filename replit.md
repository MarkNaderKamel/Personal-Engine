# Life Atlas Project

## Overview
Life Atlas is a comprehensive personal life management platform built with pure PHP 8.2 using MVC architecture. It allows users to manage financial, productivity, and personal aspects of their life from a single dashboard.

## Project Structure

### Architecture
- **MVC Pattern**: Clean separation of concerns with Models, Views, and Controllers
- **No Framework**: Pure PHP implementation for maximum control and learning
- **Database**: PostgreSQL with normalized schema and indexes
- **Frontend**: Bootstrap 5 with responsive design and modern gradients
- **Navigation**: Collapsible left sidebar with organized sections

### Directory Structure
```
/
├── app/
│   ├── Controllers/   # Request handling and business logic
│   ├── Models/        # Data layer and database interactions
│   ├── Views/         # HTML templates (PHP files)
│   │   ├── layouts/   # Header (sidebar), footer templates
│   │   ├── auth/      # Login, register views
│   │   └── modules/   # Feature-specific views
│   └── Core/          # Core classes (Database, Router, Security, Model)
├── config/            # Configuration files
├── database/          # SQL schema files
├── public/            # Web root (index.php, assets, .htaccess)
│   └── assets/        # CSS, JS, images
├── uploads/           # User file uploads (documents, profiles)
└── logs/              # Application logs
```

## Key Features Implemented

### Authentication & Security
- User registration and login
- Session-based authentication
- CSRF token protection on all forms
- XSS prevention with output escaping
- SQL injection protection via prepared statements
- Password hashing with bcrypt
- Role-based access (admin/user)

### Financial Management
- **Bills**: Track one-time and recurring bills with alerts
- **Transactions**: Income/expense tracking with categories
- **Budgets**: Monthly budget planning by category
- **Subscriptions**: Manage all subscriptions and track monthly costs
- **Debts**: Loan and credit card tracking with interest rates and payment schedules
- **Assets**: Property and investment tracking with valuation
- **Crypto Portfolio**: Track cryptocurrency holdings with prices

### Career
- **Job Applications**: Full job search tracker with status workflow
- **CV Manager**: Multi-resume builder with experience, education, skills, certifications
- **Goals**: Goal tracking with progress percentages and milestones

### Productivity
- **Tasks**: Create and track tasks with priorities and due dates
- **Projects**: Organize tasks into projects
- **Time Tracking**: Track time spent on tasks with start/stop timer
- **Habits**: Daily habit tracking with streaks
- **Notes**: Rich text notes with favorites and tagging
- **Events**: Calendar and event management with reminders
- **Contracts**: Store and track contracts with expiry alerts

### Personal Life
- **Contacts**: Store contact information with birthday reminders
- **Relationships**: Manage personal/professional relationships
- **Birthdays**: Birthday tracking with reminders
- **Pets**: Pet care management with vet checkups
- **Reading List**: Book tracking with progress and ratings
- **Travel Plans**: Trip planning with budgets and itineraries
- **Vehicles**: Maintenance and insurance tracking
- **Social Links**: Manage social media profiles

### Tools
- **AI Assistant**: Chat interface with OpenAI integration
- **Password Manager**: Encrypted password storage with categories
- **Document Management**: Secure file upload and storage
- **Weather**: Weather information display
- **News**: News feed display
- **Analytics**: Usage tracking and XP history

### System Features
- **Gamification**: XP points, levels, streaks, and badges
- **Notifications**: In-app notification center with AJAX updates
- **Admin Panel**: User management, system monitoring, activity logs

## Tech Stack

### Backend
- PHP 8.2
- PostgreSQL (via environment variables)
- PDO with prepared statements
- Custom MVC framework

### Frontend
- Bootstrap 5
- Bootstrap Icons
- Vanilla JavaScript
- AJAX for real-time updates
- Responsive mobile design
- Modern gradient styling
- Collapsible left sidebar navigation

## Development

### Running the Application
The application runs via a PHP built-in server workflow:
```bash
php -S 0.0.0.0:5000 -t public
```

### Database
Database is automatically configured via environment variables:
- PGHOST, PGPORT, PGDATABASE, PGUSER, PGPASSWORD

Schema is initialized in the database with all necessary tables and indexes.

### Test Credentials
- Email: demo@lifeatlas.app
- Password: password

### Adding a New Module
1. Create Model in `app/Models/`
2. Create Controller in `app/Controllers/`
3. Create Views in `app/Views/modules/[module-name]/`
4. Add routes in `public/index.php`
5. Add navigation link in sidebar (`app/Views/layouts/header.php`)

## Navigation Structure (Left Sidebar)
The navigation is organized into collapsible sections in the left sidebar:

- **Dashboard**: Main overview
- **Financial**: Transactions, Bills, Budgets, Subscriptions, Debts, Assets, Crypto Portfolio
- **Career**: Job Applications, CV Manager, Goals
- **Productivity**: Tasks, Projects, Time Tracking, Habits, Notes, Events, Contracts
- **Personal**: Contacts, Relationships, Birthdays, Pets, Reading List, Travel, Vehicles, Social Links
- **Tools**: AI Assistant, Passwords, Documents, Weather, News, Analytics
- **Admin** (admin only): Dashboard, Users, Logs

## Security Considerations

### Implemented
- CSRF protection on all POST forms
- XSS prevention via output escaping
- SQL injection prevention via PDO prepared statements
- Password hashing with bcrypt
- File upload validation
- Session security
- Role-based access control
- Encrypted password storage (AES-256-CBC)

### Best Practices
- Never trust user input
- Always validate and sanitize
- Use parameterized queries
- Escape output in views
- Verify CSRF tokens on state-changing operations
- Keep uploads outside webroot when possible

## Environment Variables

### Required
- `PGHOST`: PostgreSQL host
- `PGPORT`: PostgreSQL port (default 5432)
- `PGDATABASE`: Database name
- `PGUSER`: Database user
- `PGPASSWORD`: Database password
- `DATABASE_URL`: Full connection string

### Optional
- `OPENAI_API_KEY`: For AI Assistant feature
- `PASSWORD_ENCRYPTION_KEY`: For password manager encryption
- `REPL_SLUG`: For Replit-specific URLs
- `REPL_OWNER`: For Replit-specific URLs

## Recent Changes

### 2025-11-28: Phase 5 - Major Navigation Redesign
- **Sidebar Navigation**: Moved all navigation from top header to collapsible left sidebar
- **Organized Sections**: Navigation now grouped into Financial, Career, Productivity, Personal, Tools, and Admin sections
- **Mobile Support**: Bottom navigation bar for mobile devices with quick access icons
- **User Stats**: XP level, streak, and points displayed in sidebar footer
- **Top Header**: Simplified header with sidebar toggle, notifications, and user menu
- **Responsive Design**: Sidebar collapses to overlay on tablets and hides on mobile
- **Modern Styling**: Dark gradient sidebar with hover effects and active state indicators

### 2025-11-28: Phase 4 - Edit Functionality & UI Enhancement
- **Task Management**: Added full edit functionality with status, priority, and due date modification
- **Budget Management**: Added edit functionality plus quick expense addition via modal dialogs
- **Subscription Management**: Added edit functionality with pause/reactivate toggle buttons
- **Contact Management**: Added edit and detailed view functionality with quick actions
- **UI Improvements**: Updated all action buttons to use icon-based button groups for cleaner interface

### 2025-11-28: Phase 3 - Career & Personal Management Modules
- **Job Application Tracker**: Complete CRUD for job applications with status tracking
- **CV/Resume Manager**: Multi-resume support with work experience, education, skills, certifications
- **Goals Module**: Goal tracking with categories, priorities, progress, milestones
- **Habits Tracker**: Daily habit tracking with streaks and categories
- **Birthdays Manager**: Birthday tracking with reminders and gift ideas
- **Social Links**: Manage social media profiles

### 2025-11-28: Phase 2 - Enhanced Features & UI
- **Crypto Portfolio Management**: Full CRUD for crypto assets
- **Relationships Tracker**: Manage personal/professional relationships
- **Analytics Dashboard**: Comprehensive analytics
- **Password Reset System**: Token-based reset

### 2025-11-28: Phase 1 - Complete Module Implementation
- Added 12 new fully-functional modules
- Created full CRUD operations for all modules
- Enhanced UI with modern gradient styling
- Added gamification integration to all modules

## User Preferences
None specified yet.

## Common Tasks

### Create a New User
1. Navigate to `/register`
2. Fill in email, password, and optional name
3. Submit to create account
4. Login at `/login`

### Promote User to Admin
```sql
UPDATE users SET role = 'admin' WHERE email = 'user@example.com';
```

### Check Database Connection
The app will fail to load if database connection is not working. Check:
1. Environment variables are set correctly
2. PostgreSQL is running
3. Database exists and schema is initialized

## Troubleshooting

### Common Issues
1. **404 on all pages**: Check .htaccess exists and mod_rewrite is enabled
2. **Database errors**: Verify environment variables and connection
3. **CSRF errors**: Clear browser cookies and restart session
4. **File upload fails**: Check directory permissions (755 for uploads/)
5. **AI not working**: Verify OPENAI_API_KEY is set in secrets

## Last Updated
November 28, 2025
