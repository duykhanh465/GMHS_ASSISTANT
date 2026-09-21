# GMHS Assistant — Data Dictionary

> Nguồn: `docs/clinical/excel-original/Dr.Tom.xlsx` (9 sheets)
> Ngày tạo: 2026-09-20
> Quy ước:
> - **DB** = lưu vào MySQL
> - **Calculator** = code trong `app/Services/Calculators/`
> - Mỗi kết quả hiển thị phải có cảnh báo "hỗ trợ tham khảo"

---

## 1. Nhap_lieu — Bảng tính nhanh

**Vai trò:** Sheet trung tâm, chứa input bệnh nhân và công thức tham chiếu. Không tạo bảng riêng, mà map sang `patients` + Calculators.

| Cột Excel | Map sang | Kiểu | Ghi chú |
|---|---|---|---|
| Họ tên/Mã BN | `patients.code`, `patients.full_name` | string, nullable name | Không bắt buộc |
| Tuổi | `patients.age` | integer 0-120 | Dùng tính MAC |
| Giới tính | `patients.gender` | enum male/female | Ảnh hưởng PBW/IBW |
| Chiều cao | `patients.height_cm` | decimal | Dùng tính PBW/IBW |
| Cân nặng thực | `patients.weight_kg` | decimal | Dùng tính BMI, AdjBW |
| Thai kỳ | `patients.is_pregnant` | boolean | Giảm liều TTS |
| Bệnh cảnh hô hấp | `patients.respiratory_condition` | enum routine/Hen-COPD/ARDS/CO2 | Chọn preset máy thở |
| Ghi chú ca mổ | `patients.surgery_note` | text nullable | |
| BMI | `patient_calculations.bmi` | Calculator | kg / (m²) |
| PBW/IBW | `patient_calculations.pbw`, `ibw` | Calculator | Nam: 50+0.91×(cm-152.4), Nữ: 45.5+0.91×(cm-152.4) |
| AdjBW | `patient_calculations.adjbw` | Calculator | PBW + 0.4×(Actual-PBW) khi Actual >120% PBW |
| Cân nặng tính máy thở | `patient_calculations.pbw` | Calculator | Dùng PBW, không dùng cân thực |
| Dịch duy trì 4-2-1 | `patient_calculations.maintenance_fluid` | Calculator | 4-2-1 rule |
| Ống NKQ / LMA | `patient_calculations.ett_size`, `lma_size` | Calculator | Theo tuổi/cân |

**Xử lý:** `PatientMetricsCalculator`, `WeightCalculator`, `FluidCalculator`

---

## 2. Thuoc_GMHS — Thuốc gây mê hồi sức

**Vai trò:** Dữ liệu tham khảo → DB

| Cột Excel | Map sang | Ghi chú |
|---|---|---|
| Nhóm (Opioid/Thuốc mê/Giãn cơ/Hóa giải) | `drug_categories.name`, `slug` | |
| Tên thuốc | `drugs.name` | Fentanyl, Propofol, Rocuronium... |
| Liều/cách dùng | `drugs.dose_text`, `dose_min_per_kg`, `dose_max_per_kg` | VD: 1.5-2.5 mg/kg |
| Kết quả theo BN | `Calculator` tính | Dùng `weight_basis` |
| Đơn vị | `drugs.unit` | mcg, mg |
| Cách pha/preparation | `drugs.preparation` | Dùng trực tiếp / pha 250mg/50mL |
| Theo dõi | `drugs.monitoring` | HA, HR, TOF... |
| Cảnh báo | `drugs.warning` | Tụt HA, ức chế hô hấp... |
| Nguồn | `sources` via `sourceables` | Phác đồ khoa; Oxford |

**Weight basis quan trọng:**
- Vt máy thở → PBW
- Thuốc亲 nước → PBW/IBW
- Thuốc tan trong mỡ → AdjBW/cân thực

---

## 3. May_tho — Thông số máy thở

**Vai trò:** Dữ liệu tham khảo → DB + Calculator

| Cột Excel | Map sang |
|---|---|
| Bệnh cảnh | `ventilator_presets.clinical_scenario` |
| Thông số (Vt, RR, PEEP...) | `ventilator_presets.parameter` |
| Liều lượng/công thức | `ventilator_presets.value_min`, `value_max`, `default_value` |
| Kết quả theo BN | Calculator: Vt = PBW × mL/kg |
| Đơn vị | `ventilator_presets.unit` |
| Theo dõi cần chỉnh | `ventilator_presets.monitoring` |
| Cảnh báo | `ventilator_presets.warning` |
| Nguồn | `sourceables` |

**Các preset cần seed:**
- Thường quy: Vt 6-7, RR 12-16, PEEP 5, FiO2 40-60, I:E 1:2
- Hen/COPD: Vt 6-8, RR 8-10, I:E 1:3-1:5, PEEP 0-5
- ARDS: Vt 4-6, Pplat <30, PEEP ≥5

