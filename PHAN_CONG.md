# PHÂN CÔNG – 503073 Final Project (Ứng dụng quản lý ghi chú)

> Nguyên tắc: chia theo **tính năng dọc** (mỗi người sở hữu riêng bảng DB, controller, route, view, JS của mình). Hai người chỉ giao tiếp qua phần **"Hợp đồng chung"** ở mục 2, nên có thể làm song song và không phải chờ nhau.

---

## 1. Tổng quan phân công

> Lưu ý: tổng điểm các mục trong rubric là **9.0đ** (nhóm "Simple note" ghi 4.0 nhưng 12 mục chỉ cộng được 3.0). Nên hỏi giảng viên để xác nhận.

### TV1 – "Tài khoản + Xã hội + Hạ tầng" (tổng 4.75đ, đã có 1.75đ)

| # | Tiêu chí | Điểm | Trạng thái |
|---|---|---|---|
| 1-7 | Đăng ký, kích hoạt, login/logout, quên mật khẩu, xem/sửa hồ sơ + avatar, đổi mật khẩu | 1.75 | ✅ Xong (cần dọn lỗi) |
| 8 | User preferences (cỡ chữ, màu note, sáng/tối) lưu DB | 0.25 | ⬜ |
| 18 | Quản lý nhãn (xem, thêm, đổi tên, xóa) | 0.25 | ⬜ |
| 19 | Gắn nhãn vào note | 0.25 | ⬜ |
| 20 | Lọc note theo nhãn | 0.25 | ⬜ |
| 23 | Chia sẻ + nhận note (trang "Shared with me") | 0.5 | ⬜ |
| 24 | Cộng tác realtime (WebSocket / Reverb) | 0.5 | ⬜ |
| 28 | Deploy online (HTTPS) hoặc docker-compose | 0.5 | ⬜ |
| 25-26 | UI/UX + Responsive **cho các màn hình của TV1** | 0.5 | ⬜ |

### TV2 – "Lõi ghi chú + Bảo mật note + Offline" (tổng 4.25đ)

| # | Tiêu chí | Điểm |
|---|---|---|
| 9 | Hiển thị list view | 0.25 |
| 10 | Hiển thị grid view (mặc định) | 0.25 |
| 11 | Tạo note | 0.25 |
| 12 | Sửa note (chung giao diện với tạo) | 0.25 |
| 13 | Xóa note (có dialog xác nhận) | 0.25 |
| 14 | Auto-save | 0.25 |
| 15 | Đính kèm một hoặc nhiều ảnh | 0.25 |
| 16 | Ghim note (xếp theo thời điểm ghim) | 0.25 |
| 17 | Tìm kiếm live (debounce ~300ms) | 0.25 |
| 21 | Bật/tắt mật khẩu note | 0.5 |
| 22 | Đổi mật khẩu note + bảo vệ xem/sửa/xóa | 0.5 |
| 27 | Offline / PWA (service worker + IndexedDB + sync) | 0.5 |
| 25-26 | UI/UX + Responsive **cho các màn hình của TV2** | 0.5 |

### Việc chung không có điểm nhưng bắt buộc

| Việc | Người làm |
|---|---|
| Dọn code (xóa `homeold.blade.php`, `VerifyMail.php`, route `/homecopy`, sửa `notice.blace.php`) | TV1 |
| Cấu hình SMTP thật để gửi mail kích hoạt / reset / chia sẻ | TV1 |
| `readme.txt`, điền `Rubrik.docx`, đóng gói ZIP đúng tên | TV1 |
| Dựng và biên tập `demo.mp4` (≥1080p, đủ 28 mục) | TV1 |
| Dữ liệu mẫu + tài khoản demo cho giảng viên (seeder) | TV2 (phần note) / TV1 (phần user, nhãn, chia sẻ) |
| Quay phần demo của mình (cả hai phải xuất hiện trong video) | Cả hai |

> TV1 có tổng điểm cao hơn vì đã làm xong 1.75đ. Khối lượng **còn lại** của TV2 lớn hơn, nên TV1 nhận thêm các việc chung (dọn code, mail, readme, rubrik, video, deploy).

---

## 2. HỢP ĐỒNG CHUNG (cả hai phải tuân thủ, không tự ý đổi)

> Nếu cần đổi bất kỳ mục nào bên dưới, nhắn người kia trước và cập nhật file này.

### 2.1 Schema CSDL

Quy ước tên migration: bảng của TV2 dùng timestamp `2026_11_20_0000xx`, bảng của TV1 dùng `2026_11_21_0000xx` (chạy sau, vì có khóa ngoại tới `notes`).

**TV2 sở hữu:**

