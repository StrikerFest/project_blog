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

users

```
user_id (Primary Key)
username
email
password
role (e.g., 'admin', 'author', 'editor')
created_at
updated_at
deleted_at
```
- phần role ENUM chỉ là string bình thường thôi, nhưng mà nếu điền giá trị không giống trong mảng đấy thì mysql không cho điền
```mysql
CREATE TABLE IF NOT EXISTS `users` (
   `user_id` INT AUTO_INCREMENT PRIMARY KEY,
   `email` VARCHAR(255) NOT NULL,
   `username` VARCHAR(50) NOT NULL UNIQUE,
   `password` VARCHAR(60) NOT NULL,
   `role` ENUM('admin', 'author', 'editor', 'reader') NOT NULL,
   `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
   `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
   `bio` TEXT DEFAULT NULL,
   `profile_image` VARCHAR(255) DEFAULT NULL,
   `deleted_at` TIMESTAMP NULL DEFAULT NULL
);
```

posts

```
post_id (Primary Key)
author_id (Foreign Key to Users)
title
content
like
status (e.g., 'draft', 'pending_approval', 'approved', 'published')
published_at (nullable)
created_at
updated_at
```
```mysql
CREATE TABLE IF NOT EXISTS `posts` (
   `post_id` INT AUTO_INCREMENT PRIMARY KEY,
   `author_id` INT,
   `title` VARCHAR(255) NOT NULL,
   `content` TEXT NOT NULL,
   `banner_path` VARCHAR(255) DEFAULT NULL,
   `thumbnail_path` VARCHAR(255) DEFAULT NULL,
   `likes` INT DEFAULT 0,
   `status` ENUM('draft', 'pending_approval', 'approval_retracted', 'approval_denied', 'approved', 'published_retracted', 'published') NOT NULL,
   `published_at` TIMESTAMP NULL,
   `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
   `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
   FOREIGN KEY (author_id) REFERENCES users(user_id)
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
-- Create the approval_logs table
CREATE TABLE IF NOT EXISTS `approval_logs` (
   `approval_id` INT AUTO_INCREMENT PRIMARY KEY,
   `post_id` INT,
   `user_id` INT,
   `status_from` ENUM('draft', 'pending_approval', 'approval_retracted', 'approval_denied', 'approved', 'published_retracted', 'published') NOT NULL,
   `status_to` ENUM('draft', 'pending_approval', 'approval_retracted', 'approval_denied', 'approved', 'published_retracted', 'published') NOT NULL,
   `reason` TEXT NULL,
   `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
   FOREIGN KEY (`post_id`) REFERENCES `posts`(`post_id`),
   FOREIGN KEY (`user_id`) REFERENCES `users`(`user_id`)
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
    FOREIGN KEY (post_id) REFERENCES posts(post_id),
    FOREIGN KEY (user_id) REFERENCES users(user_id)
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
    slug VARCHAR(150) NOT NULL,
    status ENUM('enabled', 'disabled') NOT NULL,
    description TEXT,
    position INT NOT NULL DEFAULT 0
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
    FOREIGN KEY (post_id) REFERENCES posts(post_id),
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
    name VARCHAR(100) NOT NULL,
    slug VARCHAR(150) NOT NULL,
    status ENUM('enabled', 'disabled') NOT NULL,
    position INT NOT NULL DEFAULT 0
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
    FOREIGN KEY (post_id) REFERENCES posts(post_id),
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
    FOREIGN KEY (user_id) REFERENCES users(user_id)
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
    FOREIGN KEY (post_id) REFERENCES posts(post_id),
    FOREIGN KEY (changed_by) REFERENCES users(user_id)
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

post_likes
```mysql
CREATE TABLE IF NOT EXISTS `post_likes` (
`like_id` INT AUTO_INCREMENT PRIMARY KEY,
`user_id` INT NOT NULL,
`post_id` INT NOT NULL,
`liked_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
FOREIGN KEY (`user_id`) REFERENCES `users`(`user_id`),
FOREIGN KEY (`post_id`) REFERENCES `posts`(`post_id`),
UNIQUE (`user_id`, `post_id`)
);
```


```mysql
-- Create the banner_types table with deleted_at column
CREATE TABLE IF NOT EXISTS `banner_types` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(100) NOT NULL,
  `description` VARCHAR(255),
  `deleted_at` TIMESTAMP NULL DEFAULT NULL
);

