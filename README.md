## Hướng dẫn Setup, chạy Project
1. Tạo Database trên http://localhost/phpmyadmin (eg. uniideas, với kiểu utf8mb4_unicode_ci - gần cuối modal)
2. composer install
3. composer require spatie/laravel-activitylog
php artisan vendor:publish --provider="Spatie\Activitylog\ActivitylogServiceProvider" --tag="activitylog-config"
4. Chạy lệnh tạo Database: php artisan migrate (chạy lệnh seeding data nếu có setup)
