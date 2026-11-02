# GMHS Assistant — Deploy demo (frontend Vercel + backend VPS)

Read this top to bottom, run each block in order.

## 0. Chuẩn bị

- Repo đã merge PR `feature/auth` vào nhánh mặc định (`master`).
- Có 1 VPS Ubuntu (2GB RAM) với domain/subdomain trỏ về, ví dụ `api.gmhs-demo.xyz`.
- Có tài khoản Vercel (deploy frontend free).
- Máy local giữ nguyên để build/test.

## 1. Backend lên VPS (Laravel + MySQL + HTTPS)

SSH vào VPS, chạy từng lệnh, đợi xong mới sang lệnh tiếp.

```bash
# 1.1 Cài đặt
sudo apt update && sudo apt install -y nginx mysql-server php8.3 php8.3-fpm php8.3-mysql php8.3-mbstring php8.3-xml php8.3-bcmath php8.3-curl php8.3-zip unzip git certbot python3-certbot-nginx
php -v   # phải ra 8.3.x mới đi tiếp

# 1.2 MySQL: tạo DB + user (nhớ đổi mat-khau-manh)
sudo mysql -e "CREATE DATABASE gmhs_assistant CHARACTER SET utf8mb4; CREATE USER 'gmhs'@'localhost' IDENTIFIED BY 'mat-khau-manh'; GRANT ALL ON gmhs_assistant.* TO 'gmhs'@'localhost'; FLUSH PRIVILEGES;"

# 1.3 Lấy code
cd /var/www && sudo git clone https://github.com/duykhanh465/GMHS_ASSISTANT.git gmhs && cd gmhs/backend
sudo composer install --no-dev --optimize-autoloader

# 1.4 File .env production (KHÔNG commit file này)
cp .env.example .env
# Sửa các dòng sau trong .env:
#   APP_ENV=production
#   APP_DEBUG=false
#   APP_URL=https://api.gmhs-demo.xyz
#   FRONTEND_URL=https://gmhs-demo.vercel.app
#   DB_CONNECTION=mysql
#   DB_HOST=127.0.0.1
#   DB_DATABASE=gmhs_assistant
#   DB_USERNAME=gmhs
#   DB_PASSWORD=mat-khau-manh
php artisan key:generate
php artisan migrate --force
php artisan db:seed --force
php artisan config:cache && php artisan route:cache

# 1.5 Quyền thư mục
sudo chown -R www-data:www-data /var/www/gmhs/backend/storage /var/www/gmhs/backend/bootstrap/cache
```

Nginx cho API (`/etc/nginx/sites-available/gmhs-api`), thay `api.gmhs-demo.xyz` bằng domain thật:

```nginx
server {
    listen 80;
    server_name api.gmhs-demo.xyz;
    root /var/www/gmhs/backend/public;
    index index.php;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        include snippets/fastcgi-php.conf;
        fastcgi_pass unix:/run/php/php8.3-fpm.sock;
    }
}
```

```bash
sudo ln -s /etc/nginx/sites-available/gmhs-api /etc/nginx/sites-enabled/
sudo nginx -t && sudo systemctl reload nginx

# 1.6 HTTPS (bắt buộc vì PWA + login token)
sudo certbot --nginx -d api.gmhs-demo.xyz
curl https://api.gmhs-demo.xyz/up   # phải ra 200 OK
```

Kiểm tra API sống:

```bash
curl -X POST https://api.gmhs-demo.xyz/api/login \
  -H 'Content-Type: application/json' \
  -d '{"email":"doctor@gmhs.test","password":"password"}'
# phải trả về token + user
```

## 2. Frontend lên Vercel

```bash
cd frontend
npm run build   # phải pass mới deploy
```

Trên vercel.com → Add New → Project → import repo `GMHS_ASSISTANT`:

- Root Directory: `frontend`
- Framework: Vite (tự nhận)
- Environment variable: `VITE_API_URL=https://api.gmhs-demo.xyz/api`
- Deploy.

Vercel cấp domain `https://gmhs-demo.vercel.app`. Mở trên điện thoại, Add to Home Screen để thử PWA.

## 3. Sau deploy kiểm tra theo checklist này

1. Mở frontend → bị đá về `/login` (đúng).
2. Login `doctor@gmhs.test / password` → vào Tổng quan.
3. Tạo case DEMO-001 (nữ, 80t, 150cm, 65kg, ASA II) → Tính toán → khớp số vàng (BMI 28.9, PBW 43.3, Vt 260–303).
4. Đổi sang tiếng Anh → Tài liệu tham khảo hiện tên tiếng Anh.
5. Login `admin@gmhs.test / password` → thấy menu Quản trị.
6. F5 → phải login lại (sessionStorage).

## 4. Lỗi hay gặp

| Hiện tượng | Nguyên nhân | Sửa |
|---|---|---|
| CORS blocked | `FRONTEND_URL` trong `.env` sai domain Vercel | Sửa `.env`, chạy `php artisan config:cache` |
| 500 khi login | Chưa migrate/seed hoặc sai DB password | `php artisan migrate --force`, kiểm tra `.env` |
| 404 mọi route API | Nginx chưa trỏ về `public/`, thiếu `try_files` | Sửa file nginx như mẫu trên |
| PWA không cài được | Thiếu HTTPS | Chạy certbot, cả API và frontend đều https |

## 5. An toàn demo

- Chỉ giữ tài khoản demo và case DEMO-001. Không nhập tên, CCCD, SĐT bệnh nhân thật.
- Demo xong muốn reset: `php artisan migrate:fresh --seed --force` (CHỈ trên VPS demo, không chạy trên máy có dữ liệu thật).