-- Create the banners table with deleted_at column
CREATE TABLE IF NOT EXISTS `banners` (
     `id` INT AUTO_INCREMENT PRIMARY KEY,
     `title` VARCHAR(255) NOT NULL,
     `image_path` VARCHAR(255) NOT NULL,
     `text` TEXT,
     `link` VARCHAR(255),
     `start_date` DATETIME NOT NULL,
     `end_date` DATETIME NOT NULL,
     `is_active` BOOLEAN DEFAULT TRUE,
     `type_id` INT,
     `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
     `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
     `deleted_at` TIMESTAMP NULL DEFAULT NULL,
     FOREIGN KEY (`type_id`) REFERENCES `banner_types`(`id`)
);

-- Insert data into banner_types table
INSERT INTO `banner_types` (`name`, `description`) VALUES
   ('Header', 'Banners displayed at the top of the page'),
   ('Sidebar', 'Banners displayed on the sidebar of the page'),
   ('Footer', 'Banners displayed at the bottom of the page'),
   ('Inline', 'Banners displayed within the content of the page');

-- Insert data into banners table
INSERT INTO `banners` (`title`, `image_path`, `text`, `link`, `start_date`, `end_date`, `is_active`, `type_id`) VALUES
    ('Get the Latest Tech Gadgets!', '/assets/uploads/banner_gadgets.jpg', 'Discover the newest gadgets and electronics on our blog. Click here to read more!', 'https://techblog.com/gadgets', '2024-08-01 00:00:00', '2024-12-31 23:59:59', TRUE, 1),
    ('Top 10 Coding Practices', '/assets/uploads/banner_coding.jpg', 'Enhance your coding skills with our top 10 coding practices. Click here to learn more!', 'https://techblog.com/coding-practices', '2024-08-01 00:00:00', '2024-12-31 23:59:59', TRUE, 2),
    ('Join Our Webinar', '/assets/uploads/banner_webinar.jpg', 'Join our free webinar on the future of AI. Click here to register!', 'https://techblog.com/webinar', '2024-08-01 00:00:00', '2024-08-31 23:59:59', TRUE, 3),
    ('Latest Tech Reviews', '/assets/uploads/banner_reviews.jpg', 'Read our latest reviews on the newest tech products. Click here to check them out!', 'https://techblog.com/reviews', '2024-08-01 00:00:00', '2024-11-30 23:59:59', TRUE, 4),
    ('Subscribe to Our Newsletter', '/assets/uploads/banner_newsletter.jpg', 'Stay updated with the latest tech news. Subscribe to our newsletter!', 'https://techblog.com/newsletter', '2024-08-01 00:00:00', '2024-12-31 23:59:59', TRUE, 1),
    ('Cybersecurity Tips', '/assets/uploads/banner_cybersecurity.jpg', 'Protect yourself online with our top cybersecurity tips. Click here to read more!', 'https://techblog.com/cybersecurity-tips', '2024-08-01 00:00:00', '2024-10-31 23:59:59', TRUE, 2);


```

# Work process

## Done
- Create base database schema
- Create simple CRUD for post
- Create simple CRUD for category, tag
- Create simple login logic
- Separate admin and user site
- Add proper structure for folder management
- Improve database structure for all table
- Improve site user interface for both admin and user
- Improve authentication system
- Improve CRUD for post using the new database schema
- Add post approval system
- Add comment system

# Planning - Fulfill
- Add view post by category and task system
- Add search post by keyword

- Add user management on admin site
- Add change user profile system

- Add like system for login user
+ Add like-post table

- Add approval log

- Add banner management 
+ Add banner table and banner-post table

# Extra planning
- Add activities log

- Add post histories

