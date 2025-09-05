-- Sample data for Boundless Moments database

-- Insert sample gallery categories
INSERT IGNORE INTO gallery_categories (category_id, category_name, category_description) VALUES
(1, 'Weddings', 'Wedding photography'),
(2, 'Portraits', 'Portrait photography'),
(3, 'Events', 'Event photography'),
(4, 'Nature', 'Nature and landscape photography');

-- Insert sample gallery images
INSERT IGNORE INTO gallery_images (image_id, category_id, image_title, image_description, file_path, is_featured, upload_date) VALUES
(1, 1, 'Beautiful Wedding Day', 'Capture of a perfect wedding moment', 'uploads/portfolio/wedding-1.jpg', 1, NOW()),
(2, 2, 'Professional Portrait', 'Corporate headshot session', 'uploads/portfolio/portrait-1.jpg', 0, NOW()),
(3, 3, 'Corporate Event', 'Annual company gathering', 'uploads/portfolio/event-1.jpg', 0, NOW()),
(4, 4, 'Mountain Landscape', 'Breathtaking mountain view', 'uploads/portfolio/nature-1.jpg', 1, NOW()),
(5, 1, 'Wedding Reception', 'Dance floor moments', 'uploads/portfolio/wedding-2.jpg', 0, NOW()),
(6, 2, 'Family Portrait', 'Happy family session', 'uploads/portfolio/family-1.jpg', 0, NOW());

-- Insert sample contact messages
INSERT IGNORE INTO contact_messages (message_id, sender_name, sender_email, sender_phone, subject, message_content, submission_date, is_read) VALUES
(1, 'John Smith', 'john@example.com', '+1-555-0123', 'Wedding Photography Inquiry', 'Hi, I am interested in booking a wedding photographer for June 2024. Could you please send me your packages?', NOW(), FALSE),
(2, 'Sarah Johnson', 'sarah@example.com', '+1-555-0456', 'Portrait Session Request', 'I would like to schedule a family portrait session. What are your available dates?', DATE_SUB(NOW(), INTERVAL 1 DAY), TRUE),
(3, 'Mike Wilson', 'mike@example.com', '+1-555-0789', 'Event Photography Quote', 'We need a photographer for our corporate event next month. Please provide a quote.', DATE_SUB(NOW(), INTERVAL 2 DAY), FALSE),
(4, 'Emily Davis', 'emily@example.com', '+1-555-0321', 'Photography Services Info', 'What types of photography services do you offer? I am planning a graduation party.', DATE_SUB(NOW(), INTERVAL 3 DAY), TRUE),
(5, 'Robert Brown', 'robert@example.com', '+1-555-0654', 'Wedding Package Details', 'Can you provide more details about your wedding photography packages and pricing?', DATE_SUB(NOW(), INTERVAL 4 DAY), FALSE);

-- Insert sample bookings
INSERT IGNORE INTO bookings (booking_id, client_name, client_email, client_phone, event_type, event_date, event_time, location, special_requirements, booking_date, status) VALUES
(1, 'Alice Cooper', 'alice@example.com', '+1-555-1111', 'Wedding', '2024-06-15', '14:00:00', 'Grand Hotel Ballroom', 'Need drone shots for outdoor ceremony', DATE_SUB(NOW(), INTERVAL 5 DAY), 'confirmed'),
(2, 'David Miller', 'david@example.com', '+1-555-2222', 'Corporate Event', '2024-05-20', '18:00:00', 'Convention Center Hall A', 'Professional headshots of executives during event', DATE_SUB(NOW(), INTERVAL 3 DAY), 'pending'),
(3, 'Jennifer Taylor', 'jennifer@example.com', '+1-555-3333', 'Birthday Party', '2024-04-30', '16:00:00', 'Private Residence', 'Child-friendly photographer needed, outdoor garden setting', DATE_SUB(NOW(), INTERVAL 2 DAY), 'pending'),
(4, 'Michael Johnson', 'michael@example.com', '+1-555-4444', 'Engagement Session', '2024-05-10', '10:00:00', 'City Park', 'Golden hour shooting, romantic theme', DATE_SUB(NOW(), INTERVAL 1 DAY), 'confirmed'),
(5, 'Lisa Anderson', 'lisa@example.com', '+1-555-5555', 'Graduation Party', '2024-06-01', '15:00:00', 'University Campus', 'Group photos with family and friends', NOW(), 'pending');