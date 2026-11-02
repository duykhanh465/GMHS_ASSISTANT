# GMHS Assistant — MVP Scope v1.0

## 1. MVP là gì?

MVP là viết tắt của Minimum Viable Product.

Trong dự án này, MVP là phiên bản nhỏ nhất của GMHS Assistant có thể chạy được
từ đầu đến cuối, có thể demo trong CV và chứng minh được kỹ năng Vue 3,
Laravel, MySQL, REST API, authentication, responsive UI và xử lý công thức.

MVP không phải là phiên bản hoàn chỉnh có tất cả chức năng trong Excel.

---

## 2. Mục tiêu của GMHS Assistant MVP

Xây dựng web application responsive hỗ trợ bác sĩ gây mê hồi sức:

1. Đăng nhập hệ thống.
2. Tạo một case bệnh nhân.
3. Nhập thông tin cơ bản của bệnh nhân.
4. Tính các chỉ số nền.
5. Hiển thị gợi ý máy thở.
6. Hiển thị gợi ý thuốc khởi mê và giãn cơ.
7. Hiển thị gợi ý vận mạch cơ bản.
8. Lưu kết quả tính toán.
9. Mở lại lịch sử tính toán của case.

Kết quả chỉ có tính chất hỗ trợ tham khảo. Bác sĩ gây mê vẫn là người đánh giá
toàn diện bệnh nhân và quyết định cuối cùng theo tình trạng lâm sàng, phác đồ
của cơ sở và tài liệu chuyên môn hiện hành.

---

## 3. Người dùng của hệ thống

Hệ thống có hai loại người dùng.

### 3.1. Doctor

Doctor là bác sĩ sử dụng ứng dụng để tạo case và xem kết quả.

Doctor có thể:

- Đăng nhập.
- Đăng xuất.
- Xem danh sách case do họ tạo.
- Tạo case mới.
- Chỉnh sửa case do họ tạo.
- Xóa mềm case do họ tạo.
- Bấm tính toán.
- Xem dashboard kết quả.
- Lưu calculation snapshot.
- Xem lại các lần tính trước của case.

Doctor không thể:

- Xem case của doctor khác.
- Sửa dữ liệu thuốc chuẩn.
- Sửa công thức chuẩn.
- Quản lý tài khoản khác.

### 3.2. Admin

Admin có toàn bộ quyền của Doctor.

Trong MVP, admin chỉ cần có tài khoản seed sẵn để demo.

Trang quản lý thuốc, tài liệu nguồn và người dùng sẽ được làm ở phiên bản sau,
không nằm trong MVP.

---Phase 2.2 là phần làm hệ thống đăng nhập và phân quyền người dùng cho GMHS Assistant.

Nói đơn giản:


Phase 2.2 = Ai được vào app? Vào bằng gì? Vào rồi được quyền làm gì?
Trước Phase 2.2
App của bạn mới chỉ có:


Laravel chạy được
Vue chạy được
Database chạy được
Nhưng chưa có:


Ai là người dùng?
Đăng nhập bằng gì?
Làm sao biết người này là bác sĩ?
Làm sao biết người này là admin?
API nào cần đăng nhập mới được gọi?
Sau Phase 2.2
App đã có nền tảng auth:


Doctor đăng nhập được
Admin đăng nhập được
Laravel cấp token cho người đăng nhập
Token đó dùng để gọi API bảo vệ
Logout sẽ xóa token
User có role: admin hoặc doctor
User có trạng thái: active hoặc inactive
Cụ thể Phase 2.2 đã làm gì
1. Cài Sanctum
Sanctum là hệ thống token auth của Laravel.

Khi bác sĩ đăng nhập đúng email/password, Laravel trả về một token:


2|xxxxxxxxxxxxxxxxxxxxxxxxxxxx
Token này giống như “thẻ ra vào”.

Frontend Vue sau này sẽ lưu token đó và gửi kèm mỗi lần gọi API:


Authorization: Bearer <token>
Nhờ vậy Laravel biết:


Người đang gọi API là ai?
Có đăng nhập chưa?
Role là admin hay doctor?
2. Thêm role và is_active vào bảng users
Bạn đã thêm hai cột này:


role       = admin hoặc doctor
is_active  = tài khoản còn hoạt động hay không
Ý nghĩa:

Cột	Dùng để làm gì
role	Phân biệt admin và bác sĩ
is_active	Khóa tài khoản nếu cần
Ví dụ:


GMHS Admin  → role admin
GMHS Doctor → role doctor
Sau này:


admin  → quản lý dữ liệu thuốc, nguồn, protocol
doctor → tạo case, tính toán, xem case của chính họ
3. Tạo tài khoản demo
Bạn đã seed được:


