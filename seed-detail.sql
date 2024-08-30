INSERT INTO `categories` (`name`, `slug`, `status`, `description`, `position`, `created_at`, `updated_at`)
VALUES
    ('Lập trình', 'lap-trinh', 'enabled', 'Tất cả về các ngôn ngữ và kỹ thuật lập trình.', 1, NOW(), NOW()),
    ('Phát triển Web', 'phat-trien-web', 'enabled', 'Các chủ đề liên quan đến phát triển web.', 2, NOW(), NOW()),
    ('Khoa học Dữ liệu', 'khoa-hoc-du-lieu', 'enabled', 'Những hiểu biết về phân tích dữ liệu và học máy.', 3, NOW(), NOW()),
    ('Bảo mật', 'bao-mat', 'enabled', 'Tất cả về việc bảo vệ thông tin số.', 4, NOW(), NOW()),
    ('Kỹ thuật Phần mềm', 'ky-thuat-phan-mem', 'enabled', 'Các phương pháp và thực hành phát triển phần mềm.', 5, NOW(), NOW()),
    ('Điện toán Đám mây', 'dien-toan-dam-may', 'enabled', 'Thông tin về các công nghệ và dịch vụ đám mây.', 6, NOW(), NOW()),
    ('Trí tuệ Nhân tạo', 'tri-tue-nhan-tao', 'enabled', 'Các chủ đề liên quan đến AI và học máy.', 7, NOW(), NOW()),
    ('Mạng', 'mang', 'enabled', 'Các khái niệm và giao thức mạng.', 8, NOW(), NOW()),
    ('Quản lý Cơ sở dữ liệu', 'quan-ly-co-so-du-lieu', 'enabled', 'Quản lý và thiết kế cơ sở dữ liệu.', 9, NOW(), NOW()),
    ('DevOps', 'devops', 'enabled', 'Các thực hành kết hợp phát triển phần mềm và vận hành CNTT.', 10, NOW(), NOW());
#------------------------------------------------------------------------------------------------------------------------------------------------------------------
INSERT INTO `tags` (`name`, `slug`, `status`, `position`, `created_at`, `updated_at`)
VALUES
    ('AI', 'ai', 'enabled', 1, NOW(), NOW()),
    ('Học máy', 'hoc-may', 'enabled', 2, NOW(), NOW()),
    ('JavaScript', 'javascript', 'enabled', 3, NOW(), NOW()),
    ('Python', 'python', 'enabled', 4, NOW(), NOW()),
    ('Đám mây', 'dam-may', 'enabled', 5, NOW(), NOW()),
    ('Dữ liệu lớn', 'du-lieu-lon', 'enabled', 6, NOW(), NOW()),
    ('Blockchain', 'blockchain', 'enabled', 7, NOW(), NOW()),
    ('Bảo mật', 'bao-mat', 'enabled', 8, NOW(), NOW()),
    ('DevOps', 'devops', 'enabled', 9, NOW(), NOW()),
    ('Docker', 'docker', 'enabled', 10, NOW(), NOW()),
    ('Kubernetes', 'kubernetes', 'enabled', 11, NOW(), NOW()),
    ('SQL', 'sql', 'enabled', 12, NOW(), NOW()),
    ('NoSQL', 'nosql', 'enabled', 13, NOW(), NOW()),
    ('Khoa học dữ liệu', 'khoa-hoc-du-lieu', 'enabled', 14, NOW(), NOW()),
    ('Mạng', 'mang', 'enabled', 15, NOW(), NOW()),
    ('Java', 'java', 'enabled', 16, NOW(), NOW()),
    ('HTML', 'html', 'enabled', 17, NOW(), NOW()),
    ('CSS', 'css', 'enabled', 18, NOW(), NOW()),
    ('PHP', 'php', 'enabled', 19, NOW(), NOW()),
    ('Ruby', 'ruby', 'enabled', 20, NOW(), NOW());
