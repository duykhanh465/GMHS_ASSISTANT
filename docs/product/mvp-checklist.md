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
- [ ] Đã tạo data dictionary từ Excel.

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

- [ ] Đã cài Laravel Sanctum.
- [ ] Có migration thêm role vào users.
- [ ] Có role `admin`.
- [ ] Có role `doctor`.
- [ ] Có admin seed sẵn.
- [ ] Có doctor seed sẵn.
- [ ] Có API login.
- [ ] Có API logout.
- [ ] Route API được bảo vệ.
- [ ] Vue có trang login.
- [ ] Vue có chức năng logout.

---

## 4. Patients / Cases

- [ ] Có migration bảng patients.
- [ ] patients có `user_id`.
- [ ] patients có soft delete.
- [ ] Có relationship User → Patients.
- [ ] Có API danh sách case.
- [ ] Có API tạo case.
- [ ] Có API sửa case.
- [ ] Có API xóa mềm case.
- [ ] Có patient policy.
- [ ] Doctor chỉ xem được case của chính họ.
- [ ] Admin xem được toàn bộ case.
- [ ] Vue có danh sách case.
- [ ] Vue có tìm kiếm case.
- [ ] Vue có form tạo/chỉnh sửa case.

---

## 5. Calculation engine

- [ ] Đã tạo `WeightCalculator`.
- [ ] Đã tính BMI.
- [ ] Đã tính IBW.
- [ ] Đã tính PBW.
- [ ] Đã tính AdjBW.
- [ ] Đã tạo `FluidCalculator`.
- [ ] Đã tính dịch duy trì 4-2-1.
- [ ] Đã tạo `VentilatorCalculator`.
- [ ] Đã xử lý bệnh cảnh thường quy.
- [ ] Đã xử lý Hen/COPD.
- [ ] Đã xử lý ARDS.
- [ ] Đã xử lý nội soi CO₂.
- [ ] Golden test case khớp Excel.
- [ ] Toàn bộ unit test calculator chạy thành công.

---

## 6. Dữ liệu thuốc

- [ ] Có bảng drug_categories.
- [ ] Có bảng drugs.
- [ ] Có bảng drug_dilutions.
- [ ] Có bảng ventilator_presets.
- [ ] Có bảng sources.
- [ ] Có bảng sourceables.
- [ ] Có seeder danh mục thuốc.
- [ ] Có seeder thuốc khởi mê.
- [ ] Có seeder thuốc giãn cơ.
- [ ] Có seeder vận mạch.
- [ ] Có seeder ventilator presets.
- [ ] Dữ liệu đã đối chiếu với Excel.
- [ ] Mỗi dữ liệu quan trọng có nguồn tham khảo.

---

## 7. Calculation history

- [ ] Có bảng patient_calculations.
- [ ] Có `input_snapshot`.
- [ ] Có `ventilator_result`.
- [ ] Có `drug_result`.
- [ ] Có `vasopressor_result`.
- [ ] Có `engine_version`.
- [ ] Có API tính toán.
- [ ] Có API lưu calculation.
- [ ] Có API lịch sử calculation.
- [ ] Sửa case không làm thay đổi calculation cũ.
- [ ] Vue hiển thị lịch sử calculation.

---

## 8. Responsive user interface

- [ ] Có desktop sidebar.
- [ ] Có mobile header.
- [ ] Có mobile drawer.
- [ ] Có mobile bottom navigation.
- [ ] Có form patient responsive.
- [ ] Có dashboard overview.
- [ ] Có trang máy thở.
- [ ] Có trang gây mê.
- [ ] Có trang vận mạch.
- [ ] Có clinical disclaimer.
- [ ] Test ở mobile width 375px.
- [ ] Test ở tablet width 768px.
- [ ] Test ở desktop width 1440px.

---

## 9. Kịch bản demo cuối cùng

- [ ] Đăng nhập với doctor.
- [ ] Tạo case `DEMO-001`.
- [ ] Nhập nữ, 80 tuổi, 150 cm, 65 kg, ASA II.
- [ ] Chọn bệnh cảnh thường quy.
- [ ] Tính BMI = 28.9.
- [ ] Tính PBW = 43.3 kg.
- [ ] Tính AdjBW = 52 kg.
- [ ] Tính dịch duy trì = 105 mL/giờ.
- [ ] Hiển thị Vt = 260–303 mL.
- [ ] Hiển thị RR = 14 lần/phút.
- [ ] Hiển thị PEEP = 5 cmH₂O.
- [ ] Hiển thị FiO₂ = 40–60%.
- [ ] Lưu calculation snapshot.
- [ ] Mở lại calculation snapshot thành công.
- [ ] Doctor khác bị từ chối khi truy cập case này.