admin@gmhs.test
doctor@gmhs.test
Cả hai có password:


password
Đây là tài khoản local để test và demo, không phải tài khoản production.

4. Tạo API login
Endpoint:


POST /api/login
Bạn test bằng:


Invoke-RestMethod -Method Post -Uri http://localhost:8000/api/login ...
Khi đúng email/password, Laravel trả về:


user
token
Nghĩa là app đã biết bác sĩ nào vừa đăng nhập.

5. Tạo API /api/me
Endpoint:


GET /api/me
API này yêu cầu có token mới gọi được.

Bạn đã test thành công và nhận:


id        : 5
name      : GMHS Doctor
email     : doctor@gmhs.test
role      : doctor
is_active : True
Điều này chứng minh:


Token hợp lệ
Laravel nhận ra user hiện tại
Route auth:sanctum hoạt động
6. Tạo API logout
Endpoint:


POST /api/logout
Nó xóa token hiện tại.

Sau khi logout, token cũ không dùng được nữa. Nếu gọi lại /api/me, Laravel phải báo:


Unauthenticated
Vì sao Phase 2.2 quan trọng
Vì toàn bộ app sau này sẽ dựa vào nó.

Ví dụ Phase 2.3 tạo bảng patients, thì phải có rule:


Doctor A chỉ xem bệnh nhân/case của Doctor A
Doctor B không xem được case của Doctor A
Admin xem được tất cả
Muốn làm được rule đó, trước tiên phải có:


Đăng nhập
Token
User hiện tại
Role admin/doctor
Đó chính là Phase 2.2.

Hình dung dễ hiểu
GMHS Assistant giống một bệnh viện nhỏ:


Phase 1: xây tòa nhà, bật điện, có database
Phase 2.2: làm cổng ra vào, thẻ nhân viên, phân biệt bác sĩ và admin
Phase 2.3: bắt đầu tạo hồ sơ bệnh nhân/case
Phase 3: làm máy tính công thức y khoa
Phase 4: đưa thuốc và máy thở từ Excel vào database
Bạn hiện đã làm gần xong phần “cổng ra vào + thẻ nhân viên”. Chỉ cần test logout thành công là Phase 2.2 hoàn tất.

## 4. Luồng sử dụng chính

Đây là luồng demo bắt buộc phải hoạt động:

```text
Doctor đăng nhập
        ↓
Xem danh sách case
        ↓
Bấm “Tạo case mới”
        ↓
Nhập thông tin bệnh nhân
        ↓
Bấm “Tính toán”
        ↓
Xem dashboard kết quả
        ↓
Bấm “Lưu kết quả”
        ↓
Mở lại lịch sử calculation của case
```

Nếu toàn bộ luồng này chạy được trên desktop và điện thoại, MVP đạt mục tiêu.

---

## 5. Thông tin nhập cho một case bệnh nhân

Mỗi case trong MVP có các trường sau:

| Tên trường | Tên database dự kiến | Kiểu dữ liệu | Bắt buộc |
|---|---|---|---:|
| Mã case | `code` | Chuỗi ký tự | Có |
| Tên bệnh nhân | `full_name` | Chuỗi ký tự | Không |
| Tuổi | `age` | Số nguyên từ 0 đến 120 | Có |
| Giới tính | `gender` | `male` hoặc `female` | Có |
| Chiều cao | `height_cm` | Số, đơn vị cm | Có |
| Cân nặng thực | `weight_kg` | Số, đơn vị kg | Có |
| Phân độ ASA | `asa_class` | I, II, III, IV hoặc V | Có |
| Thai kỳ | `is_pregnant` | Có hoặc Không | Có |
| Bệnh cảnh hô hấp | `respiratory_condition` | Enum | Có |
| Ghi chú phẫu thuật | `surgery_note` | Văn bản | Không |

Các lựa chọn cho `respiratory_condition`:

```text
routine        = Thường quy
asthma_copd    = Hen / COPD
ards           = ARDS hoặc nguy cơ tổn thương phổi
co2_endoscopy  = Nội soi CO₂
```

Không đưa CCCD, số điện thoại, địa chỉ, số hồ sơ bệnh án, ảnh bệnh nhân hoặc
dữ liệu định danh bệnh nhân thật vào môi trường development, GitHub hoặc bản demo.

---

## 6. Chức năng trong MVP

### 6.1. Authentication

MVP phải có:

- Form đăng nhập bằng email và mật khẩu.
- Nút đăng xuất.
- Route chưa đăng nhập sẽ tự chuyển về trang đăng nhập.
- Role `admin`.
- Role `doctor`.
- Tài khoản test seed sẵn cho môi trường local.

Tài khoản demo local dự kiến:

```text
Admin
Email: admin@gmhs.test
Password: password

Doctor
Email: doctor@gmhs.test
Password: password
```