#------------------------------------------------------------------------------------------------------------------------------------------------------------------
-- pass : adminpass
INSERT INTO `users` (`email`, `username`, `password`, `role`, `created_at`, `updated_at`)
VALUES
    ('admin@example.com', 'admin_user', '$2b$12$Xpml31WwZnWoMKcCAMtG2e34FGr/APELzKHPcpHxDVZFIQdau.8xm', 'admin', NOW(), NOW()),
    ('editor1@example.com', 'editor1', '$2b$12$Xpml31WwZnWoMKcCAMtG2e34FGr/APELzKHPcpHxDVZFIQdau.8xm', 'editor', NOW(), NOW()),
    ('editor2@example.com', 'editor2', '$2b$12$Xpml31WwZnWoMKcCAMtG2e34FGr/APELzKHPcpHxDVZFIQdau.8xm', 'editor', NOW(), NOW()),
    ('author1@example.com', 'author1', '$2b$12$Xpml31WwZnWoMKcCAMtG2e34FGr/APELzKHPcpHxDVZFIQdau.8xm', 'author', NOW(), NOW()),
    ('author2@example.com', 'author2', '$2b$12$Xpml31WwZnWoMKcCAMtG2e34FGr/APELzKHPcpHxDVZFIQdau.8xm', 'author', NOW(), NOW()),
    ('author3@example.com', 'author3', '$2b$12$Xpml31WwZnWoMKcCAMtG2e34FGr/APELzKHPcpHxDVZFIQdau.8xm', 'author', NOW(), NOW()),
    ('reader1@example.com', 'reader1', '$2b$12$Xpml31WwZnWoMKcCAMtG2e34FGr/APELzKHPcpHxDVZFIQdau.8xm', 'reader', NOW(), NOW()),
    ('reader2@example.com', 'reader2', '$2b$12$Xpml31WwZnWoMKcCAMtG2e34FGr/APELzKHPcpHxDVZFIQdau.8xm', 'reader', NOW(), NOW()),
    ('reader3@example.com', 'reader3', '$2b$12$Xpml31WwZnWoMKcCAMtG2e34FGr/APELzKHPcpHxDVZFIQdau.8xm', 'reader', NOW(), NOW()),
    ('reader4@example.com', 'reader4', '$2b$12$Xpml31WwZnWoMKcCAMtG2e34FGr/APELzKHPcpHxDVZFIQdau.8xm', 'reader', NOW(), NOW()),
    ('reader5@example.com', 'reader5', '$2b$12$Xpml31WwZnWoMKcCAMtG2e34FGr/APELzKHPcpHxDVZFIQdau.8xm', 'reader', NOW(), NOW()),
    ('reader6@example.com', 'reader6', '$2b$12$Xpml31WwZnWoMKcCAMtG2e34FGr/APELzKHPcpHxDVZFIQdau.8xm', 'reader', NOW(), NOW()),
    ('reader7@example.com', 'reader7', '$2b$12$Xpml31WwZnWoMKcCAMtG2e34FGr/APELzKHPcpHxDVZFIQdau.8xm', 'reader', NOW(), NOW()),
    ('reader8@example.com', 'reader8', '$2b$12$Xpml31WwZnWoMKcCAMtG2e34FGr/APELzKHPcpHxDVZFIQdau.8xm', 'reader', NOW(), NOW()),
    ('reader9@example.com', 'reader9', '$2b$12$Xpml31WwZnWoMKcCAMtG2e34FGr/APELzKHPcpHxDVZFIQdau.8xm', 'reader', NOW(), NOW()),
    ('reader10@example.com', 'reader10', '$2b$12$Xpml31WwZnWoMKcCAMtG2e34FGr/APELzKHPcpHxDVZFIQdau.8xm', 'reader', NOW(), NOW());
