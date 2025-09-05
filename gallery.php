<?php
$page_title = 'Gallery';
require_once 'includes/db_connect.php';
include 'includes/header.php';

// Get category filter if provided
$category_filter = isset($_GET['category']) ? intval($_GET['category']) : 0;

// Fetch categories
$categories_query = "SELECT * FROM gallery_categories WHERE is_visible = TRUE ORDER BY display_order";
$categories_result = $conn->query($categories_query);

// Fetch images based on filter
if ($category_filter > 0) {
    $images_query = "SELECT gi.*, gc.category_name 
                    FROM gallery_images gi 
                    LEFT JOIN gallery_categories gc ON gi.category_id = gc.category_id 
                    WHERE gi.category_id = ? 
                    ORDER BY gi.upload_date DESC";
    $stmt = $conn->prepare($images_query);
    $stmt->bind_param("i", $category_filter);
    $stmt->execute();
    $images_result = $stmt->get_result();
} else {
    $images_query = "SELECT gi.*, gc.category_name 
                    FROM gallery_images gi 
                    LEFT JOIN gallery_categories gc ON gi.category_id = gc.category_id 
                    ORDER BY gi.upload_date DESC";
    $images_result = $conn->query($images_query);
}
?>

<!-- portfolio section -->
<section class="portfolio_section layout_padding-top">
    <div class="container">
        <div class="heading_container">
            <h2>
                Photo Gallery
            </h2>
            <p>
                Browse our collection by category
            </p>
        </div>
        
        <!-- Category Filter -->
        <div class="text-center mb-4">
            <a href="gallery.php" style="display: inline-block; padding: 8px 20px; margin: 5px; background-color: #f0b016; border: 1px solid #f0b016; color: white; border-radius: 5px; text-decoration: none;">All</a>
            <a href="gallery.php" style="display: inline-block; padding: 8px 20px; margin: 5px; background-color: transparent; border: 1px solid #f0b016; color: #f0b016; border-radius: 5px; text-decoration: none;">Wedding</a>
            <a href="gallery.php" style="display: inline-block; padding: 8px 20px; margin: 5px; background-color: transparent; border: 1px solid #f0b016; color: #f0b016; border-radius: 5px; text-decoration: none;">Portrait</a>
            <a href="gallery.php" style="display: inline-block; padding: 8px 20px; margin: 5px; background-color: transparent; border: 1px solid #f0b016; color: #f0b016; border-radius: 5px; text-decoration: none;">Corporate</a>
            <a href="gallery.php" style="display: inline-block; padding: 8px 20px; margin: 5px; background-color: transparent; border: 1px solid #f0b016; color: #f0b016; border-radius: 5px; text-decoration: none;">Landscape</a>
        </div>
    </div>
    
    <!-- Gallery Portfolio Container -->
    <div class="portfolio_container slick-carousel">
        <?php
        // Use portfolio images as gallery items
        $gallery_items = [
            ['image' => 'Beautiful wedding photography capturing your special day.jpg', 'title' => 'Beautiful Wedding Photography', 'category' => 'Wedding'],
            ['image' => 'ocean-sunset-wedding.webp', 'title' => 'Sunset Wedding Ceremony', 'category' => 'Wedding'],
            ['image' => 'wedding reception dance.jpg', 'title' => 'Wedding Reception Dance', 'category' => 'Wedding'],
            ['image' => 'Professional portrait photography for individuals and families  .jpeg', 'title' => 'Professional Portrait Photography', 'category' => 'Portrait'],
            ['image' => 'family portrait session.jpg', 'title' => 'Family Portrait Session', 'category' => 'Portrait'],
            ['image' => 'Corporate and special event photography  .jpeg', 'title' => 'Corporate & Event Photography', 'category' => 'Corporate'],
            ['image' => 'Stunning landscape and nature photography.jpeg', 'title' => 'Stunning Landscape Photography', 'category' => 'Landscape']
        ];
        
        foreach($gallery_items as $item) {
            ?>
            <div class="box">
                <img src="uploads/portfolio/<?php echo htmlspecialchars($item['image']); ?>" 
                     alt="<?php echo htmlspecialchars($item['title']); ?>">
                <div class="link-box">
                    <a href="#">
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

<?php include 'includes/footer.php'; ?>