Các mật khẩu này chỉ dùng ở máy local để phát triển. Không dùng chúng khi deploy.

---

### 6.2. Quản lý case bệnh nhân

Doctor có thể:

- Xem danh sách case.
- Tìm theo mã case.
- Tìm theo tên bệnh nhân.
- Tạo case.
- Chỉnh sửa case.
- Xóa mềm case.
- Mở chi tiết một case.
- Xem lịch sử calculation của case đó.

Quy tắc phân quyền:

```text
Doctor A chỉ xem được case do Doctor A tạo.
Doctor B không được xem hoặc sửa case của Doctor A.
Admin có thể xem tất cả case.
```

---

### 6.3. Các chỉ số nền cần tính

Khi người dùng bấm nút `Tính toán`, hệ thống phải tính:

| Chỉ số | Ý nghĩa |
|---|---|
| BMI | Chỉ số khối cơ thể |
| IBW | Ideal Body Weight |
| PBW | Predicted Body Weight |
| AdjBW | Adjusted Body Weight |
| Dosing Weight | Cân nặng sử dụng để tính liều khi phù hợp |
| Ventilator Weight | Cân nặng sử dụng để cài máy thở |
| Maintenance Fluid | Dịch duy trì theo quy tắc 4-2-1 |
| MAC | Gợi ý MAC theo tuổi |
| ETT Size | Cỡ ống nội khí quản gợi ý |
| LMA Size | Cỡ LMA gợi ý |

Mỗi kết quả phải có:

- Giá trị hoặc khoảng giá trị.
- Đơn vị.
- Mô tả ngắn nếu cần.
- Cảnh báo liên quan nếu có.

Công thức phải được viết trong Laravel Calculator Service và có unit test.
Không chạy hoặc `eval` công thức động từ database.

---

### 6.4. Gợi ý máy thở

MVP hỗ trợ bốn bệnh cảnh:

```text
1. Thường quy
2. Hen / COPD
3. ARDS hoặc nguy cơ tổn thương phổi
4. Nội soi CO₂
```

Các thông số hiển thị:

| Thông số | Ý nghĩa |
|---|---|
| Vt | Thể tích khí lưu thông |
| RR | Tần số thở |
| PEEP | Positive End-Expiratory Pressure |
| FiO₂ | Nồng độ oxy hít vào |
| I:E | Tỷ lệ thì hít vào : thở ra |
| EtCO₂ | Mục tiêu EtCO₂ khi phù hợp |
| Monitoring | Các thông số cần theo dõi |
| Warning | Cảnh báo lâm sàng |

---

### 6.5. Gợi ý thuốc gây mê

MVP có các thuốc từ file Excel sau.

#### Thuốc khởi mê

```text
Fentanyl
Propofol
Etomidate
Ketamine
```

#### Thuốc giãn cơ

```text
Rocuronium
Cisatracurium
Succinylcholine
```

#### Hóa giải cơ bản

```text
Sugammadex
Neostigmine
Atropine
```

Mỗi thuốc hiển thị:

- Tên thuốc.
- Nhóm thuốc.
- Liều chuẩn hoặc khoảng liều.
- Liều theo bệnh nhân khi có thể tính.
- Đơn vị.
- Cách dùng.
- Cách pha nếu cần.
- Theo dõi.
- Cảnh báo.
- Nguồn tham khảo.

---

### 6.6. Vận mạch cơ bản

MVP hỗ trợ tra cứu/tính cho:

```text
Phenylephrine
Ephedrine
Atropine
Adrenaline bolus
Adrenaline truyền
Noradrenaline 4 mg / 40 mL
Noradrenaline 4 mg / 100 mL
```

Đối với thuốc truyền, giao diện phải phân biệt rõ:

```text
Tên dung dịch
Công thức pha
Nồng độ sau pha
Liều được nhập
Kết quả tốc độ truyền mL/h
Cảnh báo
```

Cảnh báo bắt buộc với Noradrenaline:

```text
Không nhầm dung dịch Noradrenaline 4 mg/40 mL (100 mcg/mL)
với Noradrenaline 4 mg/100 mL (40 mcg/mL).
```

---

### 6.7. Lưu calculation snapshot

Khi người dùng bấm `Lưu kết quả`, hệ thống phải lưu:

```text
Thông tin bệnh nhân tại thời điểm tính
Các chỉ số nền đã tính
Kết quả máy thở
Kết quả thuốc
Kết quả vận mạch
Người thực hiện tính
Thời điểm tính
Phiên bản calculation engine
```

Lý do phải lưu snapshot:

```text
Nếu case được đổi cân nặng từ 65 kg thành 70 kg ở ngày sau,
lần tính cũ vẫn phải hiển thị kết quả đã tính theo cân nặng 65 kg.
```