```
notes
  id, user_id (FK users, cascade)
  title        string nullable
  content      longText nullable
  is_pinned    boolean default false
  pinned_at    timestamp nullable
  password     string nullable        -- bcrypt hash, null = không khóa
  created_at, updated_at

note_images
  id, note_id (FK notes, cascade), path string, created_at
```

**TV1 sở hữu:**

```
user_preferences
  id, user_id (FK users, unique), font_size string, note_color string, theme string

labels
  id, user_id (FK users), name string      -- unique(user_id, name)

note_label                                   -- bảng trung gian
  note_id (FK notes, cascade), label_id (FK labels, cascade)   -- primary(note_id, label_id)

note_shares
  id, note_id (FK notes, cascade)
  shared_with_user_id (FK users, cascade)
  permission enum('read','edit')
  created_at (= thời điểm chia sẻ), updated_at
  unique(note_id, shared_with_user_id)
```

Ghi chú: xóa nhãn chỉ xóa dòng trong `labels` và `note_label`, **không** xóa note. Đổi tên nhãn chỉ sửa `labels.name`.

### 2.2 Quy ước file (tránh xung đột khi merge)

| Thứ | TV1 | TV2 |
|---|---|---|
| Route | `routes/web.php` (auth, profile), `routes/social.php` | `routes/notes.php` |
| Controller | `AuthController`, `PasswordController`, `ProfileController`, `PreferenceController`, `LabelController`, `ShareController`, `SharedNoteController` | `NoteController`, `NoteImageController`, `NoteLockController` |
| Model | `UserPreference`, `Label`, `NoteShare`, trait `app/Models/Concerns/HasLabelsAndShares.php` | `Note`, `NoteImage` |
| View | `authentication/`, `reset-password/`, `profile/`, `preferences/`, `labels/`, `shares/`, `layouts/app.blade.php`, `partials/sidebar.blade.php` | `notes/index.blade.php`, `notes/editor.blade.php`, `notes/partials/card.blade.php`, `notes/partials/lock-modal.blade.php` |
| JS | `preferences.js`, `labels.js`, `share.js`, `realtime.js` | `notes.js`, `editor.js`, `search.js`, `lock.js`, `offline-db.js`, `public/sw.js`, `public/manifest.webmanifest` |
| Sass | `account.scss`, `social.scss` | `notes.scss` |
| Khác | `config/reverb.php`, `config/broadcasting.php`, `routes/channels.php`, Docker files | – |

`routes/notes.php` và `routes/social.php` được `require` trong `routes/web.php` (TV1 thêm 2 dòng này ngay ở Bước 0).

### 2.3 Điểm tích hợp (cách hai phần "cắm" vào nhau)

**a) Model `Note`** (TV2 viết) chỉ `use HasLabelsAndShares;` (TV1 viết). Trait cung cấp:
- Quan hệ `labels()`, `shares()`
- Accessor `is_shared` (bool)
- Scope `filterByLabels(?array $labelIds)` (null hoặc mảng rỗng thì không lọc)
- Scope `visibleTo($user)` (note của mình + note được chia sẻ)

TV2 chỉ gọi các hàm này trong `NoteController`. Trong lúc TV1 chưa làm xong, trait có **bản stub** (scope không làm gì, `is_shared` luôn false) để TV2 chạy được ngay.

Accessor `has_password` (bool, `password !== null`) và `is_pinned` do TV2 định nghĩa trong `Note`.

**b) HTML của thẻ note** (TV2 viết, TV1 dùng lại cho trang "Shared with me"):

```html
<article class="note-card" data-note-id="{{ $note->id }}">
  <div class="note-badges"> <!-- icon ghim / khóa / chia sẻ, hiển thị ở cả grid và list --> </div>
  <h3 class="note-title">…</h3>
  <div class="note-content">…</div>
</article>
```

Partial `notes/partials/card.blade.php` nhận biến `$note` và `$readonly` (mặc định false). TV1 `@include` nó trong trang Shared with me kèm thêm thông tin người chia sẻ, quyền, thời điểm (TV1 tự thêm bên ngoài partial). Icon chia sẻ dựa vào `$note->is_shared`.

**c) Màn hình sửa note** (TV2) có sẵn:
- Ô nhập `#note-title` và `#note-content`, thẻ chứa có `data-note-id`
- Nút "Chia sẻ" mở modal: `data-bs-toggle="modal" data-bs-target="#shareModal"`
- Dòng `@include('shares.modal')` (TV1 viết modal, TV2 chỉ include; stub rỗng ở Bước 0)
- Khu vực chọn nhãn `<div id="note-labels-slot">@include('labels.picker', ['note' => $note])</div>` (TV1 viết picker)

