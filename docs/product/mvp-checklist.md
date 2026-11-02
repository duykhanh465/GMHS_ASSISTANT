# GMHS Assistant — MVP Checklist

> Quy ước:
>
> - `[ ]` = chưa làm.
> - `[x]` = đã làm và đã kiểm tra.
> - Không đánh dấu hoàn thành nếu mới tạo giao diện nhưng chưa chạy được.

---

## 1. Tài liệu và thiết kế

- [x] Đã tạo `mvp-scope.md`.
- [x] Đã đọc và hiểu phạm vi MVP.
- [x] Đã chốt ERD.
- [x] Đã lưu ERD vào `docs/database/`.
- [x] Đã đặt file Excel gốc vào `docs/clinical/excel-original/`.
- [x] Đã tạo data dictionary từ Excel.

---

## 2. Môi trường phát triển

- [x] Đã cài Laragon.
- [x] PHP chạy được bằng lệnh `php -v`.
- [x] Composer chạy được bằng lệnh `composer -V`.
- [x] MySQL chạy được bằng lệnh `mysql --version`.
- [x] Đã tạo database `gmhs_assistant`.
- [x] Laravel đã kết nối MySQL và migration nền chạy thành công.
- [x] Đã khởi tạo Git repository và đẩy branch `main` lên GitHub.
- [x] Đã tạo Laravel backend.
- [x] Đã tạo Vue 3 frontend.
- [x] Vue type-check và production build chạy thành công.

---

## 3. Authentication

- [x] Đã cài Laravel Sanctum.
- [x] Có migration thêm role vào users.
- [x] Có role `admin`.
- [x] Có role `doctor`.
- [x] Có admin seed sẵn.
- [x] Có doctor seed sẵn.
- [x] Có API login.
- [x] Có API logout.
- [x] Route API được bảo vệ.
- [x] Vue có trang login.
- [x] Vue có chức năng logout.

---

## 4. Patients / Cases

- [x] Có migration bảng patients.
- [x] patients có `user_id`.
- [x] patients có soft delete.
- [x] Có relationship User → Patients.
- [x] Có API danh sách case.
- [x] Có API tạo case.
- [x] Có API sửa case.
- [x] Có API xóa mềm case.
- [x] Có patient policy.
- [x] Doctor chỉ xem được case của chính họ.
- [x] Admin xem được toàn bộ case.
- [x] Vue có danh sách case.
- [x] Vue có tìm kiếm case.
- [x] Vue có form tạo/chỉnh sửa case.

---

## 5. Calculation engine

- [x] Đã tạo `WeightCalculator`.
- [x] Đã tính BMI.
- [x] Đã tính IBW.
- [x] Đã tính PBW.
- [x] Đã tính AdjBW.
- [x] Đã tạo `FluidCalculator`.
- [x] Đã tính dịch duy trì 4-2-1.
- [x] Đã tạo `VentilatorCalculator`.
- [x] Đã xử lý bệnh cảnh thường quy.
- [x] Đã xử lý Hen/COPD.
- [x] Đã xử lý ARDS.
- [x] Đã xử lý nội soi CO₂.
- [x] Golden test case khớp Excel.
- [x] Toàn bộ unit test calculator chạy thành công.

---

## 6. Dữ liệu thuốc

- [x] Có bảng drug_categories.
- [x] Có bảng drugs.
- [x] Có bảng drug_dilutions.
- [x] Có bảng ventilator_presets.
- [x] Có bảng sources.
- [x] Có bảng sourceables với các cột liên kết nguồn.
- [x] Có seeder danh mục thuốc.
- [x] Có seeder thuốc khởi mê.
- [x] Có seeder thuốc giãn cơ.
- [x] Có seeder vận mạch.
- [x] Có seeder ventilator presets.
- [x] Dữ liệu đã đối chiếu với Excel.
- [x] Mỗi dữ liệu quan trọng có nguồn tham khảo.

---

## 7. Calculation history

- [x] Có bảng patient_calculations.
- [x] Có `input_snapshot`.
- [x] Có `ventilator_result`.
- [x] Có `drug_result`.
- [x] Có `vasopressor_result`.
- [x] Có `engine_version`.
- [x] Có API tính toán.
- [x] Có API lưu calculation.
- [x] Có API lịch sử calculation.
- [x] Sửa case không làm thay đổi calculation cũ.
- [x] Vue hiển thị lịch sử calculation.

---

## 8. Responsive user interface

- [x] Có desktop sidebar.
- [x] Có mobile header.
- [x] Có mobile drawer.
- [x] Có mobile bottom navigation.
- [x] Có form patient responsive.
- [x] Có dashboard overview.
- [x] Có trang máy thở.
- [x] Có trang gây mê.
- [x] Có trang vận mạch.
- [x] Có clinical disclaimer.
- [x] Test ở mobile width 375px.
- [x] Test ở tablet width 768px.
- [x] Test ở desktop width 1440px.

---

## 9. Kịch bản demo cuối cùng

- [x] Đăng nhập với doctor.
- [x] Tạo case `DEMO-001`.
- [x] Nhập nữ, 80 tuổi, 150 cm, 65 kg, ASA II.
- [x] Chọn bệnh cảnh thường quy.
- [x] Tính BMI = 28.9.
- [x] Tính PBW = 43.3 kg.
- [x] Tính AdjBW = 52 kg.
- [x] Tính dịch duy trì = 105 mL/giờ.
- [x] Hiển thị Vt = 260–303 mL.
- [x] Hiển thị RR = 14 lần/phút.
- [x] Hiển thị PEEP = 5 cmH₂O.
- [x] Hiển thị FiO₂ = 40–60%.
- [x] Lưu calculation snapshot.
- [x] Mở lại calculation snapshot thành công.
- [x] Doctor khác bị từ chối khi truy cập case này.

---

## 10. Sau MVP (PWA, admin, TTS/CRISIS, i18n dữ liệu)

- [x] PWA: `manifest.webmanifest`, icons, `sw.js`, đăng ký trong `main.ts`.
- [x] Middleware `admin` + `/api/admin/users|drugs|sources`.
- [x] Trang `/admin` chỉ hiện với role admin.
- [x] Bảng `spinal_protocols` (7 dòng sheet Gay_te_TTS) + API + Vue.
- [x] Bảng `emergency_protocols` (7 dòng sheet Cap_cuu_CRISIS) + API + Vue.
- [x] Cột `translations` JSON + `?locale=` cho drugs/categories/sources/protocols.
- [x] Feature tests: Auth, PatientPolicy, Drug, Source, Admin, Protocol.
- [x] README chạy local, demo account, golden numbers.

