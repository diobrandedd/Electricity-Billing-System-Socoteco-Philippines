# SOCOTECO II Billing Management System

A comprehensive billing management system designed specifically for Philippine electric cooperatives, featuring customer management, meter reading tracking, automated billing, payment processing, and detailed reporting.

## Features

### 🔐 User Management
- **Role-based Access Control**: Admin, Cashier, Meter Reader, and Customer roles
- **Secure Authentication**: Password-protected login system
- **User Activity Tracking**: Complete audit trail of all system activities

### 👥 Customer Management
- **Customer Registration**: Complete customer profiles with contact information
- **Customer Categories**: Residential, Commercial, Industrial, and Government classifications
- **Address Management**: Barangay, municipality, and province tracking
- **Account & Meter Numbers**: Unique identifiers for each customer

### 📊 Meter Reading & Consumption
- **Manual Reading Entry**: Staff can input monthly meter readings
- **Automatic Consumption Calculation**: Current reading minus previous reading
- **Reading Types**: Actual, Estimated, and Adjusted readings
- **Reading History**: Complete tracking of all meter readings

### 💰 Billing & Invoicing
- **Automated Bill Generation**: Based on meter readings and current rates
- **Detailed Bill Breakdown**: Generation, Distribution, Transmission, System Loss charges
- **VAT Calculation**: Automatic 12% VAT computation
- **Due Date Management**: Configurable payment due dates
- **Bill Status Tracking**: Pending, Paid, Overdue, Cancelled

### 💳 Payment Management
- **Payment Processing**: Record cash, check, bank transfer, and online payments
- **Partial Payments**: Support for multiple payments on single bills
- **Official Receipts**: Automatic OR number generation
- **Payment History**: Complete payment tracking per customer
- **Balance Tracking**: Real-time outstanding balance calculation

### 📈 Reports & Analytics
- **Collection Reports**: Daily, monthly, and custom date range reports
- **Aging Reports**: Outstanding bills categorized by days overdue
- **Revenue Reports**: Monthly revenue trends and analysis
- **Usage Reports**: Consumption patterns by location and customer category
- **Customer Reports**: Distribution and statistics by customer type

### 🎨 Modern User Interface
- **Responsive Design**: Works on desktop, tablet, and mobile devices
- **Bootstrap 5**: Modern, professional styling
- **Interactive Charts**: Visual data representation using Chart.js
- **DataTables**: Advanced table features with search, sort, and pagination
- **Print-Ready**: Professional bill and receipt printing

## System Requirements

### Server Requirements
- **PHP**: Version 7.4 or higher
- **MySQL**: Version 5.7 or higher
- **Web Server**: Apache or Nginx
- **Extensions**: PDO, PDO_MySQL, JSON, Session

### Recommended Environment
- **XAMPP**: For local development (Windows/Mac/Linux)
- **WAMP**: For Windows development
- **LAMP**: For Linux production servers

## Installation Guide

### Step 1: Download and Setup
1. Download the system files to your web server directory
2. For XAMPP: Place files in `C:\xampp\htdocs\socotecoSys\`
3. For WAMP: Place files in `C:\wamp64\www\socotecoSys\`

### Step 2: Database Setup
1. Start your web server (Apache) and database (MySQL)
2. Open phpMyAdmin: `http://localhost/phpmyadmin`
3. Create a new database named `socoteco_billing`
4. Import the database schema:
   - Go to the "Import" tab
   - Choose the file `database/schema.sql`
   - Click "Go" to import

### Step 3: Configuration
1. Open `config/database.php`
2. Update database connection settings if needed:
   ```php
   private $host = 'localhost';
   private $db_name = 'socoteco_billing';
   private $username = 'root';
   private $password = '';
   ```

### Step 4: Access the System
1. Open your web browser
2. Navigate to: `http://localhost/socotecoSys/auth/login.php`
3. Use default admin credentials:
   - **Username**: `admin`
   - **Password**: `admin123`

### Step 5: Initial Setup
1. **Change Admin Password**: Go to Settings → User Management
2. **Configure System Settings**: Update company information and rates
3. **Add Customer Categories**: Verify or modify customer categories
4. **Create User Accounts**: Add cashier and meter reader accounts

## Default System Settings

### Customer Categories
- **Residential**: Base rate ₱8.50/kWh
- **Commercial**: Base rate ₱9.20/kWh
- **Industrial**: Base rate ₱8.80/kWh
- **Government**: Base rate ₱8.00/kWh

