# Life Atlas - Installation Guide

This guide will help you set up Life Atlas on your local machine or server.

## System Requirements

### Minimum Requirements
- PHP 8.2 or higher
- PostgreSQL 12 or higher
- 512MB RAM
- 100MB disk space
- Modern web browser

### Recommended Requirements
- PHP 8.2+
- PostgreSQL 14+
- 1GB RAM
- 500MB disk space (for user uploads)

## Installation Steps

### 1. Environment Setup

The application is already configured for Replit environment. If deploying elsewhere:

#### Environment Variables Required:
```
PGHOST=your_database_host
PGPORT=5432
PGDATABASE=your_database_name
PGUSER=your_database_user
PGPASSWORD=your_database_password
DATABASE_URL=postgresql://user:pass@host:port/database
```

#### Optional Environment Variables:
```
OPENAI_API_KEY=your_openai_api_key  # For AI Assistant feature
```

### 2. Database Setup

The database schema is already initialized. If you need to reset or set up fresh:

```sql
-- Run the SQL in database/schema.sql
psql -h $PGHOST -U $PGUSER -d $PGDATABASE -f database/schema.sql
```

Or use the Replit SQL tool to execute the schema file.

### 3. File Permissions

Ensure upload directories are writable:

```bash
chmod -R 755 uploads/
chmod -R 755 logs/
```

### 4. Web Server Configuration

#### Using PHP Built-in Server (Development)
```bash
php -S 0.0.0.0:5000 -t public
```

#### Using Apache

Create a virtual host configuration:

```apache
<VirtualHost *:80>
    ServerName your-domain.com
    DocumentRoot /path/to/life-atlas/public

    <Directory /path/to/life-atlas/public>
        AllowOverride All
        Require all granted
    </Directory>

    ErrorLog ${APACHE_LOG_DIR}/life-atlas-error.log
    CustomLog ${APACHE_LOG_DIR}/life-atlas-access.log combined
</VirtualHost>
```

Enable mod_rewrite:
```bash
sudo a2enmod rewrite
sudo systemctl restart apache2
```

#### Using Nginx

```nginx
server {
    listen 80;
    server_name your-domain.com;
    root /path/to/life-atlas/public;
    index index.php;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_index index.php;
        include fastcgi_params;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
    }

    location ~ /\.ht {
        deny all;
    }
}
```

### 5. First User Setup

1. Navigate to the application URL
2. Click on "Register"
3. Fill in the registration form
4. Submit to create your account

### 6. Create Admin User (Optional)

To promote a user to admin:

```sql
UPDATE users SET role = 'admin' WHERE email = 'your-email@example.com';
```

## Configuration

### Application Configuration

Edit `config/app.php` to customize:

```php
return [
    'app_name' => 'Life Atlas',
    'timezone' => 'UTC',  // Change to your timezone
    'session_lifetime' => 120,  // Session timeout in minutes
    'upload_max_size' => 10485760,  // 10MB
    'allowed_file_types' => ['jpg', 'jpeg', 'png', 'pdf', 'doc', 'docx', 'txt'],
    'items_per_page' => 20,
    'openai_model' => 'gpt-3.5-turbo',
];
```

### Security Configuration

For production, ensure:

1. **HTTPS is enabled**
2. **Secure session cookies**:
   ```php
   session_set_cookie_params([
       'lifetime' => 0,
       'path' => '/',
       'domain' => '',
       'secure' => true,  // HTTPS only
       'httponly' => true,
       'samesite' => 'Lax'
   ]);
   ```
3. **Error reporting disabled**:
   ```php
   ini_set('display_errors', 0);
   error_reporting(0);
   ```

## Post-Installation

### 1. Test the Installation

- [ ] Can access the homepage
- [ ] Can register a new account
- [ ] Can login successfully
- [ ] Can create a task
- [ ] Can add a bill
- [ ] Can upload a document
- [ ] Dashboard displays correctly
- [ ] AI Assistant works (if API key configured)

### 2. Configure AI Assistant (Optional)

