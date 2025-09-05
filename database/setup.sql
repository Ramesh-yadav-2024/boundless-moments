-- Create Boundless Moments Database
CREATE DATABASE IF NOT EXISTS boundless_moments_db 
CHARACTER SET utf8mb4 
COLLATE utf8mb4_unicode_ci;

USE boundless_moments_db;

-- Users table: Stores authentication and profile information
CREATE TABLE users (
    user_id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) UNIQUE NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    full_name VARCHAR(100),
    user_role ENUM('admin', 'client') DEFAULT 'client',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    last_login DATETIME,
    is_active BOOLEAN DEFAULT TRUE
);

-- Gallery Categories table: Organizes photos into categories
CREATE TABLE gallery_categories (
    category_id INT PRIMARY KEY AUTO_INCREMENT,
    category_name VARCHAR(100) NOT NULL,
    category_description TEXT,
    display_order INT DEFAULT 0,
    is_visible BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_category_name (category_name)
);

-- Gallery Images table: Individual images within portfolios
CREATE TABLE gallery_images (
    image_id INT PRIMARY KEY AUTO_INCREMENT,
    category_id INT,
    image_title VARCHAR(200) NOT NULL,
    image_description TEXT,
    file_path VARCHAR(500) NOT NULL,
    thumbnail_path VARCHAR(500),
    upload_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    is_featured BOOLEAN DEFAULT FALSE,
    view_count INT DEFAULT 0,
    FOREIGN KEY (category_id) REFERENCES gallery_categories(category_id) 
        ON DELETE SET NULL
);

-- Contact Messages table: Contact form submissions
CREATE TABLE contact_messages (
    message_id INT PRIMARY KEY AUTO_INCREMENT,
    sender_name VARCHAR(100) NOT NULL,
    sender_email VARCHAR(100) NOT NULL,
    sender_phone VARCHAR(20),
    subject VARCHAR(200),
    message_content TEXT NOT NULL,
    submission_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    is_read BOOLEAN DEFAULT FALSE,
    replied_date DATETIME
);

-- Bookings table: Managing photography bookings
CREATE TABLE bookings (
    booking_id INT PRIMARY KEY AUTO_INCREMENT,
    client_name VARCHAR(100) NOT NULL,
    client_email VARCHAR(100) NOT NULL,
    client_phone VARCHAR(20) NOT NULL,
    event_type VARCHAR(50),
    event_date DATE NOT NULL,
    event_time TIME,
    location VARCHAR(200),
    special_requirements TEXT,
    status ENUM('pending', 'confirmed', 'cancelled') DEFAULT 'pending',
    booking_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_event_date (event_date)
);

-- Experience and Skills table: Professional background and achievements
CREATE TABLE experience_skills (
    id INT PRIMARY KEY AUTO_INCREMENT,
    section_type ENUM('experience', 'skill', 'award', 'education') NOT NULL,
    title VARCHAR(200) NOT NULL,
    subtitle VARCHAR(200),
    description TEXT,
    start_date DATE,
    end_date DATE,
    organization VARCHAR(200),
    location VARCHAR(200),
    skill_level ENUM('beginner', 'intermediate', 'advanced', 'expert'),
    display_order INT DEFAULT 0,
    is_visible BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_section_type (section_type),
    INDEX idx_display_order (display_order)
);

-- Insert default admin user (password: admin123)
INSERT INTO users (username, email, password_hash, full_name, user_role) 
VALUES ('admin', 'admin@boundlessmoments.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Administrator', 'admin');

-- Insert sample categories
INSERT INTO gallery_categories (category_name, category_description, display_order) VALUES
('Weddings', 'Beautiful wedding photography capturing your special day', 1),
('Portraits', 'Professional portrait photography for individuals and families', 2),
('Landscapes', 'Stunning landscape and nature photography', 3),
('Events', 'Corporate and special event photography', 4);

