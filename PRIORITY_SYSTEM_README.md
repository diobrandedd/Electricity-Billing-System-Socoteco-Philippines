# Priority Number Generator System

A comprehensive priority number generator system for SOCOTECO II Billing Management System that handles customer queue management with strict first-come, first-serve ordering and real-time display capabilities.

## Features

### Core Functionality
- **Priority Number Generation**: Generate unique, sequential priority numbers for customers
- **Strict Queue Management**: Enforce first-come, first-serve ordering
- **Daily Capacity Control**: Limit service to 1,000 customers per day (configurable)
- **Service Day Assignment**: Automatically assign customers to specific service days
- **Real-time Display**: Live updates of current priority number being served
- **Queue Statistics**: Comprehensive analytics and reporting

### User Interfaces
- **Customer Interface**: Generate priority numbers and view status
- **Admin Interface**: Manage queue, serve customers, and view analytics
- **Real-time Display**: Public display for service centers
- **Settings Panel**: Configure system parameters

### Security & Reliability
- **CSRF Protection**: Secure form submissions
- **Role-based Access**: Admin, cashier, and customer permissions
- **Audit Trail**: Complete logging of all actions
- **Data Validation**: Input sanitization and validation
- **Error Handling**: Comprehensive error management

## Installation

### Prerequisites
- PHP 7.4 or higher
- MySQL 5.7 or MariaDB 10.3+
- PDO extension
- JSON extension
- Web server (Apache/Nginx)

### Setup Steps

1. **Database Setup**
   ```bash
   # Import the priority system tables
   mysql -u username -p database_name < database/priority_system.sql
   ```

2. **Run Setup Script**
   - Navigate to `setup_priority_system.php` in your browser
   - Follow the installation wizard
   - Configure initial settings

3. **Configure Settings**
   - Go to `priority_settings.php`
   - Set daily capacity, advance booking days, and other parameters
   - Save configuration

## File Structure

```
socotecoSys/
├── database/
│   └── priority_system.sql          # Database schema
├── includes/
│   └── PriorityNumberGenerator.php  # Core system class
├── ajax/
│   ├── priority_number.php          # AJAX endpoints
│   └── priority_display.php         # Real-time display API
├── priority_number_generator.php    # Customer interface
├── priority_queue_management.php    # Admin interface
├── priority_display.php             # Real-time display page
├── priority_settings.php            # System settings
└── setup_priority_system.php        # Installation script
```

## Database Tables

### priority_numbers
Stores all priority numbers with customer information and status.

### priority_queue_status
Tracks current queue status and daily statistics.

### service_days
Manages service day capacity and availability.

### priority_number_history
Audit trail for all priority number actions.

## Usage Guide

### For Customers

1. **Generate Priority Number**
   - Login to the system
   - Navigate to Priority Number Generator
   - Click "Generate Priority Number"
   - Note your priority number and service date

2. **Check Status**
   - View your priority number status
   - See estimated wait time
   - Check service date and position

3. **Cancel if Needed**
   - Use the cancel button if you can't make it
   - Provide a reason for cancellation

### For Staff (Admin/Cashier)

1. **Queue Management**
   - Access Priority Queue Management
   - View current serving number
   - Mark customers as served
   - Skip numbers if needed

2. **Real-time Updates**
   - Update current priority number
   - View upcoming customers
   - Monitor queue statistics

3. **System Administration**
   - Configure system settings
   - Clear expired numbers
   - Reset queue status
   - Export reports

### For Display Screens

1. **Setup Display**
   - Open `priority_display.php` in a browser
   - Display on service center screens
   - Auto-refreshes every 10 seconds

## Configuration

### System Settings

| Setting | Description | Default |
|---------|-------------|---------|
| `priority_daily_capacity` | Max customers per day | 1000 |
| `priority_advance_days` | Days in advance for booking | 7 |
| `priority_expiry_hours` | Hours before expiry | 24 |
| `priority_notification_enabled` | Enable notifications | 1 |
| `priority_auto_assign_days` | Auto-assign service days | 1 |
| `priority_weekend_service` | Allow weekend service | 0 |

### Customization

1. **Daily Capacity**: Adjust based on staff availability
2. **Advance Booking**: Set how far ahead customers can book
3. **Expiry Time**: Configure when numbers expire
4. **Weekend Service**: Enable/disable weekend operations
5. **Break Times**: Set lunch break periods

## API Endpoints

### AJAX Endpoints (`ajax/priority_number.php`)

- `generate` - Generate new priority number
- `get_current` - Get current serving number
- `update_current` - Update current number (staff only)
- `get_details` - Get priority number details
- `get_queue_stats` - Get queue statistics
- `get_upcoming` - Get upcoming priority numbers
- `cancel` - Cancel priority number
- `get_customer_history` - Get customer history
- `clear_expired` - Clear expired numbers (admin only)
- `reset_queue` - Reset queue status (admin only)

### Real-time Display (`ajax/priority_display.php`)

Returns current queue status for display screens.

## Security Features

- **CSRF Protection**: All forms protected with tokens
- **Role-based Access**: Different permissions for different user types
- **Input Validation**: All inputs sanitized and validated
- **SQL Injection Prevention**: Prepared statements used throughout
- **Session Management**: Secure session handling
- **Audit Logging**: Complete action logging

## Performance Optimization

- **Database Indexes**: Optimized for fast queries
- **Caching**: Real-time data caching
- **Efficient Queries**: Optimized SQL statements
- **Connection Pooling**: Database connection management

## Troubleshooting

### Common Issues

1. **Database Connection Error**
   - Check database credentials in `config/database.php`
   - Ensure MySQL/MariaDB is running
   - Verify database permissions

2. **Priority Numbers Not Generating**
   - Check if tables exist
   - Verify system settings
   - Check for PHP errors

3. **Real-time Display Not Updating**
   - Check AJAX endpoint accessibility
   - Verify JavaScript is enabled
   - Check browser console for errors

4. **Permission Errors**
   - Verify user roles and permissions
   - Check session status
   - Ensure proper authentication

### Debug Mode

Enable debug mode in `config/config.php`:
```php
error_reporting(E_ALL);
ini_set('display_errors', 1);
```

## Maintenance

### Daily Tasks
- Monitor queue status
- Clear expired numbers
- Check system performance

### Weekly Tasks
- Review queue statistics
- Update system settings if needed
- Backup database

### Monthly Tasks
- Analyze performance metrics
- Update system if needed
- Review audit logs

## Support

For technical support or questions:
1. Check the troubleshooting section
2. Review system logs
3. Contact system administrator
4. Check database for errors

## Version History

- **v1.0.0** - Initial release with core functionality
  - Priority number generation
  - Queue management
  - Real-time display
  - Admin interface
  - Customer interface

## License

This system is part of the SOCOTECO II Billing Management System and is proprietary software.

---

**Note**: This system is designed specifically for SOCOTECO II's electricity billing operations. Ensure proper testing before deploying to production environment.
