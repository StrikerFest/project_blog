## Giới thiệu chung
Burogu là một trang web đơn giản được thiết kế với nghiệp vụ phổ thông cơ bản cho một trang blog với mọi nội dung, được xây dựng từ đầu.

Truy cập trang web trực tiếp ở đây (frontend): http://burogu.lovestoblog.com/

## Công nghệ sử dụng - Tech stack 
- HTML, CSS cho giao diện
- JS và Jquery để xử lý script và giao diện động
- PHP cho xử lý code backend
- PHPMailer build từ Composer để hỗ trợ xử lý gửi email

## Cấu trúc lập trình
- Đồ án được xây dựng theo mô hình MVC (model view controller)
- Router tự build, khi chuyển hướng sẽ chuyển về controller tương ứng, controller sẽ xử lý logic code, gọi hàm từ model và hiển thị ra view
- Model sẽ chứa các hàm xử lý liên quan đến làm việc trực tiếp với cơ sở dữ liệu
- View sẽ hiển thị giao diện cho người dùng

## Phi Chức năng
- Đơn giản và gọn nhẹ
- Chuẩn nghiệp vụ cơ bản
- Bảo mật với frontend js validate, backend validate và hash password
- Linh hoạt, có thể tùy chỉnh nội dung và style blog

## Chức năng

### Chung

#### Đăng nhập - Đăng ký
- Admin và độc giả đều có thể đăng nhập ở trang đăng nhập được xác định của mình
- Chỉ có một Admin chính, là người duy nhất có thể tạo tài khoản mới cho người viết hoặc người kiểm duyệt
- Không thể tạo mới một admin chính khác
- Độc giả có thể đăng ký tài khoản mới với các thông tin cơ bản để thực hiện các thao tác yêu cầu đăng nhập như thích bài viết hoặc bình luận

### Chỉnh sửa thông tin cá nhân

- Admin, người viết và người kiểm duyệt có thể chỉnh sửa thông tin cá nhân của mình ở trang hồ sơ ngoại trừ chức vụ đã được tạo bời admin từ đầu
- Độc giả có thể thay đổi thông tin cá nhân của mình ở trang hô sơ bên người đọc

### Độc giả (Người đọc)

#### Tìm kiếm bài viết
- Độc giả sẽ được điều hướng đến trang chủ, nơi những bài viết mới nhất được hiển thị
- Độc giả có thể tìm kiếm bài viết theo danh mục hoặc thẻ mà mình mong muôn
- Độc giả còn có thể sử dụng thanh tìm kiếm để tìm bài viết, danh mục, thẻ dựa theo từ khóa của mình

#### Xem bài viết
- Độc giả có thể nhấn vào thẻ bài viết để bắt đầu xem bài viết của mình

#### Like bài viết
- Độc giả có thể thể hiện sự ủng hộ của mình với tác giả của bài viết mà mình yêu thích
- Cần độc giả đămg nhập trước

#### Comment bài viết
- Độc giả có thể thể hiện ý kiến của mình qua phần bình luận của bài viết
- Cần độc giả đăng nhập trước

### Tác giả (Người viết)

#### Tạo bài viết

- Tác giả có thể tạo bài viết mới qua chức năng tạo bài viết ở trang admin
- Khi bài viết được tạo từ đầu sẽ có trạng thái nháp, và cần được chuyển sang chờ kiểm duyệt và đáp ứng một kiểm duyệt viên để bài viết bắt đầu được kiểm duyệt chất lượng

#### Quản lý thành phần bài viết

- Tác giả cũng có thể quản lý các thành phần bài viết như danh mục, thẻ ngoại trừ việc xóa

### Kiểm duyệt viên (Người kiểm duyệt bài viết)

#### Kiểm duyệt bài viết

- Kiểm duyệt viên chỉ có thể kiểm duyệt các bài viết đã được yêu cầu kiểm duyệt bải người viết
- Bài viết có thể được xác nhận kiểm duyệt hoặc từ chối và chuyển lại cho người viết với trường lý do được ghi kèm
- Khi bài viết được kiểm duyệt, nó sẽ được sẵn sàng để phát hành ra cho các độc giả
- Sau khi kiểm duyệt lần cuối, bài viết sẽ được phát hành
- Đây là nghiệp vụ cơ bản nhất về phần kiểm duyệt của một trang blog

### Admin (Chủ blog)

#### Tất cả các quyền và chức năng của người viết và người kiểm duyệt

- Admin là người có quyền hạn cao nhất, có thể tạo, kiểm duyệt và phát hành bài viết không giới hạn
- Chỉ có một admin duy nhất

#### Quản lý người dùng
- Ngoài ra admin còn có quyền được xem và quản lý người dùng website của mình
- Admin có thể tạo tài khoản mới hoặc chỉnh sửa tất cả thông tin người viết hoặc người kiểm duyệt ngoại trừ mật khẩu

## Thiết lập ( Dành cho dev)
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

- Nếu bị lỗi forbidden hay quyền thì thêm phần này vào ở dưới file luôn
```
<Directory "g:/softwares/xampp/htdocs/Blog">
        Options Indexes FollowSymLinks MultiViews
        AllowOverride all
        Order Deny,Allow
        Allow from all
        Require all granted
</Directory>

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

Nhập file cơ sở dữ liệu

Đây là tài khoản admin:
```
TK: admin
MK: adminpass
```

# Người đóng góp
- Trịnh Thế Anh - Code chính - DB 
- Phạm Quang Anh - Code hỗ trợ
- Trần Tuấn Anh - Tài liệu - Quản lý chất lượng
- Ngô Đức Chính - Tài liệu - Quản lý chất lượng
- Thành viên 4

# Người hướng dẫn
- Thầy Nguyễn Quang Minh

