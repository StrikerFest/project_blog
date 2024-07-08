# Chức năng chính

---

## Quản lý nhân viên

### Phân quyền
+ Tạo 3 chức vụ: Writer, Editor, Admin
+ Phân quyền cho nhân viên
+ Writer có thể tạo bài post mới, sau khi tạo post sẽ có thể submit, đợi editor duyệt
+ Editor có quyền duyệt bài và thay đổi tag, thể loại nhưng không thay đổi nội dung blog
+ Admin có full quyền, và cả thêm nhân viên nữa

---

## Quản lý blog

### Tạo blog
+ Writer sau khi tạo một blog mới, submit để editor check, sửa tag và thể loại nếu cần, và ấn nút Duyệt bài viết
+ Editor có thể yêu cầu thay đổi bài viết, điền vào trường note và ấn nút Kiểm tra lại
+ Sau khi duyệt, bài viết sẽ hiển thị cho khách xem

### Check log duyệt
+ Các nhân viên có thể vào xem log duyệt của editor và admin
+ Khi writer tạo bài hoặc editor hay admin duyệt hay kéo lại bài thì sẽ có một trường được thêm ở bảng approval_logs
+ Chỉ để check log kiểm tra

---

## Quản lý thể loại

---

## Quản lý tag

---

## Check log hoạt động
+ Khi có một user bên admin thực hiện một hành động nào đó, nó sẽ được log ở trong bảng activity_history
+ Cụ thể là các hành động ảnh hưởng đến cơ sở dữ liệu như tạo sửa xóa post, tag, thể loại, nhân viên,...
+ Chỉ để check log kiểm tra

---

## Lịch sử bài viết
+ Nhân viên có thể xem lịch sử bài viết ở đây
+ Khi một bài post được tạo ra, một trường mới sẽ được tạo ở đây
+ Khi Writer thay đổi nội dung bài viết thì sau khi submit cho Editor thì trường ở trên sẽ được cập nhật và lưu nội dung cũ
+ Hiện chỉ dùng để kiểm tra lịch sử bài viết nhưng có thể được dùng thể khôi phục lại nội dung một bài viết cũ

---

## Các tính năng khác
+ Có một bảng options được tạo ra để lưu giá trị cho một tính năng nhất định
+ Ví dụ như ẩn bài viết cũ hơn (GIÁ TRỊ) ngày, có thể lưu vào trường đó giá trị ngày và bật nó lên để ẩn, không tắt đi để tắt tính năng
+ Cái này chỉ sử dụng khi mình tạo một tính năng mà admin có thể thay đổi giá trị

# Bảng cơ sở dữ liệu

user

```
user_id (Primary Key)
username
email
password
role (e.g., 'admin', 'author', 'editor')
created_at
updated_at
```
```mysql
CREATE TABLE user (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL,
    email VARCHAR(100) NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'author', 'editor','reader') NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);
```

post

```
post_id (Primary Key)
author_id (Foreign Key to Users)
title
content
like
status (e.g., 'draft', 'pending_approval', 'approved', 'published')
approved_by (Foreign Key to Users, nullable)
published_at (nullable)
created_at
updated_at
```
```mysql
CREATE TABLE post (
    post_id INT AUTO_INCREMENT PRIMARY KEY,
    author_id INT,
    title VARCHAR(255) NOT NULL,
    content TEXT NOT NULL,
    likes INT DEFAULT 0,
    status ENUM('draft', 'pending_approval', 'approved', 'published') NOT NULL,
    approved_by INT NULL,
    published_at TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (author_id) REFERENCES user(user_id),
    FOREIGN KEY (approved_by) REFERENCES user(user_id)
);
```

approval_logs

```
approval_id (Primary Key)
post_id (Foreign Key to BlogPosts)
user_id (Foreign Key to Users)
action (e.g., 'approved', 'rejected')
reason (nullable)
created_at
```
```mysql
CREATE TABLE approval_logs (
    approval_id INT AUTO_INCREMENT PRIMARY KEY,
    post_id INT,
    user_id INT,
    action ENUM('approved', 'rejected') NOT NULL,
    reason TEXT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (post_id) REFERENCES post(post_id),
    FOREIGN KEY (user_id) REFERENCES user(user_id)
);
```

comments

```
comment_id (Primary Key)
post_id (Foreign Key to BlogPosts)
user_id (Foreign Key to Users)
content
created_at
updated_at
```
```mysql
CREATE TABLE comments (
    comment_id INT AUTO_INCREMENT PRIMARY KEY,
    post_id INT,
    user_id INT,
    content TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (post_id) REFERENCES post(post_id),
    FOREIGN KEY (user_id) REFERENCES user(user_id)
);
```

categories

```
category_id (Primary Key)
name
status
description
```
```mysql
CREATE TABLE categories (
    category_id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    status ENUM('enabled', 'disabled') NOT NULL,
    description TEXT
);
```

post_categories (trung gian)

```
post_id (Foreign Key to BlogPosts)
category_id (Foreign Key to Categories)
PRIMARY KEY (post_id, category_id)
```
```mysql
CREATE TABLE post_categories (
    post_id INT,
    category_id INT,
    PRIMARY KEY (post_id, category_id),
    FOREIGN KEY (post_id) REFERENCES post(post_id),
    FOREIGN KEY (category_id) REFERENCES categories(category_id)
);
```

tags

```
tag_id (Primary Key)
status
name
```
```mysql
CREATE TABLE tags (
    tag_id INT AUTO_INCREMENT PRIMARY KEY,
    status ENUM('enabled', 'disabled') NOT NULL,
    name VARCHAR(100) NOT NULL
);
```

post_tags (trung gian)

```
post_id (Foreign Key to BlogPosts)
tag_id (Foreign Key to Tags)
PRIMARY KEY (post_id, tag_id)
```
```mysql
CREATE TABLE post_tags (
    post_id INT,
    tag_id INT,
    PRIMARY KEY (post_id, tag_id),
    FOREIGN KEY (post_id) REFERENCES post(post_id),
    FOREIGN KEY (tag_id) REFERENCES tags(tag_id)
);
```

activity_logs

```
log_id (Primary Key)
user_id (Foreign Key) 
action ( tên hành động )
entity ( thực thể bị ảnh hưởng - bảng bị thay đổi ) 
timestamp ( thời gian )
```
```mysql
CREATE TABLE activity_logs (
    log_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    action VARCHAR(255) NOT NULL,
    entity VARCHAR(255) NOT NULL,
    timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES user(user_id)
);
```

post_history

```
history_id (Primary Key)
post_id (Foreign Key)
changed_by
change_type
timestamp
previous_content
current_content
```
```mysql
CREATE TABLE post_history (
    history_id INT AUTO_INCREMENT PRIMARY KEY,
    post_id INT,
    changed_by INT,
    change_type VARCHAR(50) NOT NULL,
    timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    previous_content TEXT,
    current_content TEXT,
    FOREIGN KEY (post_id) REFERENCES post(post_id),
    FOREIGN KEY (changed_by) REFERENCES user(user_id)
);
```

option

```mysql
CREATE TABLE options (
    option_id INT AUTO_INCREMENT PRIMARY KEY,
    option_name VARCHAR(255) NOT NULL,
    option_value TEXT NOT NULL,
    status ENUM('enabled', 'disabled') NOT NULL,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);
```