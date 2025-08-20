# Learn Here Free - PHP Version

A complete learning management system built with PHP, MySQL, and modern web technologies. This version is designed to be easily deployable on shared hosting platforms.

## 🚀 Features

- **Modern UI/UX**: Clean, responsive design using Tailwind CSS
- **Video Learning**: YouTube video integration with custom player
- **Batch Management**: Organize content into batches and subjects
- **Admin Dashboard**: Complete content management system
- **Mobile Responsive**: Works perfectly on all devices
- **No Build Process**: Direct deployment on shared hosting
- **Demo Mode**: Works without database for testing

## 📋 Requirements

- PHP 7.4 or higher
- MySQL 5.7 or higher (optional - works in demo mode)
- Web server (Apache/Nginx)
- Modern web browser

## 🛠️ Installation

### Option 1: Shared Hosting (Recommended)

1. **Upload Files**: Upload all files to your web hosting root directory
2. **Database Setup** (Optional):
   - Create a MySQL database
   - Import `database/schema.sql` to create tables
   - Update database credentials in `config/database.php`
3. **Access Website**: Visit your domain to start using

### Option 2: Local Development

1. **Setup Local Server**:
   ```bash
   # Using XAMPP/WAMP/MAMP
   # Copy files to htdocs/www folder
   ```

2. **Database Setup**:
   ```sql
   -- Create database
   CREATE DATABASE learnherefree;
   
   -- Import schema
   mysql -u root -p learnherefree < database/schema.sql
   ```

3. **Configure Database**:
   - Edit `config/database.php`
   - Update connection details

## 📁 File Structure

```
php-version/
├── index.php              # Main entry point
├── config/
│   └── database.php       # Database configuration
├── includes/
│   ├── functions.php      # Helper functions
│   ├── header.php         # Site header
│   └── footer.php         # Site footer
├── pages/
│   ├── home.php           # Home page
│   ├── batch.php          # Batch subjects page
│   ├── subject.php        # Subject videos page
│   ├── video.php          # Video player page
│   ├── admin.php          # Admin dashboard
│   └── 404.php            # Error page
├── assets/
│   ├── css/
│   │   └── style.css      # Custom styles
│   ├── js/
│   │   └── main.js        # JavaScript functionality
│   └── images/            # Images and logos
├── database/
│   └── schema.sql         # Database schema
└── README.md              # This file
```

## 🎯 Usage

### For Students/Learners

1. **Browse Batches**: Visit homepage to see available learning batches
2. **Select Subject**: Click on a batch to view subjects
3. **Watch Videos**: Choose a subject to access video content
4. **Learn**: Enjoy high-quality educational videos

### For Administrators

1. **Access Admin**: Go to `/admin` or click Admin link
2. **View Statistics**: See total batches, subjects, and videos
3. **Manage Content**: Add/edit/delete batches, subjects, and videos
4. **Monitor Usage**: Track user engagement and content performance

## 🔧 Configuration

### Database Configuration

Edit `config/database.php`:

```php
$db_host = 'localhost';     // Database host
$db_name = 'learnherefree'; // Database name
$db_user = 'your_username'; // Database username
$db_pass = 'your_password'; // Database password
```

### Environment Variables (Optional)

For production, you can use environment variables:

```php
$db_host = getenv('DB_HOST') ?: 'localhost';
$db_name = getenv('DB_NAME') ?: 'learnherefree';
$db_user = getenv('DB_USER') ?: 'root';
$db_pass = getenv('DB_PASS') ?: '';
```

## 🎨 Customization

### Styling

- Edit `assets/css/style.css` for custom styles
- Modify Tailwind classes in PHP files
- Add your own color scheme and branding

### Content

- Update demo data in `config/database.php`
- Add your own videos and courses
- Customize navigation and layout

### Features

- Extend functionality in `includes/functions.php`
- Add new pages in `pages/` directory
- Enhance JavaScript in `assets/js/main.js`

## 🔒 Security Features

- **SQL Injection Protection**: Prepared statements
- **XSS Prevention**: Output sanitization
- **CSRF Protection**: Form tokens (can be added)
- **Input Validation**: Server-side validation
- **Error Handling**: Graceful error management

## 📱 Responsive Design

- **Mobile First**: Optimized for mobile devices
- **Tablet Friendly**: Responsive grid layouts
- **Desktop Optimized**: Full-featured desktop experience
- **Touch Friendly**: Optimized for touch interactions

## 🚀 Performance

- **CDN Resources**: Tailwind CSS and Font Awesome from CDN
- **Optimized Images**: Responsive image handling
- **Lazy Loading**: JavaScript-based lazy loading
- **Caching Ready**: Compatible with browser caching

## 🔧 Troubleshooting

### Common Issues

1. **Database Connection Error**:
   - Check database credentials in `config/database.php`
   - Ensure MySQL service is running
   - Verify database exists

2. **Page Not Found**:
   - Check `.htaccess` file (if using Apache)
   - Verify file permissions
   - Check web server configuration

3. **Styling Issues**:
   - Ensure CDN resources are loading
   - Check internet connection for CDN
   - Verify CSS file paths

### Demo Mode

If database is not available, the system automatically runs in demo mode with sample data.

## 📞 Support

For support and questions:
- Check this README file
- Review the code comments
- Test in demo mode first

## 🔄 Updates

To update the system:
1. Backup your current installation
2. Upload new files (except config)
3. Update database schema if needed
4. Test functionality

## 📄 License

This project is open source and available under the MIT License.

---

**Learn Here Free** - Making education accessible to everyone! 🎓
