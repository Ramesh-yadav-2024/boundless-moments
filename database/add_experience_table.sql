-- Add Experience and Skills table to existing database
-- Run this file if you already have the database set up

USE boundless_moments_db;

-- Create the experience_skills table
CREATE TABLE IF NOT EXISTS experience_skills (
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