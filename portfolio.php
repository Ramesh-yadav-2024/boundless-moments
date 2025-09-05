<?php
$page_title = 'Portfolio';
require_once 'includes/db_connect.php';
include 'includes/header.php';

// Fetch categories with image counts
$categories_query = "SELECT gc.*, COUNT(gi.image_id) as image_count 
                    FROM gallery_categories gc 
                    LEFT JOIN gallery_images gi ON gc.category_id = gi.category_id 
                    WHERE gc.is_visible = TRUE 
                    GROUP BY gc.category_id 
                    ORDER BY gc.display_order";
$categories_result = $conn->query($categories_query);
?>

<!-- portfolio section -->
<section class="portfolio_section layout_padding-top">
    <div class="container">
        <div class="heading_container">
            <h2>
                Our Portfolio
            </h2>
            <p>
                Discover our stunning collection of captured moments
            </p>
        </div>
    </div>
    <div class="portfolio_container slick-carousel">
        <?php
        // Use portfolio images from uploads/portfolio folder
        $portfolio_items = [
            ['image' => 'Beautiful wedding photography capturing your special day.jpg', 'title' => 'Beautiful Wedding Photography'],
            ['image' => 'ocean-sunset-wedding.webp', 'title' => 'Sunset Wedding Ceremony'],
            ['image' => 'Professional portrait photography for individuals and families  .jpeg', 'title' => 'Professional Portrait Photography'],
            ['image' => 'family portrait session.jpg', 'title' => 'Family Portrait Session'],
            ['image' => 'Corporate and special event photography  .jpeg', 'title' => 'Corporate & Event Photography'],
            ['image' => 'wedding reception dance.jpg', 'title' => 'Wedding Reception Dance'],
            ['image' => 'Stunning landscape and nature photography.jpeg', 'title' => 'Stunning Landscape Photography']
        ];
        
        foreach($portfolio_items as $item) {
            ?>
            <div class="box">
                <img src="uploads/portfolio/<?php echo htmlspecialchars($item['image']); ?>" alt="<?php echo htmlspecialchars($item['title']); ?>">
                <div class="link-box">
                    <a href="gallery.php">
                        <img src="assets/images/link.png" alt="">
                    </a>
                    <h6>
                        <?php echo htmlspecialchars($item['title']); ?>
                    </h6>
                </div>
            </div>
            <?php
        }
        ?>
    </div>
</section>
<!-- end portfolio section -->

<!-- about section -->
<section class="about_section layout_padding">
    <div class="container-fluid">
        <div class="box">
            <div class="img_container">
                <div class="img-box b1">
                    <img src="assets/images/p-1.jpg" alt="Wedding Photography Category">
                </div>
                <div class="img-box b2">
                    <img src="assets/images/p-2.jpg" alt="Portrait Photography Category">
                </div>
            </div>
            <div class="detail-box">
                <h2>
                    Portfolio Categories
                </h2>
                <div class="category-list">
                    <div class="category-item mb-3">
                        <h5>Wedding Photography</h5>
                        <p>Capturing beautiful moments from your special day with artistic vision and professional excellence</p>
                        <p class="text-muted small">3 images</p>
                        <a href="gallery.php" class="btn btn-sm" style="background-color: #f0b016; color: white; border: 1px solid #f0b016; padding: 5px 15px; border-radius: 5px; text-decoration: none;">
                            View Gallery
                        </a>
                    </div>
                    
                    <div class="category-item mb-3">
                        <h5>Portrait Photography</h5>
                        <p>Professional portrait sessions for individuals and families in studio or outdoor settings</p>
                        <p class="text-muted small">2 images</p>
                        <a href="gallery.php" class="btn btn-sm" style="background-color: #f0b016; color: white; border: 1px solid #f0b016; padding: 5px 15px; border-radius: 5px; text-decoration: none;">
                            View Gallery
                        </a>
                    </div>
                    
                    <div class="category-item mb-3">
                        <h5>Corporate & Events</h5>
                        <p>Professional coverage for corporate events and special occasions</p>
                        <p class="text-muted small">1 images</p>
                        <a href="gallery.php" class="btn btn-sm" style="background-color: #f0b016; color: white; border: 1px solid #f0b016; padding: 5px 15px; border-radius: 5px; text-decoration: none;">
                            View Gallery
                        </a>
                    </div>
                    
                    <div class="category-item mb-3">
                        <h5>Landscape Photography</h5>
                        <p>Stunning landscape and nature photography capturing the beauty of the natural world</p>
                        <p class="text-muted small">1 images</p>
                        <a href="gallery.php" class="btn btn-sm" style="background-color: #f0b016; color: white; border: 1px solid #f0b016; padding: 5px 15px; border-radius: 5px; text-decoration: none;">
                            View Gallery
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- end about section -->

<!-- quality section -->
<section class="quality_section layout_padding">
    <div class="container">
        <div class="quality_container">
            <h2>
                Why Choose <br>
                Our Portfolio
            </h2>
            <div class="box">
                <div class="detail-box">
                    <div class="img-box">
                        <img src="assets/images/q-1.png" alt="">
                    </div>
                    <div class="text-box">
                        <h5>
                            Diverse Styles
                        </h5>
                        <p>
                            From weddings to portraits, our portfolio showcases versatility
                        </p>
                    </div>
                </div>
                <div class="detail-box">
                    <div class="img-box">
                        <img src="assets/images/q-2.png" alt="">
                    </div>
                    <div class="text-box">
                        <h5>
                            High Quality
                        </h5>
                        <p>
                            Every image represents our commitment to excellence
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- end quality section -->

<?php include 'includes/footer.php'; ?>