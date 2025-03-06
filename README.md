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
git clone https://github.com/anhpeach1/project_doctruyen

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

Truy cập `anhpeach.studio` trong trình duyệt của bạn để sử dụng ứng dụng.

## Công nghệ

- Laravel
- MySQL
- Vue.js/React (Framework Frontend)
- Tailwind CSS

## Hình ảnh trang web
### Đăng nhập 
![image](https://github.com/user-attachments/assets/f1b33a11-b048-4f9d-984a-9be36af1fa28)

### Giao diện người dùng
![image](https://github.com/user-attachments/assets/2ec47c7d-9fe6-4358-8bd8-f6303b9ea33b)

![image](https://github.com/user-attachments/assets/8f989600-10f4-4817-91ca-f2aa15c355b5)

![image](https://github.com/user-attachments/assets/4f03c838-1b39-48d5-bb58-eac6cb07eef8)

![image](https://github.com/user-attachments/assets/6ac12192-9242-4ce3-ac92-6a3b5e491498)

![image](https://github.com/user-attachments/assets/5aa52189-f126-4708-b1eb-5732d0d4636a)

![image](https://github.com/user-attachments/assets/d087ed14-f56c-40be-8720-6ad4fa2264ea)


### Giao diện admin
![image](https://github.com/user-attachments/assets/7d2f5a9a-37ac-4b7f-8b38-5ceb161e0ec5)

![image](https://github.com/user-attachments/assets/53aacb07-dfde-45f7-ad0c-3da54b85d867)

![image](https://github.com/user-attachments/assets/39a54ae9-9ea9-45e5-8980-7a4ff31f235e)

![image](https://github.com/user-attachments/assets/606a00fe-9f8e-446c-967b-602d242307f3)


### 

## Giấy phép

Phân phối theo Giấy phép MIT. Xem `LICENSE` để biết thêm thông tin.