---

## 7. Các màn hình phải làm trong MVP

| Mã màn hình | Tên màn hình | Mục đích |
|---|---|---|
| AUTH-01 | Đăng nhập | Xác thực user |
| PAT-01 | Danh sách case | Xem, tìm kiếm, tạo case |
| PAT-02 | Tạo/chỉnh sửa case | Nhập thông tin bệnh nhân |
| DASH-01 | Tổng quan | BMI, PBW, IBW, dịch, MAC |
| DASH-02 | Gợi ý máy thở | Vt, RR, PEEP, FiO₂, I:E |
| DASH-03 | Gợi ý gây mê | Khởi mê, giãn cơ, hóa giải |
| DASH-04 | Gợi ý vận mạch | Bolus, pha thuốc, tốc độ truyền |
| CAL-01 | Lịch sử tính toán | Xem lại snapshot cũ |
| SET-01 | Hồ sơ cá nhân | Thông tin user và đăng xuất |

---

## 8. Yêu cầu responsive

### Mobile: 320px đến 767px

- Dashboard hiển thị một cột.
- Có header ở trên.
- Có drawer menu.
- Có bottom navigation.
- Nút bấm có kích thước dễ thao tác bằng ngón tay.
- Số liều, đơn vị và cảnh báo không bị cắt.
- Card có khoảng cách đủ để đọc nhanh trong môi trường lâm sàng.

### Tablet: 768px đến 1023px

- Dashboard hiển thị một hoặc hai cột tùy nội dung.
- Sidebar có thể thu gọn.

### Desktop: từ 1024px

- Sidebar cố định bên trái.
- Dashboard hiển thị hai hoặc ba cột.
- Bảng thuốc có thể hiển thị thêm thông tin như cảnh báo và nguồn.

---

## 9. Những chức năng không được làm trong MVP

Các chức năng dưới đây để Phase 2 hoặc Phase 3:

```text
Gây tê tủy sống.
Gây tê ngoài màng cứng.
CRISIS / cẩm nang cấp cứu quanh mổ.
Trang admin CRUD thuốc.
Trang admin CRUD nguồn tài liệu.
Import Excel trực tiếp từ giao diện.
Export PDF.
PWA và offline mode.
Dark mode.
Thông báo thời gian thực.
Đa ngôn ngữ.
Đăng ký tài khoản công khai.
Chia sẻ case giữa nhiều bác sĩ.
Triển khai production.
```

Không thêm những phần này khi MVP chưa hoàn thành.

---

## 10. Golden test case từ Excel

Case mẫu dùng để kiểm thử calculation engine:

```text
Mã case: DEMO-001
Tuổi: 80
Giới: Nữ
Chiều cao: 150 cm
Cân nặng thực: 65 kg
ASA: II
Thai kỳ: Không
Bệnh cảnh hô hấp: Thường quy
```

Kết quả cần đối chiếu với Excel:

```text
BMI: 28.9 kg/m²
PBW: 43.3 kg
AdjBW: 52 kg
Cân nặng máy thở: 43.3 kg
Dịch duy trì 4-2-1: 105 mL/giờ
Vt bảo vệ phổi: 260–303 mL
RR: 14 lần/phút
PEEP: 5 cmH₂O
FiO₂: 40–60%
```

Đây sẽ là unit test bắt buộc khi viết Laravel Calculator Services.

---

## 11. Điều kiện hoàn thành MVP

MVP chỉ được đánh dấu hoàn thành khi demo được đầy đủ kịch bản sau:

1. Đăng nhập bằng tài khoản doctor.
2. Tạo case `DEMO-001`.
3. Nhập đúng golden test case.
4. Bấm tính toán.
5. Kết quả chỉ số nền khớp Excel.
6. Xem được gợi ý máy thở.
7. Xem được gợi ý thuốc gây mê.
8. Xem được gợi ý vận mạch.
9. Lưu được calculation snapshot.
10. Sửa cân nặng của case sau đó.
11. Mở lịch sử tính cũ và vẫn thấy kết quả theo cân nặng cũ.
12. Doctor khác không được truy cập case của doctor đầu tiên.
13. Giao diện sử dụng được ở màn hình mobile và desktop.

---

## 12. Cảnh báo lâm sàng bắt buộc

Tất cả màn hình hiển thị liều thuốc, tốc độ truyền hoặc thông số máy thở phải
có cảnh báo sau:

> Kết quả chỉ mang tính hỗ trợ tham khảo. Bác sĩ gây mê cần đánh giá toàn diện
> bệnh nhân và quyết định cuối cùng dựa trên tình trạng lâm sàng, phác đồ của
> cơ sở và tài liệu chuyên môn hiện hành.
