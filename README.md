# Trang blog đơn giản
Một trang web đơn giản được thiết kế để tạo dựng một hệ thống Blog CRUD đơn giản, sử dụng PHP thuần, HTML, CSS, JS và MySQL cho cơ sở dữ liệu.
![image](https://github.com/StrikerFest/project_blog/assets/72716233/67e553ac-503a-471d-8a9e-47a46535352a)

## Chức năng
Đơn giản và gọn nhẹ
Cho phép người dùng quản lý blog (xem, sửa, xóa)
Sử dụng cơ sở dữ liệu MySQL để lưu trữ các bài posts.

## Thiết lập
### Yêu cầu
- PHP
- MySQL
- Web server (xampp)

### Installation

1. Cài xampp:
- Check phiên bản PHP hiện tại:
+ Vào terminal: gõ `php -v` sẽ ra kết quả tương đương nếu đã cài PHP, bên dưới là ví dụ cho phiên bản 8.1

```
PHP 8.1.12 (cli) (built: Oct 25 2022 18:16:21) (ZTS Visual C++ 2019 x64)
Copyright (c) The PHP Group
Zend Engine v4.1.12, Copyright (c) Zend Technologies
    with Xdebug v3.2.2, Copyright (c) 2002-2023, by Derick Rethans    
```

- Nếu lỗi hoặc chưa cài PHP thì xem hướng dẫn trên mạng để cài đặt biến môi trường của PHP hoặc cài thẳng PHP nếu chưa có, như một vài link sau
+ https://www.geeksforgeeks.org/how-to-install-php-in-windows-10/
+ https://tuhocict.com/cai-dat-moi-truong-phat-trien-ung-dung-php/

- Tải xampp: https://www.apachefriends.org/download.html
+ Cài đặt xampp theo bộ cài
+ Trước khi bật server, vào C:\Windows\System32\drivers\etc\hosts
+ Mở host bằng notepad hoặc ứng dụng tương đương như vscode, sublime text, vim,...
+ Thêm vào cuối file như sau, và lưu
```
127.0.0.1   www.projectblog.com
```
- Trường hợp bị lỗi quyền sửa host có thể chỉnh tạm thời:
+ Chuột phải vào host
+ Nhấn vào Properties
+ Vào tab Security
+ Nhấn ô Edit
+ Ở trong khung group or user names, nhấn vào Users (...)
+ Ở cột Allow, check ô full control, apply xong ok.
+ Sửa file host xong chỉnh lại quyền là được, quyền user mặc định là read và read and execute.

- Xong trong thư mục xampp vừa cài đặt, vào đường dẫn sau `xampp\apache\conf\extra`
+ Bên trong mở `httpd-vhosts.conf`
+ Thêm vào cuối file, chỉ đổ DocumentRoot là chỗ chứa project đã git clone về (Xem clone ở bước 2)
```
<VirtualHost *:80>
    ServerName www.projectblog.com
    ServerAlias projectblog.com
    DocumentRoot g:/softwares/xampp/htdocs/Blog
</VirtualHost>
```

- Xong mở xampp bằng xampp-control.exe trong thư mục xampp đã cài
+ Bấm nút start bên cạnh module Apache và module Mysql
+ Thành công

2. Clone repo:
- Clone repo vào thư mục `xampp/htdocs/`
- Nó sẽ hiển thị ví dụ như `g:/softwares/xampp/htdocs/Blog`

```
git clone https://github.com/StrikerFest/project_blog.git
```

3. Tạo cơ sở dữ liệu
- Tạo một cơ sở dữ liệu tên là `project_blog` trong MySQL
- Sau khi bật web server xampp, vào link 
```
http://localhost/phpmyadmin/index.php?route=/server/databases
```
- Ở dưới trường `Create database`, điền `project_blog`, nhấn create, xong.

4. Thiết lập biến môi trường dự án và thiết lập cơ sở dữ liệu:
- Trong project, vào file env.php ở ngoài cùng dự án
- Thay đổi giá trị sau tùy thuộc vào máy từng người
```
$_ENV['DB_HOST'] = 'localhost';
$_ENV['DB_USER'] = 'root';
$_ENV['DB_PASS'] = '';
$_ENV['DB_NAME'] = 'project_blog';
```
- Thường thì các giá trị trên là mặc định rồi nên không cần thay đổi nếu mình chưa chỉnh sửa chúng

Vào trang web `http://www.projectblog.com/admin/post`
Nếu vào được thì sẽ là thành công, nhưng trước hết phải vào url sau để chạy câu lệnh SQL đi kèm với project và cài đặt các bảng của cơ sở dữ liệu
```
http://www.projectblog.com/database/install.php
```

- Sau đó tạo bảng user và chạy seed để tạo user
```
http://www.projectblog.com/database/installUser.php
http://www.projectblog.com/database/seedUser.php
```

Đây là tài khoản admin:
```
TK: admin
MK: admin
```

Đây là tài khoản user:
```
TK: user
MK: user
```

- Nếu muốn có dữ liệu linh tinh truyền vào cơ sở dữ liệu để có dữ liệu thì vào
```
http://www.projectblog.com/database/seed.php
```

- Xong vào lại `http://www.projectblog.com/admin/post` để xem kết quả

### Chức năng sử dụng

#### Quản lý blog:
- Xem thêm sửa xóa block

# Người đóng góp
- Trịnh Thế Anh
- Phạm Quang Anh
- Thành viên 2
- Thành viên 3
- Thành viên 4

# Người hướng dẫn
- Thầy Nguyễn Quang Minh

# Giấy phép
- Không
