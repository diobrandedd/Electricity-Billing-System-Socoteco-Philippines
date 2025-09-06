# SOCOTECO II Billing Management System - Installation Guide

This guide will walk you through the complete installation process for the SOCOTECO II Billing Management System.

## Prerequisites

Before installing the system, ensure you have the following:

### System Requirements
- **Operating System**: Windows 10/11, macOS, or Linux
- **Web Server**: Apache 2.4+ or Nginx 1.18+
- **PHP**: Version 7.4 or higher (PHP 8.0+ recommended)
- **MySQL**: Version 5.7 or higher (MySQL 8.0+ recommended)
- **Web Browser**: Chrome, Firefox, Safari, or Edge (latest versions)

### Required PHP Extensions
- PDO
- PDO_MySQL
- JSON
- Session
- mbstring
- fileinfo
- finfo

### Recommended Development Environment
- **XAMPP** (Windows/Mac/Linux) - [Download](https://www.apachefriends.org/)
- **WAMP** (Windows) - [Download](http://www.wampserver.com/)
- **MAMP** (Mac) - [Download](https://www.mamp.info/)

## Installation Steps

### Step 1: Download and Extract Files

1. **Download the system files** to your local machine
2. **Extract the files** to your web server directory:
   - **XAMPP**: `C:\xampp\htdocs\socotecoSys\`
   - **WAMP**: `C:\wamp64\www\socotecoSys\`
   - **MAMP**: `/Applications/MAMP/htdocs/socotecoSys/`

### Step 2: Start Web Server and Database

#### For XAMPP:
1. Open XAMPP Control Panel
2. Start **Apache** service
3. Start **MySQL** service
4. Verify both services are running (green status)

#### For WAMP:
1. Start WAMP server
2. Ensure both Apache and MySQL are running (green icon)

#### For MAMP:
1. Start MAMP
2. Click "Start Servers"
3. Verify Apache and MySQL are running

### Step 3: Database Setup

#### Option A: Using phpMyAdmin (Recommended)

1. **Open phpMyAdmin**:
   - XAMPP: `http://localhost/phpmyadmin`
   - WAMP: `http://localhost/phpmyadmin`
   - MAMP: `http://localhost:8888/phpMyAdmin/`

2. **Create Database**:
   - Click "New" in the left sidebar
   - Enter database name: `socoteco_billing`
   - Select collation: `utf8mb4_unicode_ci`
   - Click "Create"

3. **Import Schema**:
   - Select the `socoteco_billing` database
   - Click "Import" tab
   - Click "Choose File" and select `database/schema.sql`
   - Click "Go" to import

#### Option B: Using MySQL Command Line

1. **Open MySQL command line**:
   ```bash
   mysql -u root -p
   ```

2. **Create database**:
   ```sql
   CREATE DATABASE socoteco_billing CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
   USE socoteco_billing;
   ```

3. **Import schema**:
   ```bash
   mysql -u root -p socoteco_billing < database/schema.sql
   ```

### Step 4: Configure Database Connection

1. **Open** `config/database.php`
2. **Update connection settings** if needed:
   ```php
   private $host = 'localhost';
   private $db_name = 'socoteco_billing';
   private $username = 'root';
   private $password = ''; // Add your MySQL password if set
   ```

### Step 5: Set File Permissions (Linux/Mac)

If you're using Linux or Mac, set proper file permissions:

```bash
# Navigate to the project directory
cd /path/to/socotecoSys

# Set directory permissions
find . -type d -exec chmod 755 {} \;

# Set file permissions
find . -type f -exec chmod 644 {} \;

# Make sure upload directories are writable
chmod 777 uploads/
```

### Step 6: Access the System

1. **Open your web browser**
2. **Navigate to the system**:
   - XAMPP: `http://localhost/socotecoSys/auth/login.php`
   - WAMP: `http://localhost/socotecoSys/auth/login.php`
   - MAMP: `http://localhost:8888/socotecoSys/auth/login.php`

3. **Login with default credentials**:
   - **Username**: `admin`
   - **Password**: `admin123`

### Step 7: Initial System Configuration

#### 7.1 Change Admin Password
1. Go to **Settings** → **User Management**
2. Find the admin user and click **Edit**
3. Change the password to a secure one
4. Save changes

#### 7.2 Configure System Settings
1. Go to **Settings** → **System Settings**
2. Update the following:
   - Company Name: SOCOTECO II
   - Company Address: Your actual address
   - VAT Rate: 12 (default)
   - Penalty Rate: 2 (default)
   - Due Days: 15 (default)

#### 7.3 Verify Customer Categories
1. Go to **Settings** → **Customer Categories**
2. Verify the default categories are correct:
   - Residential (₱8.50/kWh)
   - Commercial (₱9.20/kWh)
   - Industrial (₱8.80/kWh)
   - Government (₱8.00/kWh)

#### 7.4 Create User Accounts
1. Go to **Settings** → **User Management**
2. Click **Add User**
3. Create accounts for:
   - Cashiers
   - Meter Readers
   - Additional Administrators

## Post-Installation Checklist

### ✅ System Verification

- [ ] Database connection successful
- [ ] Admin login working
- [ ] Dashboard loads correctly
- [ ] All navigation menus functional
- [ ] Customer management accessible
- [ ] Meter reading system working
- [ ] Billing system functional
- [ ] Payment processing working
- [ ] Reports generating correctly

### ✅ Security Configuration

- [ ] Admin password changed
- [ ] Default user accounts created
- [ ] File permissions set correctly
- [ ] Database credentials secured
- [ ] SSL certificate installed (production)

### ✅ Data Setup

- [ ] Customer categories configured
- [ ] System settings updated
- [ ] Billing rates configured
- [ ] Test customer added
- [ ] Test meter reading recorded
- [ ] Test bill generated
- [ ] Test payment processed

## Troubleshooting

### Common Installation Issues

#### Issue: "Database connection failed"
**Solution**:
- Check if MySQL is running
- Verify database credentials
- Ensure database exists
- Check MySQL port (default: 3306)

#### Issue: "Access denied for user 'root'"
**Solution**:
- Check MySQL password
- Update `config/database.php` with correct password
- Reset MySQL root password if needed

#### Issue: "File not found" errors
**Solution**:
- Verify file paths are correct
- Check web server document root
- Ensure all files are in the correct directory

#### Issue: "Permission denied" errors
**Solution**:
- Check file permissions
- Ensure web server has read access
- Set proper ownership of files

#### Issue: "Session start" errors
**Solution**:
- Check PHP session configuration
- Ensure session directory is writable
- Verify session extension is enabled

### Performance Optimization

#### For Production Environment:

1. **Enable PHP OPcache**:
   ```ini
   opcache.enable=1
   opcache.memory_consumption=128
   opcache.max_accelerated_files=4000
   ```

2. **Configure MySQL**:
   ```ini
   innodb_buffer_pool_size=256M
   query_cache_size=32M
   ```

3. **Enable Gzip Compression**:
   ```apache
   LoadModule deflate_module modules/mod_deflate.so
   <Location />
       SetOutputFilter DEFLATE
   </Location>
   ```

## Backup and Maintenance

### Database Backup
```bash
# Create backup
mysqldump -u root -p socoteco_billing > backup_$(date +%Y%m%d).sql

# Restore backup
mysql -u root -p socoteco_billing < backup_20231201.sql
```

### File Backup
```bash
# Create file backup
tar -czf socoteco_backup_$(date +%Y%m%d).tar.gz /path/to/socotecoSys/
```

### Regular Maintenance Tasks
- [ ] Daily database backups
- [ ] Weekly file backups
- [ ] Monthly system updates
- [ ] Quarterly security reviews
- [ ] Annual system audits

## Support and Documentation

### Getting Help
- Check this installation guide first
- Review the main README.md file
- Contact system administrator
- Check system logs for error details

### System Logs
- **Apache Error Log**: Check web server error logs
- **PHP Error Log**: Check PHP error log file
- **MySQL Error Log**: Check MySQL error log
- **Application Logs**: Check audit_trail table in database

### Additional Resources
- PHP Documentation: https://www.php.net/docs.php
- MySQL Documentation: https://dev.mysql.com/doc/
- Bootstrap Documentation: https://getbootstrap.com/docs/
- Chart.js Documentation: https://www.chartjs.org/docs/

---

**Installation completed successfully!** 🎉

Your SOCOTECO II Billing Management System is now ready to use. Remember to:
1. Change default passwords
2. Configure system settings
3. Create user accounts
4. Set up regular backups
5. Test all functionality

For any issues or questions, refer to the troubleshooting section or contact your system administrator.