-- Insert sample gallery images (placeholder entries)
INSERT INTO gallery_images (category_id, image_title, image_description, file_path, is_featured) VALUES
(1, 'Sunset Wedding Ceremony', 'A beautiful outdoor wedding ceremony at golden hour', 'uploads/gallery/wedding1.jpg', TRUE),
(1, 'Wedding Reception Dance', 'Candid moments from the wedding reception', 'uploads/gallery/wedding2.jpg', FALSE),
(2, 'Family Portrait Session', 'Professional family portrait in natural lighting', 'uploads/gallery/portrait1.jpg', TRUE),
(3, 'Mountain Landscape', 'Breathtaking mountain vista at sunrise', 'uploads/gallery/landscape1.jpg', TRUE),
(4, 'Corporate Event', 'Professional corporate event photography', 'uploads/gallery/event1.jpg', FALSE);

-- Insert sample contact messages
INSERT INTO contact_messages (sender_name, sender_email, sender_phone, subject, message_content, is_read) VALUES
('John Smith', 'john@example.com', '+1-555-0123', 'Wedding Photography Inquiry', 'Hi, I am interested in booking a wedding photographer for June 2024. Could you please send me your packages and pricing information? We are planning a beachside wedding with approximately 150 guests.', FALSE),
('Sarah Johnson', 'sarah@example.com', '+1-555-0456', 'Portrait Session Request', 'I would like to schedule a family portrait session for our family of five. What are your available dates for next month? We prefer outdoor settings if possible.', TRUE),
('Mike Wilson', 'mike@example.com', '+1-555-0789', 'Event Photography Quote', 'We need a photographer for our annual corporate event next month. The event will be held at the Convention Center from 6 PM to 10 PM. Please provide a detailed quote including all services.', FALSE),
('Emily Davis', 'emily@example.com', '+1-555-0321', 'Photography Services Info', 'What types of photography services do you offer? I am planning a graduation party and would like to know your packages and availability for May.', TRUE),
('Robert Brown', 'robert@example.com', '+1-555-0654', 'Wedding Package Details', 'Can you provide more details about your wedding photography packages? We are looking for full-day coverage including engagement photos.', FALSE);

-- Insert sample bookings
INSERT INTO bookings (client_name, client_email, client_phone, event_type, event_date, event_time, location, special_requirements, status) VALUES
('Alice Cooper', 'alice@example.com', '+1-555-1111', 'Wedding', '2024-06-15', '14:00:00', 'Grand Hotel Ballroom, 123 Main St', 'Need drone shots for outdoor ceremony. Prefer candid style photography. Album with 100 photos required.', 'confirmed'),
('David Miller', 'david@example.com', '+1-555-2222', 'Corporate Event', '2024-05-20', '18:00:00', 'Convention Center Hall A', 'Professional headshots of all executives during the event. Need photos for company website and annual report.', 'pending'),
('Jennifer Taylor', 'jennifer@example.com', '+1-555-3333', 'Birthday Party', '2024-04-30', '16:00:00', 'Private Residence - 456 Oak Avenue', 'Child-friendly photographer needed. Outdoor garden setting. Focus on candid moments with children.', 'pending'),
('Michael Johnson', 'michael@example.com', '+1-555-4444', 'Engagement Session', '2024-05-10', '10:00:00', 'City Park Rose Garden', 'Golden hour shooting preferred. Romantic theme with natural lighting. Need 50 edited photos.', 'confirmed'),
('Lisa Anderson', 'lisa@example.com', '+1-555-5555', 'Graduation Party', '2024-06-01', '15:00:00', 'University Campus - Main Quad', 'Group photos with family and friends. Individual portraits with diploma. Need quick turnaround for prints.', 'pending');

