INSERT INTO users (first_name, last_name, username, password, profile_url)
VALUES
('Ali', 'Daher', 'alidaher',
 '$2y$10$THajlLBaP0HXo7G/aGEyD.jU.yfL8EpBqkXRu4l6wQJ4d1drVphUG',
 'assets/img_698881bd845581.83934648.png'),

('Sara', 'Hassan', 'sarahassan',
 '$2y$10$THajlLBaP0HXo7G/aGEyD.jU.yfL8EpBqkXRu4l6wQJ4d1drVphUG',
 'assets/img_698881bd845581.83934648.png'),

('Omar', 'Khalil', 'omarkhalil',
 '$2y$10$THajlLBaP0HXo7G/aGEyD.jU.yfL8EpBqkXRu4l6wQJ4d1drVphUG',
 'assets/img_698881bd845581.83934648.png');

INSERT INTO todos (title, description, is_done, image_url, user_id)
VALUES
('Buy groceries', 'Buy milk, bread and eggs', FALSE,
 'assets/img_698881bd845581.83934648.png', 1000),

('Finish project', 'Complete backend API for todo app', FALSE,
 NULL, 1000),

('Workout', 'Go to the gym for 1 hour', TRUE,
 'assets/img_698881bd845581.83934648.png', 1001),

('Read book', 'Read 30 pages of a programming book', FALSE,
 NULL, 1002);
