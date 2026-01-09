# 🚀 Deployment Guide - Kataraksa

## 📋 Informasi Dokumen
| Item | Detail |
|------|--------|
| **Project** | Kataraksa - Sistem Perpustakaan Digital |
| **Framework** | CodeIgniter 4.6.4 |
| **PHP Version** | 8.1+ (Recommended: 8.3) |
| **Database** | MySQL 5.7+ / MariaDB 10.4+ |

---

## 🏠 Local Development (Windows)

### Option A: Laragon (Recommended)

**1. Install Laragon**
```
Download: https://laragon.org/download/
```

**2. Enable PHP Extensions**
Edit `php.ini` (lokasi: `C:\laragon\bin\php\php-X.X.X\php.ini`):
```ini
extension=intl
extension=mysqli
extension=pdo_mysql
extension=mbstring
extension=curl
extension=openssl
extension=gd
```

**3. Clone & Setup Project**
```bash
cd C:\laragon\www
git clone <repository-url> kataraksa
cd kataraksa
composer install
```

**4. Configure Environment**
```bash
copy env .env
```

Edit `.env`:
```ini
CI_ENVIRONMENT = development

app.baseURL = 'http://kataraksa.test/'

database.default.hostname = localhost
database.default.database = kataraksa
database.default.username = root
database.default.password = 
database.default.DBDriver = MySQLi
```

**5. Create Database & Migrate**
```bash
# Buat database via HeidiSQL/phpMyAdmin
# Nama: kataraksa

php spark migrate
php spark db:seed DatabaseSeeder
```

**6. Access**
```
http://kataraksa.test/
```

---

### Option B: XAMPP

**1. Install XAMPP**
```
Download: https://www.apachefriends.org/
```

**2. Enable PHP Extensions**
Edit `C:\xampp\php\php.ini`:
```ini
extension=intl
extension=mysqli
```

**3. Setup Project**
```bash
cd C:\xampp\htdocs
git clone <repository-url> kataraksa
cd kataraksa
composer install
```

**4. Configure `.env`**
```ini
app.baseURL = 'http://localhost/kataraksa/public/'
```

**5. Access**
```
http://localhost/kataraksa/public/
```

---

## 🌐 Shared Hosting (cPanel)

### Requirements
- PHP 8.1+ dengan extension: intl, mysqli, mbstring, curl
- MySQL 5.7+
- File Manager / FTP Access

### Step-by-Step

**1. Prepare Files**
```bash
# Di local, install dependencies
composer install --no-dev --optimize-autoloader
```

**2. Upload Files**
Upload semua file ke `public_html/` atau subdomain folder.

**3. Restructure for Shared Hosting**
```
public_html/
├── app/
├── system/              # dari vendor/codeigniter4/framework/system
├── vendor/
├── writable/
├── .env
├── .htaccess            # dari public/.htaccess
├── index.php            # dari public/index.php (EDIT PATHS!)
├── favicon.ico
└── uploads/
```

**4. Edit `index.php`**
```php
<?php
// Ubah paths
$pathsConfig = __DIR__ . '/app/Config/Paths.php';

// Change FCPATH
define('FCPATH', __DIR__ . DIRECTORY_SEPARATOR);
```

**5. Edit `app/Config/Paths.php`**
```php
public string $systemDirectory = __DIR__ . '/../../system';
public string $appDirectory = __DIR__ . '/../';
public string $writableDirectory = __DIR__ . '/../../writable';
```

**6. Create Database**
- Login cPanel → MySQL Databases
- Create database: `username_kataraksa`
- Create user & assign privileges

**7. Configure `.env`**
```ini
CI_ENVIRONMENT = production

app.baseURL = 'https://yourdomain.com/'
app.forceGlobalSecureRequests = true

database.default.hostname = localhost
database.default.database = username_kataraksa
database.default.username = username_dbuser
database.default.password = your_secure_password
database.default.DBDriver = MySQLi
```

**8. Run Migrations**
Via SSH atau buat temporary PHP file:
```php
<?php
// migrate.php - HAPUS SETELAH SELESAI!
require_once 'vendor/autoload.php';
$app = \Config\Services::codeigniter();
$migrate = \Config\Services::migrations();
$migrate->latest();
echo "Migration complete!";
```

**9. Set Permissions**
```bash
chmod 755 -R app/
chmod 777 -R writable/
chmod 755 -R public/uploads/
```