#------------------------------------------------------------------------------------------------------------------------------------------------------------------
INSERT INTO `posts` (`author_id`, `editor_id`, `title`, `slug`, `content`, `banner_path`, `thumbnail_path`, `likes`, `status`, `published_at`, `created_at`, `updated_at`, `deleted_at`)
VALUES
    (1, NULL, 'Giới thiệu về AI', 'gioi-thieu-ve-ai', 'Trí tuệ nhân tạo (AI) là một lĩnh vực của khoa học máy tính nhằm tạo ra các máy móc có khả năng hành vi thông minh. Bài viết này đề cập đến các kiến thức cơ bản về AI và ứng dụng của nó.', NULL, NULL, 100, 'published', NOW(), NOW(), NOW(), NULL),
    (1, NULL, 'Hiểu về Machine Learning', 'hieu-ve-machine-learning', 'Machine Learning (ML) là một phân nhánh của AI cho phép các hệ thống học hỏi và cải thiện từ kinh nghiệm mà không cần lập trình rõ ràng. Bài viết này giải thích các thuật toán ML khác nhau.', NULL, NULL, 100, 'published', NOW(), NOW(), NOW(), NULL),
    (2, NULL, 'Những thực hành tốt nhất trong phát triển web', 'nhung-thuc-hanh-tot-nhat-trong-phat-trien-web', 'Phát triển web bao gồm việc tạo ra và duy trì các trang web. Bài viết này thảo luận về các thực hành tốt nhất trong phát triển web, bao gồm thiết kế, trải nghiệm người dùng và tối ưu hóa hiệu suất.', NULL, NULL, 100, 'published', NOW(), NOW(), NOW(), NULL),
    (2, NULL, 'Giới thiệu về điện toán đám mây', 'gioi-thieu-ve-dien-toan-dam-may', 'Điện toán đám mây cung cấp các dịch vụ máy tính theo yêu cầu qua internet. Bài viết này giới thiệu các khái niệm về điện toán đám mây và các dịch vụ được cung cấp bởi các nhà cung cấp chính.', NULL, NULL, 100, 'published', NOW(), NOW(), NOW(), NULL),
    (3, NULL, 'Khám phá an ninh mạng', 'khám-pha-an-ninh-mang', 'An ninh mạng rất quan trọng để bảo vệ các hệ thống và dữ liệu khỏi các mối đe dọa mạng. Bài viết này bao gồm các khía cạnh chính của an ninh mạng, bao gồm quản lý rủi ro và phòng ngừa mối đe dọa.', NULL, NULL, 100, 'published', NOW(), NOW(), NOW(), NULL),
    (3, NULL, 'Hệ quản trị cơ sở dữ liệu', 'he-quan-tri-co-so-du-lieu', 'Hệ quản trị cơ sở dữ liệu (DBMS) giúp quản lý và tổ chức dữ liệu. Bài viết này khám phá các loại DBMS khác nhau, các tính năng của chúng và các trường hợp sử dụng.', NULL, NULL, 100, 'published', NOW(), NOW(), NOW(), NULL),
    (4, NULL, 'Những điều cơ bản về Blockchain', 'nhung-dieu-co-ban-ve-blockchain', 'Công nghệ Blockchain là nền tảng của các loại tiền điện tử và có ứng dụng ngoài tài chính. Bài viết này cung cấp cái nhìn tổng quan về cách Blockchain hoạt động và các ứng dụng tiềm năng của nó.', NULL, NULL, 100, 'published', NOW(), NOW(), NOW(), NULL),
    (4, NULL, 'Giới thiệu về DevOps', 'gioi-thieu-ve-devops', 'DevOps là một tập hợp các thực hành kết hợp phát triển phần mềm và hoạt động CNTT. Bài viết này giới thiệu các khái niệm DevOps và lợi ích của nó trong phát triển phần mềm hiện đại.', NULL, NULL, 100, 'published', NOW(), NOW(), NOW(), NULL),
    (5, NULL, 'Bắt đầu với Docker', 'bat-dau-voi-docker', 'Docker là một nền tảng để phát triển, vận chuyển và chạy các ứng dụng bên trong các container. Bài viết này cung cấp hướng dẫn cho người mới bắt đầu về Docker và công nghệ container.', NULL, NULL, 100, 'published', NOW(), NOW(), NOW(), NULL),
    (5, NULL, 'Hiểu về Kubernetes', 'hieu-ve-kubernetes', 'Kubernetes là một hệ thống mã nguồn mở để tự động hóa việc triển khai và quản lý các ứng dụng container hóa. Bài viết này giải thích kiến trúc Kubernetes và các trường hợp sử dụng của nó.', NULL, NULL, 100, 'published', NOW(), NOW(), NOW(), NULL);
#------------------------------------------------------------------------------------------------------------------------------------------------------------------
INSERT INTO `post_categories` (`post_id`, `category_id`)
VALUES
    (1, 7), -- Giới thiệu về AI
    (2, 7), -- Hiểu về Machine Learning
    (3, 1), -- Những thực hành tốt nhất trong phát triển web
    (4, 6), -- Giới thiệu về điện toán đám mây
    (5, 4), -- Khám phá an ninh mạng
    (6, 9), -- Hệ quản trị cơ sở dữ liệu
    (7, 7), -- Những điều cơ bản về Blockchain
    (8, 10), -- Giới thiệu về DevOps
    (9, 6), -- Bắt đầu với Docker
    (10, 6); -- Hiểu về Kubernetes
