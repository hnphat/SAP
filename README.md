# SAP
migrate one table
Example: php artisan migrate --path=database/migrations/2022_02_21_144955_nhat_ky.php

# nạp nhanh SQL file vào database
mysql -u root -p test < "C:\Users\Admin\Downloads\10h0004072026.sql"

# Neu dung VS tren ubuntu thi
Example: /opt/lampp/bin/php artisan migrate --path=database/migrations/2022_02_21_144955_nhat_ky.php

# Trên ubuntu để chạy php artisan chạy code bên dưới trên terminal trước vì php của ubuntu và php trên laravel xung đột nhau
alias php="/opt/lampp/bin/php"