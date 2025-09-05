<?php
session_start();
$page_title = 'Experience & Skills';
include 'includes/db_connect.php';
include 'includes/header.php';

// Fetch experience data
$experience_query = "SELECT * FROM experience_skills WHERE section_type = 'experience' AND is_visible = TRUE ORDER BY display_order ASC";
$experience_result = $conn->query($experience_query);

// Fetch skills data
$skills_query = "SELECT * FROM experience_skills WHERE section_type = 'skill' AND is_visible = TRUE ORDER BY display_order ASC";
$skills_result = $conn->query($skills_query);

// Fetch awards data
$awards_query = "SELECT * FROM experience_skills WHERE section_type = 'award' AND is_visible = TRUE ORDER BY display_order ASC";
$awards_result = $conn->query($awards_query);

// Fetch education data
$education_query = "SELECT * FROM experience_skills WHERE section_type = 'education' AND is_visible = TRUE ORDER BY display_order ASC";
$education_result = $conn->query($education_query);
?>

<style>
.experience-section {
    padding: 60px 0;
    background: #f8f9fa;
}

.skills-section {
    padding: 60px 0;
    background: white;
}

.awards-section {
    padding: 60px 0;
    background: #f8f9fa;
}

.education-section {
    padding: 60px 0;
    background: white;
}

.timeline-item {
    border-left: 3px solid #007bff;
    padding-left: 20px;
    margin-bottom: 30px;
    position: relative;
}

.timeline-item::before {
    content: '';
    width: 12px;
    height: 12px;
    background: #007bff;
    border-radius: 50%;
    position: absolute;
    left: -7px;
    top: 0;
}

.skill-item {
    background: white;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    margin-bottom: 20px;
    transition: transform 0.3s ease;
}

.skill-item:hover {
    transform: translateY(-5px);
}

.skill-level {
    padding: 5px 15px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: bold;
    text-transform: uppercase;
}

.skill-level.expert {
    background: #28a745;
    color: white;
}

.skill-level.advanced {
    background: #17a2b8;
    color: white;
}

.skill-level.intermediate {
    background: #ffc107;
    color: #333;
}

.skill-level.beginner {
    background: #6c757d;
    color: white;
}

.award-item {
    background: white;
    padding: 25px;
    border-radius: 8px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    margin-bottom: 20px;
    border-left: 4px solid #ffc107;
}

.education-item {
    background: #f8f9fa;
    padding: 25px;
    border-radius: 8px;
    margin-bottom: 20px;
    border: 1px solid #dee2e6;
}

.date-range {
    color: #666;
    font-size: 14px;
    font-style: italic;
}
</style>

<!-- Hero Section -->
<section class="hero_area">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-12 text-center">
                <h1 class="display-4 mb-4">Experience & Skills</h1>
                <p class="lead">Professional background, expertise, and achievements in photography</p>
            </div>
        </div>
    </div>
</section>

<!-- Professional Experience Section -->
<section class="experience-section">
    <div class="container">
        <div class="heading_container text-center mb-5">
            <h2>Professional Experience</h2>
            <p>Our journey in professional photography</p>
        </div>
        
        <div class="row">
            <div class="col-lg-8 offset-lg-2">
                <?php if ($experience_result->num_rows > 0): ?>
                    <?php while ($experience = $experience_result->fetch_assoc()): ?>
                        <div class="timeline-item">
                            <h4><?php echo htmlspecialchars($experience['title']); ?></h4>
                            <?php if ($experience['subtitle']): ?>
                                <h6 class="text-primary"><?php echo htmlspecialchars($experience['subtitle']); ?></h6>
                            <?php endif; ?>
                            <?php if ($experience['organization']): ?>
                                <p class="mb-1"><strong><?php echo htmlspecialchars($experience['organization']); ?></strong>
                                <?php if ($experience['location']): ?>
                                    - <?php echo htmlspecialchars($experience['location']); ?>
                                <?php endif; ?>
                                </p>
                            <?php endif; ?>
                            <?php if ($experience['start_date']): ?>
                                <p class="date-range">
                                    <?php echo date('M Y', strtotime($experience['start_date'])); ?> - 
                                    <?php echo $experience['end_date'] ? date('M Y', strtotime($experience['end_date'])) : 'Present'; ?>
                                </p>
                            <?php endif; ?>
                            <?php if ($experience['description']): ?>
                                <p><?php echo nl2br(htmlspecialchars($experience['description'])); ?></p>
                            <?php endif; ?>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <p class="text-center">No experience data available.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<!-- Skills Section -->
