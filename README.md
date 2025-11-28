# Life Atlas - All-in-One Personal Life Management Platform

A comprehensive web application built in pure PHP with MVC architecture for managing every aspect of your personal and financial life from one dashboard.

## Features

### Authentication System
- User registration with email verification
- Secure login with session management
- Password reset functionality
- Profile management
- Role-based access control (Admin/User)

### Dashboard
- Real-time statistics and widgets
- Upcoming bills and tasks overview
- Gamification progress tracking
- Quick action panels
- Recent activity feed

### AI Assistant Module
- Chat-based AI assistant powered by OpenAI
- Context-aware suggestions
- Financial advice and productivity coaching
- Conversation history tracking

### Gamification Engine
- XP system with level progression
- Daily and weekly streak tracking
- Achievement badges
- Action-based rewards
- Leaderboard ready

### Financial Modules
1. **Bills Management** - Track recurring and one-time bills with automatic reminders
2. **Budget Planning** - Monthly budget tracking by category
3. **Subscriptions** - Manage all subscriptions and track monthly costs
4. **Crypto Portfolio** - Track cryptocurrency holdings (ready for price API integration)
5. **Debt Tracker** - Monitor loans and credit card debt
6. **Asset Management** - Track property, vehicles, and investments

### Productivity Modules
1. **Task Management** - Create, track, and complete tasks with priorities
2. **Project Management** - Organize tasks into projects
3. **Time Tracking** - Track time spent on tasks
4. **Events & Calendar** - Schedule and manage events
5. **Contracts Management** - Store contract details and deadlines
6. **Notes** - Create text and voice notes

### Personal Life Modules
1. **Contacts Management** - Store contact information with birthday tracking
2. **Relationships Tracker** - Manage personal relationships
3. **Pet Care** - Track pet information and vet appointments
4. **Reading List** - Manage books to read and completed
5. **Travel Planner** - Plan trips and track travel details
6. **Vehicle Management** - Track service dates and insurance
7. **Password Manager** - Securely store encrypted passwords

### Document & File System
- Secure file upload with validation
- Document categorization
- File size limits and type restrictions
- Download and delete capabilities

### Notification System
- In-app notification center
- Unread notification counter
- Mark as read functionality
- Real-time notifications via AJAX

### Analytics System
- User activity tracking
- XP transaction history
- System usage statistics
- Admin analytics dashboard

### Admin Panel
- User management
- System monitoring
- Activity logs
- Statistics dashboard

## Tech Stack

- **Backend**: Pure PHP 8.2 (OOP + MVC Architecture)
- **Database**: PostgreSQL
- **Frontend**: HTML5, CSS3, Bootstrap 5, Vanilla JavaScript
- **Security**: CSRF protection, prepared statements, password hashing

## Installation

### Prerequisites
- PHP 8.2 or higher
- PostgreSQL database
- Web server (Apache/Nginx) or PHP built-in server

### Setup Instructions

1. **Clone or download the project**
```bash
git clone <repository-url>
cd life-atlas
```

2. **Configure Database**
   - The database is automatically configured using environment variables
   - Database schema is already initialized

3. **Set up OpenAI API Key (Optional)**
   - To use the AI Assistant, set the OPENAI_API_KEY environment variable
   - Go to Replit Secrets and add: `OPENAI_API_KEY = your-key-here`

4. **Start the Application**
   - The PHP server is already running on port 5000
   - Access the application in your browser

5. **Register an Account**
   - Go to `/register` to create your first account
   - The first user can be manually promoted to admin in the database if needed

## Directory Structure

```
life-atlas/
├── app/
│   ├── Controllers/      # Application controllers
│   ├── Models/           # Data models
│   ├── Views/            # View templates
│   └── Core/             # Core classes (Database, Router, Security, Model)
├── config/               # Configuration files
│   ├── app.php          # Application config
│   └── database.php     # Database config
├── database/             # Database schema
│   └── schema.sql       # Complete SQL schema
├── public/               # Public web root
│   ├── index.php        # Application entry point
│   ├── .htaccess        # URL rewriting rules
│   └── assets/          # CSS, JS, images
├── uploads/              # User uploaded files
│   ├── documents/       # Document storage
│   └── profiles/        # Profile images
└── logs/                 # Application logs
```

## Security Features