1. Get an OpenAI API key from https://platform.openai.com
2. Add it to your Replit Secrets or environment:
   ```
   OPENAI_API_KEY=sk-...
   ```
3. Test by going to the AI Assistant page and sending a message

### 3. Customize Branding (Optional)

Edit the following files:
- `app/Views/layouts/header.php` - Navigation and branding
- `public/assets/css/style.css` - Custom styles
- `config/app.php` - Application name

### 4. Set Up Backups

Create a backup script:

```bash
#!/bin/bash
# backup.sh

DATE=$(date +%Y%m%d_%H%M%S)
BACKUP_DIR="/path/to/backups"

# Backup database
pg_dump -h $PGHOST -U $PGUSER $PGDATABASE > "$BACKUP_DIR/db_$DATE.sql"

# Backup uploads
tar -czf "$BACKUP_DIR/uploads_$DATE.tar.gz" uploads/

# Keep only last 7 days
find $BACKUP_DIR -name "*.sql" -mtime +7 -delete
find $BACKUP_DIR -name "*.tar.gz" -mtime +7 -delete
```

Make it executable and add to cron:
```bash
chmod +x backup.sh
crontab -e
# Add: 0 2 * * * /path/to/backup.sh
```

## Troubleshooting

### Common Issues

#### 1. Database Connection Failed
- Check environment variables
- Verify PostgreSQL is running
- Check database credentials
- Ensure database exists

#### 2. 404 Errors on All Pages
- Check .htaccess file exists in public/
- Verify mod_rewrite is enabled (Apache)
- Check try_files directive (Nginx)
- Ensure DocumentRoot points to public/ directory

#### 3. File Upload Fails
- Check directory permissions (755)
- Verify upload_max_filesize in php.ini
- Check post_max_size in php.ini
- Ensure uploads/ directory exists

#### 4. Session Issues
- Check session.save_path is writable
- Verify session cookies are being set
- Check session timeout settings

#### 5. AI Assistant Not Working
- Verify OPENAI_API_KEY is set
- Check API key is valid
- Review error logs for API errors
- Ensure cURL extension is enabled

### Viewing Logs

Application logs are stored in:
- `logs/` directory (if logging enabled)
- PHP error logs (location depends on php.ini)
- Web server error logs

## Performance Optimization

### 1. Enable OpCache

In php.ini:
```ini
opcache.enable=1
opcache.memory_consumption=128
opcache.interned_strings_buffer=8
opcache.max_accelerated_files=4000
opcache.revalidate_freq=60
```

### 2. Database Indexing

Indexes are already created in the schema. For large datasets, consider:
```sql
CREATE INDEX idx_bills_due_date ON bills(due_date);
CREATE INDEX idx_tasks_due_date ON tasks(due_date);
CREATE INDEX idx_notifications_created_at ON notifications(created_at);
```

### 3. Session Storage

For better performance, use Redis or Memcached for sessions:
```php
// In php.ini
session.save_handler = redis
session.save_path = "tcp://127.0.0.1:6379"
```

## Security Checklist

Before going live:

- [ ] HTTPS enabled
- [ ] Firewall configured
- [ ] Database not publicly accessible
- [ ] Strong admin password set
- [ ] File upload limits configured
- [ ] Error display disabled in production
- [ ] Backups configured
- [ ] Session security configured
- [ ] CSRF protection enabled (already built-in)
- [ ] SQL injection protection (already built-in)
- [ ] XSS protection (already built-in)

## Updating

To update the application:

1. Backup database and files
2. Pull latest code
3. Run any new database migrations
4. Clear cache/sessions if needed
5. Test thoroughly

## Support

If you encounter issues:
1. Check this installation guide
2. Review README.md
3. Check error logs
4. Review the code for inline comments

## Next Steps

After installation:
1. Explore the dashboard
2. Create your first task
3. Add a bill to track
4. Try the AI Assistant
5. Customize your profile
6. Set up budgets
7. Invite team members (if applicable)

Congratulations! Life Atlas is now installed and ready to use.