#------------------------------------------------------------------------------------------------------------------------------------------------------------------
INSERT INTO `post_tags` (`post_id`, `tag_id`)
VALUES
    (1, 1), -- Giới thiệu về AI
    (1, 2),
    (2, 2), -- Hiểu về Machine Learning
    (2, 3),
    (3, 4), -- Những thực hành tốt nhất trong phát triển web
    (3, 5),
    (4, 6), -- Giới thiệu về điện toán đám mây
    (4, 7),
    (5, 8), -- Khám phá an ninh mạng
    (5, 9),
    (6, 10), -- Hệ quản trị cơ sở dữ liệu
    (6, 11),
    (7, 12), -- Những điều cơ bản về Blockchain
    (7, 13),
    (8, 14), -- Giới thiệu về DevOps
    (8, 15),
    (9, 16), -- Bắt đầu với Docker
    (9, 17),
    (10, 18), -- Hiểu về Kubernetes
    (10, 19),
    (10, 20);
#------------------------------------------------------------------------------------------------------------------------------------------------------------------
INSERT INTO `comments` (`post_id`, `user_id`, `content`, `created_at`, `updated_at`)
VALUES
    (1, 5, 'Bài viết rất hay, tôi học được nhiều điều mới!', NOW(), NOW()), -- Giới thiệu về AI
    (1, 6, 'Cảm ơn về thông tin bổ ích!', NOW(), NOW()),
    (1, 7, 'Tôi rất thích chủ đề này, mong chờ những bài viết tiếp theo.', NOW(), NOW()),
    (1, 8, 'Có thể bổ sung thêm ví dụ thực tế không?', NOW(), NOW()),
    (1, 9, 'Bài viết rất chi tiết, dễ hiểu.', NOW(), NOW()),
    (2, 5, 'Machine Learning là một chủ đề thú vị, cảm ơn!', NOW(), NOW()), -- Hiểu về Machine Learning
    (2, 6, 'Rất hữu ích cho tôi trong công việc.', NOW(), NOW()),
    (2, 7, 'Có phần nào nói về các ứng dụng thực tế không?', NOW(), NOW()),
    (2, 8, 'Bài viết nên có thêm các ví dụ cụ thể.', NOW(), NOW()),
    (2, 9, 'Tôi mới bắt đầu tìm hiểu về Machine Learning, bài viết này giúp ích rất nhiều.', NOW(), NOW()),
    (3, 5, 'Các thực hành tốt nhất này rất cần thiết trong phát triển web.', NOW(), NOW()), -- Những thực hành tốt nhất trong phát triển web
    (3, 6, 'Bài viết cung cấp nhiều thông tin quý giá.', NOW(), NOW()),
    (3, 7, 'Có thể cung cấp thêm các công cụ phát triển web không?', NOW(), NOW()),
    (3, 8, 'Tôi thấy phần tối ưu hóa hiệu suất rất hữu ích.', NOW(), NOW()),
    (3, 9, 'Bài viết rất đầy đủ, cảm ơn!', NOW(), NOW()),
    (4, 5, 'Điện toán đám mây ngày càng trở nên quan trọng, cảm ơn bài viết.', NOW(), NOW()), -- Giới thiệu về điện toán đám mây
    (4, 6, 'Có thông tin về các nhà cung cấp dịch vụ không?', NOW(), NOW()),
    (4, 7, 'Tôi rất thích phần phân tích các dịch vụ.', NOW(), NOW()),
    (4, 8, 'Bài viết nên có thêm các ví dụ thực tế.', NOW(), NOW()),
    (4, 9, 'Rất thú vị, tôi đã học được nhiều điều.', NOW(), NOW()),
    (5, 5, 'Bài viết về an ninh mạng rất cần thiết trong thời đại ngày nay.', NOW(), NOW()), -- Khám phá an ninh mạng
    (5, 6, 'Có thể giải thích thêm về các loại mối đe dọa không?', NOW(), NOW()),
    (5, 7, 'Tôi đánh giá cao việc cung cấp các giải pháp phòng ngừa.', NOW(), NOW()),
    (5, 8, 'Bài viết cần thêm thông tin về công cụ an ninh mạng.', NOW(), NOW()),
    (5, 9, 'Rất chi tiết và dễ hiểu.', NOW(), NOW()),
    (6, 5, 'Hệ quản trị cơ sở dữ liệu là một chủ đề quan trọng, cảm ơn bài viết.', NOW(), NOW()), -- Hệ quản trị cơ sở dữ liệu
    (6, 6, 'Có thông tin về các DBMS phổ biến không?', NOW(), NOW()),
    (6, 7, 'Bài viết rất hữu ích cho những ai làm việc với dữ liệu.', NOW(), NOW()),
    (6, 8, 'Tôi muốn biết thêm về các DBMS mã nguồn mở.', NOW(), NOW()),
    (6, 9, 'Tốt, nhưng có thể thêm phần so sánh giữa các DBMS.', NOW(), NOW()),
    (7, 5, 'Blockchain là một công nghệ thú vị, cảm ơn!', NOW(), NOW()), -- Những điều cơ bản về Blockchain
    (7, 6, 'Bài viết có thể giải thích thêm về smart contracts không?', NOW(), NOW()),
    (7, 7, 'Tôi muốn tìm hiểu thêm về ứng dụng thực tế của Blockchain.', NOW(), NOW()),
    (7, 8, 'Rất chi tiết, cảm ơn!', NOW(), NOW()),
    (7, 9, 'Bài viết rất hữu ích cho những ai mới bắt đầu với Blockchain.', NOW(), NOW()),
    (8, 5, 'DevOps là một chủ đề quan trọng trong phát triển phần mềm.', NOW(), NOW()), -- Giới thiệu về DevOps
    (8, 6, 'Có thể giải thích thêm về các công cụ DevOps không?', NOW(), NOW()),
    (8, 7, 'Bài viết rất chi tiết, cảm ơn!', NOW(), NOW()),
    (8, 8, 'Tôi muốn tìm hiểu thêm về lợi ích của DevOps trong phát triển phần mềm.', NOW(), NOW()),
    (8, 9, 'Tốt, nhưng cần thêm ví dụ về ứng dụng DevOps.', NOW(), NOW()),
    (9, 5, 'Docker là công cụ tuyệt vời cho phát triển ứng dụng.', NOW(), NOW()), -- Bắt đầu với Docker
    (9, 6, 'Bài viết giúp tôi rất nhiều trong việc bắt đầu với Docker.', NOW(), NOW()),
    (9, 7, 'Có thông tin về cách triển khai Docker không?', NOW(), NOW()),
    (9, 8, 'Bài viết rất chi tiết và dễ hiểu.', NOW(), NOW()),
    (9, 9, 'Tôi muốn biết thêm về việc quản lý container.', NOW(), NOW()),
    (10, 5, 'Kubernetes là một công nghệ mạnh mẽ, cảm ơn bài viết!', NOW(), NOW()), -- Hiểu về Kubernetes
    (10, 6, 'Có thể giải thích thêm về các thành phần chính của Kubernetes không?', NOW(), NOW()),
    (10, 7, 'Bài viết rất hữu ích cho việc triển khai Kubernetes.', NOW(), NOW()),
    (10, 8, 'Tôi muốn biết thêm về các trường hợp sử dụng thực tế của Kubernetes.', NOW(), NOW()),
    (10, 9, 'Rất tốt, cảm ơn về thông tin!', NOW(), NOW());