<section class="skills-section">
    <div class="container">
        <div class="heading_container text-center mb-5">
            <h2>Skills & Expertise</h2>
            <p>Technical skills and creative capabilities</p>
        </div>
        
        <div class="row">
            <?php if ($skills_result->num_rows > 0): ?>
                <?php while ($skill = $skills_result->fetch_assoc()): ?>
                    <div class="col-lg-6 col-md-6 mb-4">
                        <div class="skill-item">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <h5 class="mb-0"><?php echo htmlspecialchars($skill['title']); ?></h5>
                                <?php if ($skill['skill_level']): ?>
                                    <span class="skill-level <?php echo htmlspecialchars($skill['skill_level']); ?>">
                                        <?php echo htmlspecialchars($skill['skill_level']); ?>
                                    </span>
                                <?php endif; ?>
                            </div>
                            <?php if ($skill['description']): ?>
                                <p class="mb-0"><?php echo nl2br(htmlspecialchars($skill['description'])); ?></p>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <div class="col-12">
                    <p class="text-center">No skills data available.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- Awards Section -->
<section class="awards-section">
    <div class="container">
        <div class="heading_container text-center mb-5">
            <h2>Awards & Recognition</h2>
            <p>Achievements and accolades in photography</p>
        </div>
        
        <div class="row">
            <?php if ($awards_result->num_rows > 0): ?>
                <?php while ($award = $awards_result->fetch_assoc()): ?>
                    <div class="col-lg-6 mb-4">
                        <div class="award-item">
                            <h4><?php echo htmlspecialchars($award['title']); ?></h4>
                            <?php if ($award['subtitle']): ?>
                                <h6 class="text-warning"><?php echo htmlspecialchars($award['subtitle']); ?></h6>
                            <?php endif; ?>
                            <?php if ($award['organization']): ?>
                                <p class="mb-1"><strong><?php echo htmlspecialchars($award['organization']); ?></strong>
                                <?php if ($award['location']): ?>
                                    - <?php echo htmlspecialchars($award['location']); ?>
                                <?php endif; ?>
                                </p>
                            <?php endif; ?>
                            <?php if ($award['start_date']): ?>
                                <p class="date-range"><?php echo date('M d, Y', strtotime($award['start_date'])); ?></p>
                            <?php endif; ?>
                            <?php if ($award['description']): ?>
                                <p><?php echo nl2br(htmlspecialchars($award['description'])); ?></p>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <div class="col-12">
                    <p class="text-center">No awards data available.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- Education Section -->
<section class="education-section">
    <div class="container">
        <div class="heading_container text-center mb-5">
            <h2>Education & Certifications</h2>
            <p>Formal education and professional development</p>
        </div>
        
        <div class="row">
            <div class="col-lg-8 offset-lg-2">
                <?php if ($education_result->num_rows > 0): ?>
                    <?php while ($education = $education_result->fetch_assoc()): ?>
                        <div class="education-item">
                            <h4><?php echo htmlspecialchars($education['title']); ?></h4>
                            <?php if ($education['organization']): ?>
                                <h6 class="text-info"><?php echo htmlspecialchars($education['organization']); ?></h6>
                            <?php endif; ?>
                            <?php if ($education['location']): ?>
                                <p class="mb-1"><?php echo htmlspecialchars($education['location']); ?></p>
                            <?php endif; ?>
                            <?php if ($education['start_date']): ?>
                                <p class="date-range">
                                    <?php echo date('M Y', strtotime($education['start_date'])); ?> - 
                                    <?php echo $education['end_date'] ? date('M Y', strtotime($education['end_date'])) : 'Present'; ?>
                                </p>
                            <?php endif; ?>
                            <?php if ($education['description']): ?>
                                <p><?php echo nl2br(htmlspecialchars($education['description'])); ?></p>
                            <?php endif; ?>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <p class="text-center">No education data available.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<!-- Call to Action -->
<section class="client_section layout_padding">
    <div class="container">
        <div class="text-center">
            <h2>Ready to Work Together?</h2>
            <p class="lead mb-4">Let our experience and skills help capture your special moments</p>
            <a href="contact.php" class="btn btn-primary btn-lg">Get In Touch</a>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>