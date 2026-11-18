# NoteApp

## Công nghệ

- **Backend:** PHP 8.2+, Laravel 12, Blade
- **Cơ sở dữ liệu:** SQLite (mặc định; Laravel cũng hỗ trợ cấu hình các hệ quản trị khác)
- **Frontend:** JavaScript, Sass, Bootstrap 5, Bootstrap Icons, jQuery
- **Build frontend:** Vite 7

## Tính năng hiện tại

- Đăng ký, đăng nhập và đăng xuất.
- Xác minh email; gửi lại email xác minh và yêu cầu đặt lại mật khẩu qua email.
- Chỉnh sửa tên, email và ảnh đại diện; xóa ảnh đại diện.
- Giao diện ghi chú hỗ trợ tìm kiếm, lọc theo nhãn/trạng thái chia sẻ, ghim/bỏ ghim, xóa ghi chú và chuyển chế độ lưới/danh sách.
- Chuyển giao diện sáng/tối; bản nháp trong cửa sổ tạo ghi chú được lưu trên trình duyệt.

> **Phạm vi hiện tại:** Ghi chú trên trang chính là dữ liệu mẫu. Các thao tác ghim, xóa và lọc chỉ thay đổi giao diện; ứng dụng chưa lưu ghi chú vào cơ sở dữ liệu. Bản nháp được lưu trong `localStorage` của trình duyệt.

## Các tệp chính

| Tệp | Vai trò |
|---|---|
| `routes/web.php` | Khai báo các route xác thực, xác minh email, đặt lại mật khẩu, hồ sơ và ảnh đại diện. |
| `app/Http/Controllers/AuthController.php` | Xử lý đăng ký, đăng nhập và đăng xuất. |
| `app/Http/Controllers/ProfileController.php` | Cập nhật thông tin tài khoản, mật khẩu và ảnh đại diện. |
| `app/Http/Controllers/PasswordController.php` | Gửi liên kết và xử lý đặt lại mật khẩu. |
| `app/Models/User.php` | Mô hình người dùng, xác minh email và thuộc tính ảnh đại diện. |
| `database/migrations/2026_11_18_054936_add_avatar_to_users_table.php` | Bổ sung cột lưu đường dẫn ảnh đại diện cho người dùng. |
| `resources/views/home.blade.php` | Giao diện chính và dữ liệu ghi chú mẫu. |
| `resources/js/home.js` | Xử lý tìm kiếm, lọc, ghim, xóa, đổi giao diện và lưu bản nháp trên trình duyệt. |
| `resources/sass/home.scss` | Định dạng giao diện trang ghi chú và thông báo xác minh email. |
| `resources/views/profile/edit.blade.php` | Giao diện chỉnh sửa hồ sơ và xem trước ảnh đại diện. |
| `resources/views/authentication/` | Các trang đăng nhập và đăng ký. |
| `resources/views/reset-password/` | Các trang yêu cầu liên kết và nhập mật khẩu mới. |
| `resources/views/emails/verify.blade.php` | Nội dung email xác minh tài khoản. |
| `vite.config.js`, `package.json`, `composer.json` | Cấu hình build frontend và khai báo các gói phụ thuộc. |

Các cập nhật giao diện gần đây tập trung ở `resources/views/home.blade.php` và `resources/sass/home.scss` (nút gửi lại email xác minh), `resources/views/profile/edit.blade.php` (xem trước ảnh đại diện), cùng `routes/web.php` (khai báo route).

## Cài đặt và chạy

Yêu cầu PHP 8.2+, Composer, Node.js và npm.

```powershell
composer install
Copy-Item .env.example .env
php artisan key:generate
php artisan migrate
php artisan storage:link
npm install
npm run build
php artisan serve
```

Để gửi email xác minh và đặt lại mật khẩu, cấu hình thông tin mail trong tệp `.env` trước khi sử dụng các chức năng này.