### Billing Rates (per kWh)
- **Generation Charge**: ₱4.50
- **Distribution Charge**: ₱1.20
- **Transmission Charge**: ₱0.80
- **System Loss Charge**: ₱0.50
- **VAT**: 12%

### System Configuration
- **Due Days**: 15 days from bill date
- **Penalty Rate**: 2% per month for overdue bills

## User Roles & Permissions

### 👑 Administrator
- Full system access
- User management
- System settings
- All reports and analytics
- Customer management
- Billing and payment processing

### 💰 Cashier
- Customer management
- Payment processing
- Bill viewing and printing
- Customer information access

### 📊 Meter Reader
- Meter reading entry
- Reading history viewing
- Customer information access

### 👤 Customer (Future Feature)
- View own bills
- Payment history
- Account information

## File Structure

```
socotecoSys/
├── auth/                    # Authentication files
│   ├── login.php
│   └── logout.php
├── config/                  # Configuration files
│   ├── config.php
│   └── database.php
├── database/                # Database files
│   └── schema.sql
├── includes/                # Common includes
│   ├── header.php
│   └── footer.php
├── ajax/                    # AJAX endpoints
│   ├── get_previous_reading.php
│   └── get_bill_details.php
├── dashboard.php            # Main dashboard
├── customers.php            # Customer management
├── customer_details.php     # Customer details view
├── meter_readings.php       # Meter reading management
├── bills.php                # Billing management
├── bill_details.php         # Bill details view
├── bill_print.php           # Bill printing
├── payments.php             # Payment management
├── payment_receipt.php      # Payment receipt printing
├── reports.php              # Reports and analytics
└── README.md               # This file
```

## Key Features Explained

### Automated Billing Process
1. **Meter Reading Entry**: Staff records monthly meter readings
2. **Bill Generation**: System automatically calculates consumption and charges
3. **Bill Distribution**: Bills are generated with unique bill numbers
4. **Payment Processing**: Cashiers process payments and generate receipts
5. **Status Updates**: Bill status automatically updates based on payments

### Rate Calculation
The system uses a tiered rate structure:
- **Base Rate**: Varies by customer category
- **Additional Charges**: Generation, Distribution, Transmission, System Loss
- **VAT**: 12% on total charges
- **Penalties**: 2% monthly for overdue bills

### Security Features
- **CSRF Protection**: All forms protected against cross-site request forgery
- **SQL Injection Prevention**: Prepared statements for all database queries
- **Session Management**: Secure session handling
- **Input Sanitization**: All user inputs are sanitized
- **Audit Trail**: Complete logging of all system activities

## Troubleshooting

### Common Issues

#### Database Connection Error
- Check if MySQL is running
- Verify database credentials in `config/database.php`
- Ensure database `socoteco_billing` exists

#### Login Issues
- Verify default credentials: admin/admin123
- Check if user account is active
- Clear browser cache and cookies

#### Permission Errors
- Ensure web server has write permissions to upload directories
- Check file permissions (755 for directories, 644 for files)

#### Bill Generation Issues
- Verify meter readings exist for the billing period
- Check if bill already exists for the reading
- Ensure customer is active

### Support
For technical support or feature requests, please contact the system administrator.

## Future Enhancements

### Planned Features
- **SMS Notifications**: Automated bill reminders via SMS
- **Email Integration**: Email bill delivery and notifications
- **Online Payment Gateway**: Integration with payment processors
- **Mobile App**: Customer mobile application
- **API Integration**: RESTful API for third-party integrations
- **Advanced Analytics**: Machine learning for consumption prediction
- **Document Management**: Digital document storage and retrieval

### Customization Options
- **Multi-language Support**: Filipino and English language options
- **Custom Rate Structures**: Flexible rate configuration
- **Additional Report Types**: Custom report generation
- **Integration APIs**: Connect with existing systems

## License

This system is developed for SOCOTECO II and is proprietary software. Unauthorized distribution or modification is prohibited.

## Version History

### Version 1.0.0 (Current)
- Initial release
- Complete billing management system
- User authentication and role management
- Customer, billing, and payment modules
- Comprehensive reporting system
- Modern responsive UI

---

**Developed for SOCOTECO II Electric Cooperative**  
*Empowering Philippine electric cooperatives with modern billing solutions*
