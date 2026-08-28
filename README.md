
# KCIC Sales Recap

## Deskripsi
ikuti step by stepp
## Prasyarat
Pastikan Anda memiliki perangkat lunak berikut:
- PHP 8.0 atau lebih tinggi
- Composer
- MySQL
- Laravel 10

## Langkah-langkah Instalasi

### 1. Clone Repository
Clone proyek ini ke komputer lokal menggunakan perintah berikut:

```bash
git clone https://github.com/Teguhdl/kcic-sales-recap.git kcicsalesrecap
```
### 2. Buat Database Secara Manual
Sebelum menjalankan migrasi, pastikan database `kcic_sales` sudah dibuat di server MySQL.

buat database secara manual menggunakan perintah SQL berikut di MySQL:

```sql
CREATE DATABASE kcic_sales;
```

### 3. Menyiapkan Koneksi Database
Pastikan telah menyiapkan database MySQL di mesin lokal Anda dengan pengaturan yang sesuai. Berikut adalah konfigurasi default untuk file `.env`:

```env
APP_NAME=Laravel
APP_ENV=local
APP_KEY=base64:TMfJ/ZFE5vkphEX2XKx93eifx03Y6H5xx5ERWMGYAvI=
APP_DEBUG=true
APP_URL=http://localhost

LOG_CHANNEL=stack
LOG_DEPRECATIONS_CHANNEL=null
LOG_LEVEL=debug

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=kcic_sales
DB_USERNAME=root
DB_PASSWORD=

BROADCAST_DRIVER=log
CACHE_DRIVER=file
FILESYSTEM_DISK=local
QUEUE_CONNECTION=sync
SESSION_DRIVER=file
SESSION_LIFETIME=120

MEMCACHED_HOST=127.0.0.1

REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379

MAIL_MAILER=smtp
MAIL_HOST=mailpit
MAIL_PORT=1025
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS="hello@example.com"
MAIL_FROM_NAME="${APP_NAME}"

AWS_ACCESS_KEY_ID=
AWS_SECRET_ACCESS_KEY=
AWS_DEFAULT_REGION=us-east-1
AWS_BUCKET=
AWS_USE_PATH_STYLE_ENDPOINT=false

PUSHER_APP_ID=
PUSHER_APP_KEY=
PUSHER_APP_SECRET=
PUSHER_HOST=
PUSHER_PORT=443
PUSHER_SCHEME=https
PUSHER_APP_CLUSTER=mt1

VITE_PUSHER_APP_KEY="${PUSHER_APP_KEY}"
VITE_PUSHER_HOST="${PUSHER_HOST}"
VITE_PUSHER_PORT="${PUSHER_PORT}"
VITE_PUSHER_SCHEME="${PUSHER_SCHEME}"
VITE_PUSHER_APP_CLUSTER="${PUSHER_APP_CLUSTER}"
```

### 4. Generate New Key
```bash
php artisan key:generate
```

### 5. Install Dependencies
jalankan perintah berikut untuk menginstall semua dependensi yang diperlukan:

```bash
composer install
```

### 6. Jalankan Migration dan Seeder
Setelah dependensi terinstall, jalankan migration dan seeder untuk membuat tabel yang diperlukan di database:

```bash
php artisan migrate --seed
```

Ini akan membuat tabel dan mengisi data awal ke dalam database sesuai dengan seeder yang telah disiapkan.

### 7. Menjalankan Aplikasi
Sekarang jalankan aplikasi menggunakan server built-in Laravel:

```bash
php artisan serve
```

Aplikasi akan dapat diakses di browser melalui URL berikut:

```
http://localhost:8000
```

Atau bisa langsung melalui URL:

```
http://localhost/kcicsalesrecap/public/login
```


### 8. Login Admin

```bash
EMAIL : admin@example.com
PASSWORD : password123
```


