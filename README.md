# NoteApp

## Công nghệ

- **Backend:** PHP 8.2+, Laravel 12, Blade
- **Cơ sở dữ liệu:** SQLite mặc định; migration bảng ghi chú tương thích MySQL
- **Frontend:** JavaScript, Sass, Bootstrap 5, Bootstrap Icons, jQuery
- **Build frontend:** Vite 7

## Tính năng hiện tại

- Đăng ký, đăng nhập và đăng xuất.
- Xác minh email; gửi lại email xác minh và yêu cầu đặt lại mật khẩu qua email.
- Chỉnh sửa tên, email và ảnh đại diện; xóa ảnh đại diện.
- Giao diện ghi chú hỗ trợ tìm kiếm, lọc theo nhãn/trạng thái chia sẻ, ghim/bỏ ghim, xóa ghi chú và chuyển chế độ lưới/danh sách.
- Chuyển giao diện sáng/tối; bản nháp trong cửa sổ tạo ghi chú được lưu trên trình duyệt.

> **Phạm vi hiện tại:** Bảng `notes` đã có schema và quan hệ với người dùng, nhưng giao diện trang chính vẫn dùng dữ liệu mẫu, chưa đọc/ghi vào bảng này. Các thao tác ghim, xóa và lọc chỉ thay đổi giao diện; bản nháp được lưu trong `localStorage` của trình duyệt.

## Các tệp chính

| Tệp | Vai trò |
|---|---|
| `routes/web.php` | Khai báo các route xác thực, xác minh email, đặt lại mật khẩu, hồ sơ và ảnh đại diện. |
| `app/Http/Controllers/AuthController.php` | Xử lý đăng ký, đăng nhập và đăng xuất. |
| `app/Http/Controllers/ProfileController.php` | Cập nhật thông tin tài khoản, mật khẩu và ảnh đại diện. |
| `app/Http/Controllers/PasswordController.php` | Gửi liên kết và xử lý đặt lại mật khẩu. |
| `app/Models/User.php` | Mô hình người dùng, xác minh email và thuộc tính ảnh đại diện. |
| `app/Models/Note.php` | Mô hình ghi chú, ép kiểu nhãn/trạng thái và quan hệ với người dùng. |
| `database/migrations/2026_11_18_054936_add_avatar_to_users_table.php` | Bổ sung cột lưu đường dẫn ảnh đại diện cho người dùng. |
| `database/migrations/2026_11_18_142708_create_notes_table.php` | Tạo bảng `notes` gồm nội dung, nhãn, trạng thái và khóa ngoại tới người dùng. |
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

Ứng dụng mặc định dùng SQLite. Nếu dùng MySQL, hãy tạo database trước và cấu hình `DB_CONNECTION=mysql`, `DB_HOST`, `DB_PORT`, `DB_DATABASE`, `DB_USERNAME` cùng `DB_PASSWORD` trong `.env` trước khi chạy migration.

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
