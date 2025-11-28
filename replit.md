# Life Atlas Project

## Overview
Life Atlas is a comprehensive personal life management platform built with pure PHP 8.2 using MVC architecture. It allows users to manage financial, productivity, and personal aspects of their life from a single dashboard.

## Project Structure

### Architecture
- **MVC Pattern**: Clean separation of concerns with Models, Views, and Controllers
- **No Framework**: Pure PHP implementation for maximum control and learning
- **Database**: PostgreSQL with normalized schema and indexes
- **Frontend**: Bootstrap 5 with responsive design and modern gradients

### Directory Structure
```
/
├── app/
│   ├── Controllers/   # Request handling and business logic
│   ├── Models/        # Data layer and database interactions
│   ├── Views/         # HTML templates (PHP files)
│   │   ├── layouts/   # Header, footer templates
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
- **Budgets**: Monthly budget planning by category
- **Subscriptions**: Manage all subscriptions and track monthly costs
- **Debts**: Loan and credit card tracking with interest rates and payment schedules
- **Assets**: Property and investment tracking with valuation

### Productivity
- **Tasks**: Create and track tasks with priorities and due dates
- **Projects**: Organize tasks into projects
- **Time Tracking**: Track time spent on tasks with start/stop timer
- **Events**: Calendar and event management with reminders
- **Contracts**: Store and track contracts with expiry alerts
- **Notes**: Rich text notes with favorites and tagging

### Personal Life
- **Contacts**: Store contact information with birthday reminders
- **Pets**: Pet care management with vet checkups
- **Reading List**: Book tracking with progress and ratings
- **Travel Plans**: Trip planning with budgets and itineraries
- **Vehicles**: Maintenance and insurance tracking
- **Password Manager**: Encrypted password storage with categories

### System Features
- **AI Assistant**: Chat interface with OpenAI integration
- **Gamification**: XP points, levels, streaks, and badges
- **Notifications**: In-app notification center with AJAX updates
- **Document Management**: Secure file upload and storage
- **Admin Panel**: User management, system monitoring, activity logs
- **Analytics**: Usage tracking and XP history

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

### Adding a New Module
1. Create Model in `app/Models/`
2. Create Controller in `app/Controllers/`
3. Create Views in `app/Views/modules/[module-name]/`
4. Add routes in `public/index.php`
5. Update navigation in `app/Views/layouts/header.php`

## Navigation Structure
The navbar is organized into dropdown categories:
- **Financial**: Bills, Budgets, Subscriptions, Debts, Assets, Crypto Portfolio
- **Productivity**: Tasks, Projects, Time Tracking, Notes, Events, Contracts
- **Personal**: Contacts, Relationships, Pets, Reading List, Travel, Vehicles
- **Tools**: AI Assistant, Passwords, Documents, Analytics

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

### 2025-11-28: Phase 2 - Enhanced Features & UI
- **Crypto Portfolio Management**: Full CRUD for crypto assets with portfolio value tracking
- **Relationships Tracker**: Manage personal/professional relationships with important dates
- **Analytics Dashboard**: Comprehensive analytics with financial, productivity, and gamification stats
- **Password Reset System**: Forgot password + reset password with token-based authentication
- **Change Password**: Secure password change from profile page with current password verification
- **Document Download**: Added download functionality for uploaded documents with proper MIME types
- **Enhanced Login Page**: Professional UI with globe icon, gradients, forgot password link
- **Enhanced Profile Page**: Improved design with gamification stats, badges, and password change form
- **Enhanced Dashboard**: Comprehensive widgets showing all key metrics at a glance

### 2025-11-28: Phase 1 - Complete Module Implementation
- Added 12 new fully-functional modules:
  - Debts, Assets, Notes, Events, Contracts, Pets
  - Reading List, Travel, Vehicles, Passwords, Time Tracking, Relationships
- Created full CRUD operations for all modules
- Enhanced UI with modern gradient styling
- Updated navigation with organized dropdowns
- Added gamification integration to all modules

### UI Enhancements
- Modern gradient backgrounds on cards
- Subtle animations and transitions
- Improved table styling with hover effects
- Better mobile responsiveness
- Professional form styling
- Custom CSS utilities (skeleton loading, status badges, hover effects)
- Relationship avatars and crypto icons

### Security Enhancements
- Added CSRF tokens to all forms
- Changed DELETE operations to POST requests
- Added database indexes on all foreign keys
- Implemented input validation and output escaping
- Added encrypted password storage
- Token-based password reset with expiry

## Future Enhancements

### High Priority
- Email verification system
- Email-based password reset (currently token-based for demo)
- Export to PDF/CSV
- Two-factor authentication
- Crypto price API integration for live prices

### Medium Priority
- OCR document scanning
- Voice notes recording
- Crypto price API integration
- Weather API integration
- Dark mode toggle

### Low Priority
- Mobile app (React Native)
- Multi-language support
- Advanced charts and visualizations
- Social sharing features

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

### Add Test Data
```sql
INSERT INTO tasks (user_id, title, priority, status) 
VALUES (1, 'Test Task', 'high', 'pending');

INSERT INTO bills (user_id, bill_name, amount, due_date, category) 
VALUES (1, 'Electricity', 150.00, CURRENT_DATE + INTERVAL '7 days', 'utilities');
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

## Documentation
- See README.md for feature overview
- See INSTALLATION.md for deployment guide
- Inline code comments for detailed logic

## Last Updated
November 28, 2025
