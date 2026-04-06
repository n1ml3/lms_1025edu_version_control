# Sử dụng PHP 8.2 đi kèm Apache
FROM php:8.2-apache

# Cài đặt các extension để PHP kết nối được với MySQL/MariaDB
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Kích hoạt mod_rewrite (dành cho URL thân thiện nếu dùng .htaccess)
RUN a2enmod rewrite

# Cấp quyền cơ bản cho thư mục web (giúp tránh lỗi permission khi upload file/ảnh)
RUN chown -R www-data:www-data /var/www/html