#------------------------------------------------------------------------------------------------------------------------------------------------------------------
-- Likes for post 1
INSERT INTO `post_likes` (`user_id`, `post_id`, `liked_at`)
VALUES
    (5, 1, NOW()), -- Reader 1
    (6, 1, NOW()), -- Reader 2
    (7, 1, NOW()), -- Reader 3
    (8, 1, NOW()), -- Reader 4
    (9, 1, NOW()); -- Reader 5

-- Likes for post 2
INSERT INTO `post_likes` (`user_id`, `post_id`, `liked_at`)
VALUES
    (5, 2, NOW()), -- Reader 1
    (6, 2, NOW()), -- Reader 2
    (7, 2, NOW()), -- Reader 3
    (8, 2, NOW()), -- Reader 4
    (9, 2, NOW()); -- Reader 5

-- Likes for post 3
INSERT INTO `post_likes` (`user_id`, `post_id`, `liked_at`)
VALUES
    (5, 3, NOW()), -- Reader 1
    (6, 3, NOW()), -- Reader 2
    (7, 3, NOW()), -- Reader 3
    (8, 3, NOW()), -- Reader 4
    (9, 3, NOW()); -- Reader 5

-- Likes for post 4
INSERT INTO `post_likes` (`user_id`, `post_id`, `liked_at`)
VALUES
    (5, 4, NOW()), -- Reader 1
    (6, 4, NOW()), -- Reader 2
    (7, 4, NOW()), -- Reader 3
    (8, 4, NOW()), -- Reader 4
    (9, 4, NOW()); -- Reader 5

-- Likes for post 5
INSERT INTO `post_likes` (`user_id`, `post_id`, `liked_at`)
VALUES
    (5, 5, NOW()), -- Reader 1
    (6, 5, NOW()), -- Reader 2
    (7, 5, NOW()), -- Reader 3
    (8, 5, NOW()), -- Reader 4
    (9, 5, NOW()); -- Reader 5

