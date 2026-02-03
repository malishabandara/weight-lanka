# Weight Lanka – License & Renewal Monitor (PHP + MySQL)

Simple, user-friendly system to manage **scale licenses** and **1-year renewals**, with a home screen that gives quick access to all modules.

## Requirements

- XAMPP (Apache + MySQL + PHP)
- phpMyAdmin (included with XAMPP) or MySQL CLI

## Setup (XAMPP)

- **Step 1**: Copy this project to:
  - `C:\xampp\htdocs\weightlanka`

- **Step 2**: Create the database + tables + sample data
  - Open phpMyAdmin: `http://localhost/phpmyadmin`
  - Go to **Import**
  - Import: `db/weightlanka.sql`

- **Step 3**: Configure DB connection (if needed)
  - Edit `config.php` and set your MySQL credentials.
  - Default (XAMPP): host `127.0.0.1`, db `weightlanka`, user `root`, password empty.

- **Step 4**: Open the system
  - `http://localhost/weightlanka/`

## Main Features

- **Home**: eye-catching icon navigation (quick access to every section)
- **Licenses**: list all licenses and renew quickly
- **Due Monitor**: select a month and view all licenses expiring in that month
- **Renewal History**: view previous renewals for tracking
- **Customers / Scales**: basic management pages
- **1-year rule**: expiry is always calculated as `service_date + 1 year`
## Notes

- This project currently focuses on **license + renewal monitoring** only (no login/sales modules yet).

### Managing Petty Cash
1. Go to **Petty Cash**
2. Click "New Transaction"
3. Select type (Income/Expense)
4. Enter details and amount
5. Save transaction

### Viewing Profit Reports
1. Go to **Profit Report**
2. Select date range
3. View profit breakdown and charts

## Features Highlights

- ✅ Modern, responsive UI with Bootstrap 5
- ✅ Beautiful icons from Bootstrap Icons
- ✅ DataTables for easy data management
- ✅ Automatic license expiry monitoring
- ✅ Profit calculation and reporting
- ✅ Stock management
- ✅ Date filtering and search
- ✅ Mobile-friendly design

## Security Notes

- Change default admin password
- Keep database credentials secure
- Regular database backups recommended
- Update PHP and MySQL regularly

## Support

For issues or questions, please refer to the code comments or contact your system administrator.

## License

This system is developed for Weight Lanka Company.

---

**Version:** 1.0  
**Last Updated:** 2025