---

## ☁️ VPS Deployment (Ubuntu/Debian)

### Requirements
- Ubuntu 20.04+ / Debian 11+
- Nginx atau Apache
- PHP 8.1+ FPM
- MySQL 8.0 / MariaDB 10.4+
- Composer
- Git

### Step 1: Install Dependencies

```bash
# Update system
sudo apt update && sudo apt upgrade -y

# Install PHP & Extensions
sudo apt install -y php8.3-fpm php8.3-mysql php8.3-mbstring \
    php8.3-xml php8.3-curl php8.3-intl php8.3-gd php8.3-zip

# Install MySQL
sudo apt install -y mysql-server

# Install Nginx
sudo apt install -y nginx

# Install Composer
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer

# Install Git
sudo apt install -y git
```

### Step 2: Configure MySQL

```bash
sudo mysql_secure_installation

sudo mysql -u root -p
```

```sql
CREATE DATABASE kataraksa CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'kataraksa_user'@'localhost' IDENTIFIED BY 'StrongPassword123!';
GRANT ALL PRIVILEGES ON kataraksa.* TO 'kataraksa_user'@'localhost';
FLUSH PRIVILEGES;
EXIT;
```

### Step 3: Clone & Setup Project

```bash
cd /var/www
sudo git clone <repository-url> kataraksa
cd kataraksa

sudo composer install --no-dev --optimize-autoloader

sudo cp env .env
sudo nano .env
```

Edit `.env`:
```ini
CI_ENVIRONMENT = production

app.baseURL = 'https://yourdomain.com/'
app.forceGlobalSecureRequests = true

database.default.hostname = localhost
database.default.database = kataraksa
database.default.username = kataraksa_user
database.default.password = StrongPassword123!
database.default.DBDriver = MySQLi
```

### Step 4: Set Permissions

```bash
sudo chown -R www-data:www-data /var/www/kataraksa
sudo chmod -R 755 /var/www/kataraksa
sudo chmod -R 777 /var/www/kataraksa/writable
```

### Step 5: Configure Nginx

```bash
sudo nano /etc/nginx/sites-available/kataraksa
```

```nginx
server {
    listen 80;
    listen [::]:80;
    server_name yourdomain.com www.yourdomain.com;
    root /var/www/kataraksa/public;
    index index.php index.html;

    # Security Headers
    add_header X-Frame-Options "SAMEORIGIN" always;
    add_header X-Content-Type-Options "nosniff" always;
    add_header X-XSS-Protection "1; mode=block" always;

    # Logs
    access_log /var/log/nginx/kataraksa_access.log;
    error_log /var/log/nginx/kataraksa_error.log;

    location / {
        try_files $uri $uri/ /index.php$is_args$args;
    }

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.3-fpm.sock;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    # Deny access to sensitive files
    location ~ /\. {
        deny all;
    }

    location ~ /(app|system|writable|vendor)/ {
        deny all;
    }

    # Cache static files
    location ~* \.(jpg|jpeg|png|gif|ico|css|js|woff|woff2)$ {
        expires 30d;
        add_header Cache-Control "public, immutable";
    }
}
```

```bash
sudo ln -s /etc/nginx/sites-available/kataraksa /etc/nginx/sites-enabled/
sudo nginx -t
sudo systemctl reload nginx
```

### Step 6: SSL Certificate (Let's Encrypt)

```bash
sudo apt install -y certbot python3-certbot-nginx
sudo certbot --nginx -d yourdomain.com -d www.yourdomain.com
```

### Step 7: Run Migrations

```bash
cd /var/www/kataraksa
php spark migrate
php spark db:seed DatabaseSeeder
```

### Step 8: Setup Cron (Optional)

```bash
sudo crontab -e
```

```cron
# Update overdue status daily at midnight
0 0 * * * cd /var/www/kataraksa && php spark task:overdue >> /dev/null 2>&1
```

---

## 🐳 Docker Deployment

### Project Structure
```
kataraksa/
├── docker/
│   ├── nginx/
│   │   └── default.conf
│   └── php/
│       └── Dockerfile
├── docker-compose.yml
└── ... (project files)
```

### docker-compose.yml