-- Likes for post 6
INSERT INTO `post_likes` (`user_id`, `post_id`, `liked_at`)
VALUES
    (5, 6, NOW()), -- Reader 1
    (6, 6, NOW()), -- Reader 2
    (7, 6, NOW()), -- Reader 3
    (8, 6, NOW()), -- Reader 4
    (9, 6, NOW()); -- Reader 5

-- Likes for post 7
INSERT INTO `post_likes` (`user_id`, `post_id`, `liked_at`)
VALUES
    (5, 7, NOW()), -- Reader 1
    (6, 7, NOW()), -- Reader 2
    (7, 7, NOW()), -- Reader 3
    (8, 7, NOW()), -- Reader 4
    (9, 7, NOW()); -- Reader 5

-- Likes for post 8
INSERT INTO `post_likes` (`user_id`, `post_id`, `liked_at`)
VALUES
    (5, 8, NOW()), -- Reader 1
    (6, 8, NOW()), -- Reader 2
    (7, 8, NOW()), -- Reader 3
    (8, 8, NOW()), -- Reader 4
    (9, 8, NOW()); -- Reader 5

-- Likes for post 9
INSERT INTO `post_likes` (`user_id`, `post_id`, `liked_at`)
VALUES
    (5, 9, NOW()), -- Reader 1
    (6, 9, NOW()), -- Reader 2
    (7, 9, NOW()), -- Reader 3
    (8, 9, NOW()), -- Reader 4
    (9, 9, NOW()); -- Reader 5

-- Likes for post 10
INSERT INTO `post_likes` (`user_id`, `post_id`, `liked_at`)
VALUES
    (5, 10, NOW()), -- Reader 1
    (6, 10, NOW()), -- Reader 2
    (7, 10, NOW()), -- Reader 3
    (8, 10, NOW()), -- Reader 4
    (9, 10, NOW()); -- Reader 5
#------------------------------------------------------------------------------------------------------------------------------------------------------------------
-- Banner Types
INSERT INTO `banner_types` (`name`, `description`)
VALUES
    ('Header', 'Header banners displayed at the top of the page'),
    ('Sidebar', 'Sidebar banners displayed on the side of the page'),
    ('Footer', 'Footer banners displayed at the bottom of the page'),
    ('Inline', 'Inline banners displayed within content areas');
#------------------------------------------------------------------------------------------------------------------------------------------------------------------
-- Banners
INSERT INTO `banners` (`title`, `image_path`, `text`, `link`, `start_date`, `end_date`, `is_active`, `type_id`, `position`)
VALUES
    ('Header Banner 1', '/assets/images/line.jpg', 'Discover the newest gadgets and electronics on our blog. Click here to read more!', 'https://techblog.com/gadgets', '2024-08-01 00:00:00', '2024-12-31 23:59:59', 1, 1, 0),
    ('Subscribe to Our Newsletter', '/assets/images/1270x300_placeholder_banner.webp', 'Stay updated with the latest tech news. Subscribe to our newsletter!', 'https://techblog.com/newsletter', '2024-08-01 00:00:00', '2024-12-31 23:59:59', 1, 1, 1),
    ('Top 10 Coding Practices', '/assets/images/300x1270_placeholder_banner.webp', 'Enhance your coding skills with our top 10 coding practices. Click here to learn more!', 'https://techblog.com/coding-practices', '2024-08-01 00:00:00', '2024-12-31 23:59:59', 1, 2, 0),
    ('Cybersecurity Tips', '/assets/images/1270x300_placeholder_banner.webp', 'Protect yourself online with our top cybersecurity tips. Click here to read more!', 'https://techblog.com/cybersecurity-tips', '2024-08-01 00:00:00', '2024-10-31 23:59:59', 1, 2, 1),
    ('Join Our Webinar', '/assets/images/1270x300_placeholder_banner.webp', 'Join our free webinar on the future of AI. Click here to register!', 'https://techblog.com/webinar', '2024-08-01 00:00:00', '2024-08-31 23:59:59', 1, 3, 0),
    ('Latest Tech Reviews', '/assets/images/1270x300_placeholder_banner.webp', 'Read our latest reviews on the newest tech products. Click here to check them out!', 'https://techblog.com/reviews', '2024-08-01 00:00:00', '2024-11-30 23:59:59', 1, 4, 0);












