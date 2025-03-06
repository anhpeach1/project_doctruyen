# Doctruyen

Ứng dụng web để đọc truyện, tiểu thuyết, và truyện tranh trực tuyến. Doctruyen cung cấp giao diện thân thiện cho người đọc để khám phá và thưởng thức nội dung.

## Tính năng

- Hệ thống xác thực người dùng
- Duyệt truyện theo danh mục
- Đánh dấu truyện yêu thích
- Chức năng tìm kiếm

## Cài đặt

```bash
# Sao chép kho lưu trữ
git clone https://github.com/yourusername/doctruyen.git

# Di chuyển đến thư mục dự án
cd doctruyen

# Cài đặt các phụ thuộc
composer install
npm install

# Sao chép tệp môi trường
cp .env.example .env

# Tạo khóa ứng dụng
php artisan key:generate

# Cấu hình cơ sở dữ liệu trong tệp .env
# Sau đó chạy migrations
php artisan migrate

# Biên dịch tài nguyên
npm run dev
```

## Sử dụng

```bash
# Khởi động máy chủ phát triển cục bộ
php artisan serve
```

Truy cập `http://localhost:8000` trong trình duyệt của bạn để sử dụng ứng dụng.

## Công nghệ

- Laravel
- MySQL
- Vue.js/React (Framework Frontend)
- Tailwind CSS

## Hình ảnh trang web
### Đăng nhập 
![Screenshot 2025-02-27 172814](https://github.com/user-attachments/assets/311018d5-3c3a-4399-b5cd-30d4d624cf18)
### Giao diện người dùng
![Screenshot_27-2-2025_17470_127 0 0 1](https://github.com/user-attachments/assets/33ff31a5-425c-420c-9f63-617b9f329f09)
![Screenshot_27-2-2025_173618_127 0 0 1](https://github.com/user-attachments/assets/68dd0407-037d-4887-94c8-cceb3ad7fe11)
![Screenshot_27-2-2025_173143_127 0 0 1](https://github.com/user-attachments/assets/1f585665-6272-4bf5-ae6f-aa5d259536ed)
![Screenshot_27-2-2025_17508_127 0 0 1](https://github.com/user-attachments/assets/919070a1-eee8-4365-980b-da608698a7f9)
### Giao diện admin
![Screenshot 2025-02-27 174040](https://github.com/user-attachments/assets/2948ba40-4249-47ca-b2f4-d6c945ac1b1f)
![Screenshot_27-2-2025_175258_127 0 0 1](https://github.com/user-attachments/assets/9c007704-fd32-4422-b17a-37c170fdd5d9)



### 

## Giấy phép

Phân phối theo Giấy phép MIT. Xem `LICENSE` để biết thêm thông tin.