```yaml
version: '3.8'

services:
  app:
    build:
      context: .
      dockerfile: docker/php/Dockerfile
    container_name: kataraksa_app
    restart: unless-stopped
    working_dir: /var/www
    volumes:
      - ./:/var/www
      - ./docker/php/local.ini:/usr/local/etc/php/conf.d/local.ini
    networks:
      - kataraksa_network
    depends_on:
      - db

  nginx:
    image: nginx:alpine
    container_name: kataraksa_nginx
    restart: unless-stopped
    ports:
      - "8080:80"
    volumes:
      - ./:/var/www
      - ./docker/nginx/default.conf:/etc/nginx/conf.d/default.conf
    networks:
      - kataraksa_network
    depends_on:
      - app

  db:
    image: mysql:8.0
    container_name: kataraksa_db
    restart: unless-stopped
    environment:
      MYSQL_DATABASE: kataraksa
      MYSQL_ROOT_PASSWORD: root_password
      MYSQL_USER: kataraksa_user
      MYSQL_PASSWORD: kataraksa_password
    ports:
      - "3307:3306"
    volumes:
      - kataraksa_dbdata:/var/lib/mysql
    networks:
      - kataraksa_network

  phpmyadmin:
    image: phpmyadmin:latest
    container_name: kataraksa_pma
    restart: unless-stopped
    ports:
      - "8081:80"
    environment:
      PMA_HOST: db
      MYSQL_ROOT_PASSWORD: root_password
    networks:
      - kataraksa_network
    depends_on:
      - db

networks:
  kataraksa_network:
    driver: bridge

volumes:
  kataraksa_dbdata:
```

### docker/php/Dockerfile

```dockerfile
FROM php:8.3-fpm

# Install dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libicu-dev \
    zip \
    unzip

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-configure intl
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd intl

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www

# Copy project
COPY . /var/www

# Install dependencies
RUN composer install --no-dev --optimize-autoloader

# Set permissions
RUN chown -R www-data:www-data /var/www
RUN chmod -R 755 /var/www
RUN chmod -R 777 /var/www/writable

EXPOSE 9000
CMD ["php-fpm"]
```

### docker/nginx/default.conf

```nginx
server {
    listen 80;
    server_name localhost;
    root /var/www/public;
    index index.php;

    location / {
        try_files $uri $uri/ /index.php$is_args$args;
    }

    location ~ \.php$ {
        fastcgi_pass app:9000;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\. {
        deny all;
    }
}
```

### Docker Commands

```bash
# Build & Start
docker-compose up -d --build

# Run Migrations
docker-compose exec app php spark migrate
docker-compose exec app php spark db:seed DatabaseSeeder

# View Logs
docker-compose logs -f

# Stop
docker-compose down

# Stop & Remove Volumes
docker-compose down -v
```

### Access
- **App:** http://localhost:8080
- **phpMyAdmin:** http://localhost:8081

---

## 🔐 Production Checklist

### Security
- [ ] Set `CI_ENVIRONMENT = production`
- [ ] Enable `app.forceGlobalSecureRequests = true`
- [ ] Enable CSRF protection in `Filters.php`
- [ ] Set strong database password
- [ ] Configure SSL/HTTPS
- [ ] Set proper file permissions (755 for folders, 644 for files)
- [ ] Remove development files (`.git`, `tests/`, etc.)

### Performance
- [ ] Run `composer install --no-dev --optimize-autoloader`
- [ ] Enable OPcache in PHP
- [ ] Configure Nginx/Apache caching
- [ ] Enable gzip compression

### Monitoring
- [ ] Setup error logging
- [ ] Configure log rotation
- [ ] Setup uptime monitoring
- [ ] Backup database regularly

---

## 📞 Troubleshooting

### Common Issues

**1. 500 Internal Server Error**
```bash
# Check logs
tail -f /var/www/kataraksa/writable/logs/log-*.log
tail -f /var/log/nginx/error.log
```

**2. Permission Denied**
```bash
sudo chown -R www-data:www-data /var/www/kataraksa
sudo chmod -R 777 /var/www/kataraksa/writable
```

**3. Class "Locale" not found**
```bash
# Install intl extension
sudo apt install php8.3-intl
sudo systemctl restart php8.3-fpm
```

**4. Database Connection Error**
- Check `.env` database credentials
- Verify MySQL is running: `sudo systemctl status mysql`
- Test connection: `mysql -u user -p database`

---

*Happy Deploying!* 🚀