**d) Sự kiện JS** (CustomEvent trên `document`):

| Sự kiện | Ai phát | Ai nghe | Dữ liệu |
|---|---|---|---|
| `note:opened` | TV2 (editor) | TV1 `realtime.js` | `{ noteId, canEdit }` |
| `note:local-change` | TV2 (editor) | TV1 `realtime.js` | `{ noteId, title, content }` |
| `note:remote-update` | TV1 `realtime.js` | TV2 (editor) | `{ noteId, title, content, fromUserId }` |

Mỗi người có thể test độc lập bằng cách tự `dispatchEvent` thủ công trong console.

**e) CSS variables** (TV1 xuất ra trong layout từ bảng `user_preferences`, TV2 chỉ dùng):

```css
:root {
  --note-font-size: 16px;
  --note-bg-color: #ffffff;
}
```
Chế độ sáng/tối dùng `data-bs-theme` trên thẻ `<html>` như hiện tại.

**f) API ghi chú** (TV2) — TV1 không gọi trực tiếp, trừ thông tin chia sẻ do TV1 tự cung cấp qua `ShareController`:
`GET /notes`, `POST /notes`, `GET/PUT/DELETE /notes/{id}`, `POST /notes/{id}/pin`, `POST/DELETE /notes/{id}/images`, `POST /notes/{id}/lock`, `POST /notes/{id}/unlock-check`, `PUT /notes/{id}/lock`, `DELETE /notes/{id}/lock`.

**g) API của TV1:** `/labels` (CRUD), `PUT /notes/{id}/labels` (gắn nhãn), `/notes/{id}/shares` (CRUD người nhận), `GET /shared` (trang Shared with me), `PUT /preferences`.

### 2.4 Quyết định chốt trước để khỏi vướng nhau

1. Mật khẩu note chỉ bảo vệ **chủ note**. Note đang bị khóa mật khẩu thì **không được chia sẻ** (TV1 ẩn/disable nút chia sẻ khi `has_password = true`). Cần tắt khóa trước khi chia sẻ.
2. Người nhận chia sẻ quyền `edit` có thể sửa title/content, **không** được xóa, ghim, khóa hay đổi nhãn note của người khác.
3. Chỉ chủ note được ghim / xóa / gắn nhãn / khóa mật khẩu. Nhãn là của riêng từng user.
4. Auto-save gọi `PUT /notes/{id}` (debounce). Với note mới, lần nhập đầu tiên sẽ `POST /notes` rồi dùng id trả về.
5. **Không được thêm trường nào khác vào note ngoài title và content** (phần tô đỏ trong đề: thêm tính năng là bị loại bài).

---

## 3. Bước 0 – Khởi động (làm trước khi chia nhánh, không quá nửa ngày)

Mục tiêu: để hai người làm độc lập ngay từ đầu.

**TV1 làm:**
1. Dọn lỗi cũ (xem mục 1), xóa file thừa.
2. Tách `home.blade.php` thành `layouts/app.blade.php` + `partials/sidebar.blade.php` + `notes/index.blade.php` (nội dung tạm giữ nguyên dữ liệu mẫu).
3. Thêm 2 dòng `require` cho `routes/notes.php` và `routes/social.php` (file rỗng).
4. Tạo **stub** `HasLabelsAndShares`, `shares/modal.blade.php` (rỗng), `labels/picker.blade.php` (rỗng).
5. Push lên `main`, nhắn TV2.

**TV2 làm (song song, không cần chờ):** cài môi trường, đọc `README.md` + code cũ, tạo migration `notes`, `note_images` và model `Note` theo mục 2.1, viết `NoteController` khung. Khi TV1 push xong thì rebase lên.

Sau đó chia nhánh: `feature/tv1-*` và `feature/tv2-*`, merge vào `main` thường xuyên (ít nhất mỗi 2-3 ngày).

---

## 4. Kế hoạch từng người (mỗi người tự kiểm thử được, không cần phần của người kia)

### TV1