- **SQL Injection Protection**: All queries use prepared statements
- **XSS Protection**: Input sanitization and output escaping
- **CSRF Protection**: Token-based CSRF validation
- **Password Security**: Bcrypt password hashing
- **File Upload Security**: Type and size validation
- **Session Security**: Secure session management
- **Role-Based Access**: Admin and user role separation

## Module Overview

### Bills Management
Track all your bills in one place with:
- Recurring bill support
- Due date tracking
- Category organization
- Payment status tracking
- Overdue bill alerts

### Task Management
Stay productive with:
- Task creation with priorities
- Due date tracking
- Status management (pending, in progress, completed)
- XP rewards for completing tasks
- Project association

### Budget Planning
Control your finances with:
- Monthly budget tracking
- Category-based budgeting
- Spending vs budget comparison
- Budget alerts when overspending

### AI Assistant
Get personalized help with:
- Financial advice
- Task prioritization
- Goal setting
- General life management tips

### Gamification
Stay motivated with:
- XP points for every action
- Level progression system
- Daily streak tracking
- Achievement badges
- Longest streak records

## API Endpoints

- `GET /` - Redirect to dashboard
- `GET /login` - Login page
- `POST /login` - Process login
- `GET /register` - Registration page
- `POST /register` - Process registration
- `GET /logout` - Logout user
- `GET /dashboard` - Main dashboard
- `GET /bills` - Bills list
- `GET /tasks` - Tasks list
- `GET /budgets` - Budgets overview
- `GET /subscriptions` - Subscriptions list
- `GET /contacts` - Contacts list
- `GET /projects` - Projects list
- `GET /ai-assistant` - AI chat interface
- `GET /documents` - Document manager
- `GET /notifications` - Notification center
- `GET /admin` - Admin dashboard (admin only)
- `GET /api/notifications/unread` - Get unread notifications (JSON)

## Database Schema

The application uses 30+ normalized tables including:
- `users` - User accounts
- `tasks` - Task management
- `bills` - Bill tracking
- `budgets` - Budget planning
- `subscriptions` - Subscription tracking
- `contacts` - Contact management
- `projects` - Project organization
- `documents` - File metadata
- `notifications` - Notification system
- `user_xp` - Gamification tracking
- `xp_transactions` - XP history
- `badges` - Achievement badges
- `ai_conversations` - AI chat history
- And many more...

## Configuration

### Application Settings (`config/app.php`)
- App name and URL
- Timezone settings
- Session lifetime
- Upload limits
- Allowed file types
- OpenAI API configuration

### Database Settings (`config/database.php`)
- Automatically configured from environment variables
- PDO connection with prepared statements
- Error handling and logging

## Gamification System

### XP Rewards
- Login: 10 XP
- Create Task: 10 XP
- Complete Task: 20 XP
- Add Bill: 15 XP
- Create Budget: 15 XP
- Add Contact: 10 XP
- Create Project: 20 XP
- Upload Document: 10 XP
- Use AI Assistant: 5 XP

### Leveling
- Level 1: 0 XP
- Level 2: 1,000 XP
- Level 3: 2,000 XP
- And so on... (1000 XP per level)

## Production Deployment

For production deployment:

1. **Use a production-ready web server** (Apache/Nginx)
2. **Enable HTTPS** for secure connections
3. **Set proper file permissions**
   ```bash
   chmod 755 public/
   chmod 644 public/index.php
   chmod -R 755 uploads/
   ```
4. **Configure environment variables securely**
5. **Set up database backups**
6. **Enable error logging** (disable display_errors)
7. **Implement rate limiting** for API endpoints
8. **Configure CORS** if needed

## Browser Support

- Chrome/Edge (latest)
- Firefox (latest)
- Safari (latest)
- Mobile browsers (iOS Safari, Chrome Mobile)

## Future Enhancements

Planned features:
- Email verification system
- Two-factor authentication
- Export data to PDF/CSV
- Mobile app (React Native)
- Dark mode toggle
- Multi-language support
- Advanced analytics charts
- Integration with external APIs (Crypto prices, Weather, News)
- OCR document scanning
- Voice note recording

## Support

For issues, questions, or feature requests:
- Check the documentation
- Review the code comments
- Contact the development team

## License

This project is proprietary software. All rights reserved.

## Credits

Developed as a comprehensive personal life management platform using modern PHP practices and MVC architecture.

---

**Version**: 1.0.0  
**Last Updated**: November 2025