---

## 4. Van_mach — Vận mạch/Inotrope

**Vai trò:** DB — cần tách `drugs` và `drug_dilutions`

| Cột Excel | Map sang |
|---|---|
| Tên thuốc | `drugs.name` |
| Pha chuẩn | `drug_dilutions.recipe_text`, `concentration_value` |
| Liều/cách dùng | `drugs.dose_text` |
| Kết quả mL/bolus, mL/giờ | `Calculator` + `drug_dilutions.rate_formula` |
| Chỉ định | `drugs.timing` / `notes` |
| Theo dõi | `drugs.monitoring` |
| Cảnh báo | `drugs.warning` |

**Lưu ý an toàn:**
- Noradrenaline 4mg/40mL = 100 mcg/mL
- Noradrenaline 4mg/100mL = 40 mcg/mL → phải hiển thị cảnh báo phân biệt rõ

---

## 5. Giam_dau_PONV

**Vai trò:** DB → `drugs` (category Giảm đau, Chống nôn)

| Cột Excel | Map sang |
|---|---|
| Nhóm | `drug_categories` |
| Tên thuốc | `drugs.name` |
| Liều | `drugs.dose_text` |
| Nồng độ/pha | `drugs.preparation` |
| Thời điểm | `drugs.timing` |
| Theo dõi | `drugs.monitoring` |
| Cảnh báo | `drugs.warning` |

---

## 6. Gay_te_TTS — Gây tê tủy sống

**Vai trò:** DB → `spinal_protocols`

| Cột Excel | Map sang |
|---|---|
| Kỹ thuật/thuốc | `spinal_protocols.technique_or_drug` |
| Liều người lớn | `spinal_protocols.adult_dose` |
| Giảm liều | `spinal_protocols.reduced_dose` |
| Kết quả gợi ý | Logic: giảm liều nếu >60 tuổi hoặc thai kỳ |
| Pha/nồng độ | `spinal_protocols.preparation` |
| Theo dõi | `spinal_protocols.monitoring` |
| Xử trí biến cố | `spinal_protocols.complication_action` |

---

## 7. Cap_cuu_CRISIS

**Vai trò:** DB → `emergency_protocols` + `emergency_protocol_steps`

| Cột Excel | Map sang |
|---|---|
| Tình huống | `emergency_protocols.situation` |
| Dấu hiệu | `emergency_protocols.signs` |
| Hành động ngay | `emergency_protocol_steps.action_text` (step_order) |
| Thuốc/liều | `emergency_protocol_steps.drug_id`, `dose_text` |
| Theo dõi | `emergency_protocols.monitoring` |
| Chẩn đoán phân biệt | `emergency_protocols.differential` |
| Ghi chú | `emergency_protocols.note` |

**Không nằm trong MVP** — để Phase sau.

---

## 8. Cong_thuc — Công thức phải thuộc

**Vai trò:** Bảng tổng hợp, không tạo bảng riêng. Dữ liệu đã có ở `drugs`, `drug_dilutions`, `ventilator_presets`. Dùng làm trang tra cứu đọc từ các bảng đó.

---

## 9. Nguon — Nguồn tham khảo

**Vai trò:** DB → `sources` + `sourceables` (polymorphic)

| Cột Excel | Map sang |
|---|---|
| Tên tài liệu | `sources.name` |
| Phân sử dụng | `sources.document_type` |
| Vị trí trong sách | `sources.notes` |
| Nội dung chuẩn hóa | `sources.notes` |
| Độ ưu tiên | `sources.priority` |
| Ghi chú | `sources.notes` |
| Dạng nguồn | `sources.document_type` |
| Ngày cập nhật | `sources.updated_date` |

**Liên kết:** `sourceables` nối `sources` với `drugs`, `ventilator_presets`, `spinal_protocols`, `emergency_protocols`

---

## Tổng kết mapping

| Sheet | Bảng/Calculator | Trong MVP? |
|---|---|---:|
| Nhap_lieu | `patients`, `patient_calculations` + Calculators | Có |
| Thuoc_GMHS | `drug_categories`, `drugs` | Có |
| May_tho | `ventilator_presets` + `VentilatorCalculator` | Có |
| Van_mach | `drugs`, `drug_dilutions` | Có |
| Giam_dau_PONV | `drugs` | Có |
| Gay_te_TTS | `spinal_protocols` | Không (Phase sau) |
| Cap_cuu_CRISIS | `emergency_protocols` | Không (Phase sau) |
| Cong_thuc | (tổng hợp, không tạo bảng) | Không |
| Nguon | `sources`, `sourceables` | Có |

---

## Cảnh báo bắt buộc

> Kết quả chỉ mang tính hỗ trợ tham khảo. Bác sĩ gây mê cần đánh giá toàn diện bệnh nhân và quyết định cuối cùng dựa trên tình trạng lâm sàng, phác đồ của cơ sở và tài liệu chuyên môn hiện hành.