| Giai đoạn | Việc | Cách test độc lập |
|---|---|---|
| 1 | Bước 0, sau đó `user_preferences` + màn Preferences (cỡ chữ, màu note, sáng/tối), xuất CSS variables | Mở trang preferences, kiểm tra biến CSS đổi |
| 2 | Nhãn: trang quản lý (xem/thêm/đổi tên/xóa), trait `HasLabelsAndShares` bản thật (`filterByLabels`), picker gắn nhãn, bộ lọc nhãn ở sidebar | Dùng seeder tạo vài note thủ công trong DB hoặc `tinker` |
| 3 | Chia sẻ: kiểm tra email thuộc user đã đăng ký, chọn nhiều người nhận, quyền read/edit, thu hồi/đổi quyền, gửi mail/thông báo cho người nhận, trang "Shared with me" (quyền, người chia sẻ, thời điểm), chủ note xem được danh sách người nhận | Dùng 2 tài khoản + note tạo bằng seeder |
| 4 | Realtime: cấu hình Reverb, channel riêng theo note có kiểm tra quyền, `realtime.js` phát/nhận sự kiện (mục 2.3d) | Trang test tạm có 2 ô `#note-title`/`#note-content`, mở bằng 2 trình duyệt |
| 5 | Deploy online (HTTPS) hoặc docker-compose, SMTP thật, readme, rubrik, ghép video | – |

Tự rà UX/responsive cho: đăng nhập/đăng ký, hồ sơ, preferences, nhãn, modal chia sẻ, trang Shared with me.

### TV2

| Giai đoạn | Việc | Cách test độc lập |
|---|---|---|
| 1 | Migration + model `Note`, CRUD, grid/list (grid mặc định), dialog xác nhận khi xóa, auto-save với debounce, **một** giao diện cho cả thêm và sửa | Chạy với trait stub của TV1 |
| 2 | Ghim (sắp xếp theo `pinned_at`), tìm kiếm live (300ms, tìm cả title và content), upload nhiều ảnh | – |
| 3 | Khóa mật khẩu: bật (nhập 2 lần), đổi (nhập mật khẩu cũ + mới 2 lần), tắt (nhập lại mật khẩu hiện tại), hỏi mật khẩu trước khi xem/sửa/xóa, kiểm tra ở **server** chứ không chỉ ở JS; icon ghim/khóa ở grid và list | – |
| 4 | PWA: `manifest`, `sw.js`, IndexedDB lưu note, xem offline, hàng đợi sync khi có mạng lại | DevTools → Network → Offline |
| 5 | Chỉnh UI/UX + responsive cho màn danh sách, editor, dialog khóa; seed dữ liệu demo | – |

Tự rà UX/responsive cho: trang danh sách note, editor, dialog khóa, thanh tìm kiếm.

---

## 5. Mốc tích hợp chung

| Mốc | Nội dung | Ai làm gì |
|---|---|---|
| A | Kết thúc Bước 0 | TV1 push, TV2 rebase |
| B | Cuối giai đoạn 2 | Merge hai nhánh vào `main`, cả hai chạy thử: tạo note → gắn nhãn → lọc → ghim → tìm kiếm |
| C | Cuối giai đoạn 4 | Merge, test chia sẻ + realtime trên editor thật, test offline |
| D | Trước hạn nộp ít nhất 2-3 ngày | Test chéo toàn bộ 28 mục theo rubric, quay video, nộp |

---

## 6. Phần phụ thuộc không tránh khỏi (đã được tách tối đa)

| Phụ thuộc | Cách giảm rủi ro |
|---|---|
| Nhãn / chia sẻ cần có bảng `notes` | Schema nằm sẵn ở mục 2.1, TV1 test bằng seeder, không cần chờ UI của TV2 |
| Realtime cần màn hình editor | Hai bên giao tiếp qua sự kiện JS (2.3d), TV1 test bằng trang tạm |
| Icon chia sẻ trên thẻ note | TV2 chỉ đọc `$note->is_shared` (stub → bản thật) |
| Preferences áp dụng lên note | TV2 chỉ dùng CSS variables, không đọc DB preferences |
| Trang Shared with me dùng lại thẻ note | `@include` partial `card.blade.php` (có bản stub ở Bước 0) |

---

## 7. Checklist chất lượng (đề yêu cầu: có xử lý lỗi, không lỗ hổng bảo mật nghiêm trọng)

**TV1:** chặn truy cập trái phép vào note người khác (policy), validate email người nhận có tồn tại, không cho tự chia sẻ cho chính mình, kiểm tra quyền trên channel WebSocket, nhãn trùng tên trong cùng một user phải báo lỗi, mật khẩu đúng chuẩn bcrypt, throttle gửi mail.

**TV2:** mọi thao tác sửa/xóa/mở note đều kiểm tra chủ sở hữu ở server, mật khẩu note lưu dạng hash, chặn file upload không phải ảnh + giới hạn dung lượng, không trả nội dung note khóa khi chưa xác thực mật khẩu, escape nội dung khi render (chống XSS).

**Cả hai:** tuyệt đối không thêm tính năng ngoài đề, dùng URL tương đối (không hardcode host/port), chạy được ở thư mục gốc, clean project trước khi nén.