-- Insert sample experience and skills data
INSERT INTO experience_skills (section_type, title, subtitle, description, start_date, end_date, organization, location, skill_level, display_order, is_visible) VALUES
-- Experience entries
('experience', 'Senior Wedding Photographer', 'Boundless Moments Photography', 'Lead photographer specializing in wedding ceremonies and receptions. Managed teams of 2-3 assistant photographers for large events. Photographed over 150+ weddings with 98% client satisfaction rate.', '2022-01-01', NULL, 'Boundless Moments Photography', 'New York, NY', NULL, 1, TRUE),
('experience', 'Portrait Photographer', 'Creative Studios Inc.', 'Specialized in family portraits, corporate headshots, and personal branding photography. Developed expertise in studio lighting and outdoor natural light photography.', '2020-06-01', '2021-12-31', 'Creative Studios Inc.', 'Brooklyn, NY', NULL, 2, TRUE),
('experience', 'Assistant Photographer', 'Metro Photography Services', 'Assisted lead photographers in weddings, events, and commercial shoots. Learned advanced lighting techniques and client relationship management.', '2019-03-01', '2020-05-31', 'Metro Photography Services', 'Manhattan, NY', NULL, 3, TRUE),

-- Skills entries
('skill', 'Wedding Photography', NULL, 'Expert in capturing candid moments, ceremony details, and reception celebrations with artistic flair.', NULL, NULL, NULL, NULL, 'expert', 1, TRUE),
('skill', 'Portrait Photography', NULL, 'Advanced skills in individual and family portraits using both studio and natural lighting.', NULL, NULL, NULL, NULL, 'expert', 2, TRUE),
('skill', 'Adobe Photoshop', NULL, 'Professional photo editing, retouching, and digital enhancement techniques.', NULL, NULL, NULL, NULL, 'advanced', 3, TRUE),
('skill', 'Adobe Lightroom', NULL, 'Color correction, exposure adjustment, and batch processing for efficient workflow.', NULL, NULL, NULL, NULL, 'expert', 4, TRUE),
('skill', 'Event Photography', NULL, 'Corporate events, birthday parties, graduations and special occasions photography.', NULL, NULL, NULL, NULL, 'advanced', 5, TRUE),
('skill', 'Drone Photography', NULL, 'Aerial photography and videography for weddings and real estate projects.', NULL, NULL, NULL, NULL, 'intermediate', 6, TRUE),

-- Awards entries
('award', 'Wedding Photographer of the Year', 'New York Photography Awards', 'Recognized for excellence in wedding photography with outstanding portfolio and client testimonials.', '2023-11-15', NULL, 'New York Photography Association', 'New York, NY', NULL, 1, TRUE),
('award', 'Best Portrait Series', 'Metro Photography Competition', 'First place winner for creative family portrait series showcasing diverse cultural backgrounds.', '2022-09-20', NULL, 'Metro Photography Guild', 'New York, NY', NULL, 2, TRUE),
('award', 'Rising Star Award', 'Professional Photographers Society', 'Awarded to emerging photographers showing exceptional talent and professional growth.', '2021-05-10', NULL, 'Professional Photographers Society', 'New York, NY', NULL, 3, TRUE),

-- Education entries
('education', 'Bachelor of Fine Arts in Photography', 'New York University', 'Comprehensive study in digital and film photography, studio lighting, composition, and visual storytelling. Graduated Magna Cum Laude.', '2016-09-01', '2020-05-15', 'New York University', 'New York, NY', NULL, 1, TRUE),
('education', 'Wedding Photography Certification', 'Professional Wedding Photographers Association', 'Specialized certification covering wedding day timeline management, client relations, and advanced shooting techniques.', '2020-08-01', '2020-10-15', 'PWPA Institute', 'Online', NULL, 2, TRUE),
('education', 'Adobe Certified Expert', 'Adobe Systems', 'Professional certification in Photoshop and Lightroom for advanced photo editing and workflow management.', '2021-03-01', '2021-04-30', 'Adobe Training Center', 'Online', NULL, 3, TRUE);