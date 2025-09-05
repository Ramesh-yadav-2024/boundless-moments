<?php
session_start();
require_once '../includes/db_connect.php';

// Check if user is logged in and is admin
if (!isLoggedIn() || !isAdmin()) {
    header('Location: ../login.php');
    exit();
}

$page_title = 'Manage Gallery';
$success_message = '';
$error_message = '';

// Handle DELETE operation
if (isset($_GET['delete']) && isset($_GET['confirm'])) {
    $image_id = intval($_GET['delete']);
    
    // Get file path before deleting
    $stmt = $conn->prepare("SELECT file_path FROM gallery_images WHERE image_id = ?");
    $stmt->bind_param("i", $image_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $image = $result->fetch_assoc();
    
    // Delete from database
    $delete_stmt = $conn->prepare("DELETE FROM gallery_images WHERE image_id = ?");
    $delete_stmt->bind_param("i", $image_id);
    
    if ($delete_stmt->execute()) {
        $success_message = "Image deleted successfully!";
    } else {
        $error_message = "Error deleting image.";
    }
}

// Handle CREATE operation with file upload
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_image'])) {
    $title = sanitize_input($_POST['title']);
    $description = sanitize_input($_POST['description']);
    $category_id = intval($_POST['category_id']);
    $is_featured = isset($_POST['is_featured']) ? 1 : 0;
    
    // Handle file upload
    if (isset($_FILES['image_file']) && $_FILES['image_file']['error'] == 0) {
        $allowed_types = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        $max_size = 5 * 1024 * 1024; // 5MB
        
        $file_type = $_FILES['image_file']['type'];
        $file_size = $_FILES['image_file']['size'];
        $file_tmp = $_FILES['image_file']['tmp_name'];
        $file_name = $_FILES['image_file']['name'];
        
        // Validate file type
        if (!in_array($file_type, $allowed_types)) {
            $error_message = "Invalid file type. Please upload JPG, PNG, GIF, or WebP images only.";
        }
        // Validate file size
        elseif ($file_size > $max_size) {
            $error_message = "File too large. Maximum size is 5MB.";
        }
        else {
            // Create uploads directory if it doesn't exist
            $upload_dir = '../uploads/gallery/';
            if (!file_exists($upload_dir)) {
                mkdir($upload_dir, 0755, true);
            }
            
            // Generate unique filename
            $file_extension = pathinfo($file_name, PATHINFO_EXTENSION);
            $new_filename = 'gallery_' . time() . '_' . uniqid() . '.' . $file_extension;
            $file_path = 'uploads/gallery/' . $new_filename;
            $full_path = $upload_dir . $new_filename;
            
            // Move uploaded file
            if (move_uploaded_file($file_tmp, $full_path)) {
                // Insert into database
                $stmt = $conn->prepare("INSERT INTO gallery_images (category_id, image_title, image_description, file_path, is_featured) VALUES (?, ?, ?, ?, ?)");
                $stmt->bind_param("isssi", $category_id, $title, $description, $file_path, $is_featured);
                
                if ($stmt->execute()) {
                    $success_message = "Image uploaded and added to gallery successfully!";
                } else {
                    $error_message = "Error saving image to database.";
                    // Delete uploaded file if database insert failed
                    unlink($full_path);
                }
            } else {
                $error_message = "Error uploading file. Please try again.";
            }
        }
    } else {
        $error_message = "Please select an image file to upload.";
    }
}

// Initialize variables
$images_result = null;
$categories = null;
$total_images = 0;
$featured_images = 0;
$total_categories = 0;

try {
    // Check if tables exist, create them if they don't
    $gallery_table_check = $conn->query("SHOW TABLES LIKE 'gallery_images'");
    $categories_table_check = $conn->query("SHOW TABLES LIKE 'gallery_categories'");
    
    if ($gallery_table_check->num_rows == 0) {
        // Create gallery_images table
        $create_gallery = "CREATE TABLE gallery_images (
            image_id INT AUTO_INCREMENT PRIMARY KEY,
            category_id INT,
            image_title VARCHAR(255) NOT NULL,
            image_description TEXT,
            file_path VARCHAR(500) NOT NULL,
            is_featured BOOLEAN DEFAULT FALSE,
            upload_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )";
        $conn->query($create_gallery);
    }
    
    if ($categories_table_check->num_rows == 0) {
        // Create gallery_categories table
        $create_categories = "CREATE TABLE gallery_categories (
            category_id INT AUTO_INCREMENT PRIMARY KEY,
            category_name VARCHAR(100) NOT NULL,
            category_description TEXT,
            created_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )";
        $conn->query($create_categories);
        
        // Insert default categories
        $conn->query("INSERT INTO gallery_categories (category_name, category_description) VALUES 
            ('Wedding', 'Wedding photography'),
            ('Portrait', 'Portrait photography'),
            ('Event', 'Event photography'),
            ('Nature', 'Nature photography')");
    }
    
    // Now fetch data safely
    $images_query = "SELECT gi.*, gc.category_name 
                    FROM gallery_images gi 
                    LEFT JOIN gallery_categories gc ON gi.category_id = gc.category_id 
                    ORDER BY gi.upload_date DESC";
    $images_result = $conn->query($images_query);

    // Fetch categories for dropdown
    $categories = $conn->query("SELECT * FROM gallery_categories ORDER BY category_name");

    // Get statistics for gallery
    $total_images = $conn->query("SELECT COUNT(*) as count FROM gallery_images")->fetch_assoc()['count'];
    $featured_images = $conn->query("SELECT COUNT(*) as count FROM gallery_images WHERE is_featured = 1")->fetch_assoc()['count'];
    $total_categories = $conn->query("SELECT COUNT(*) as count FROM gallery_categories")->fetch_assoc()['count'];
    
} catch (Exception $e) {
    $error_message = "Database error: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title; ?> - Boundless Moments Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css?family=Dosis:500|Raleway:400,600,700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Raleway', sans-serif;
            background-color: #f8f9fa;
        }
        
        /* Sidebar Styles */
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            width: 250px;
            background: linear-gradient(180deg, #2c3e50 0%, #34495e 50%, #2c3e50 100%);
            box-shadow: 2px 0 15px rgba(0,0,0,0.2);
            z-index: 1000;
            transition: all 0.3s ease;
        }
        
        .sidebar-header {
            padding: 20px;
            text-align: center;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            background: linear-gradient(135deg, #f0b016 0%, #d49c14 100%);
        }
        
        .sidebar-header h3 {
            color: white;
            font-weight: 600;
            margin: 0;
            font-size: 1.4rem;
        }
        
        .sidebar-header .logo-icon {
            font-size: 2rem;
            color: white;
            margin-bottom: 10px;
        }
        
        .sidebar-menu {
            padding: 20px 0;
        }
        
        .sidebar-menu ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        
        .sidebar-menu li {
            margin: 5px 15px;
        }
        
        .sidebar-menu a {
            display: flex;
            align-items: center;
            padding: 12px 20px;
            color: white;
            text-decoration: none;
            border-radius: 10px;
            transition: all 0.3s ease;
            font-weight: 500;
        }
        
        .sidebar-menu a:hover {
            background-color: rgba(255,255,255,0.1);
            transform: translateX(5px);
        }
        
        .sidebar-menu a.active {
            background: linear-gradient(135deg, #f0b016 0%, #d49c14 100%);
            box-shadow: 0 2px 10px rgba(240, 176, 22, 0.3);
            transform: translateX(5px);
        }
        
        .sidebar-menu a i {
            margin-right: 10px;
            width: 20px;
            text-align: center;
        }
        
        /* Top Header */
        .top-header {
            position: fixed;
            top: 0;
            left: 250px;
            right: 0;
            height: 70px;
            background-color: white;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            z-index: 999;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 30px;
        }
        
        .top-header h1 {
            margin: 0;
            color: #343a40;
            font-weight: 600;
            font-size: 1.5rem;
        }
        
        /* Main Content */
        .main-content {
            margin-left: 250px;
            margin-top: 70px;
            padding: 30px;
            min-height: calc(100vh - 70px);
        }
        
        .card-custom {
            border: none;
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            margin-bottom: 30px;
        }
        
        .card-header-custom {
            background: linear-gradient(135deg, #27ae60 0%, #2ecc71 100%);
            color: white;
            font-weight: 600;
            border-radius: 15px 15px 0 0 !important;
            border: none;
        }
        
        .btn-custom {
            background-color: #f0b016;
            border-color: #f0b016;
            color: white;
            border-radius: 20px;
            padding: 8px 20px;
            font-weight: 500;
        }
        
        .btn-custom:hover {
            background-color: #d49c14;
            border-color: #d49c14;
            color: white;
        }
        
        .table-custom thead {
            background-color: #f0b016;
            color: white;
        }
        
        .alert-custom {
            border-radius: 10px;
            border: none;
        }
        
        .stats-card {
            border-radius: 15px;
            padding: 20px;
            text-align: center;
            margin-bottom: 20px;
            color: white;
            border: none;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }
        
        .stats-card.total {
            background: linear-gradient(135deg, #27ae60 0%, #2ecc71 100%);
        }
        
        .stats-card.featured {
            background: linear-gradient(135deg, #f39c12 0%, #e67e22 100%);
        }
        
        .stats-card.categories {
            background: linear-gradient(135deg, #8e44ad 0%, #9b59b6 100%);
        }
        
        .stats-card h3 {
            font-size: 2rem;
            font-weight: bold;
            margin: 0;
        }
    </style>
</head>
<body>
    <!-- Left Sidebar -->
    <div class="sidebar">
        <div class="sidebar-header">
            <div class="logo-icon">
                <i class="fas fa-camera"></i>
            </div>
            <h3>Boundless Moments</h3>
            <small style="color: rgba(255,255,255,0.8);">Admin Panel</small>
        </div>
        
        <div class="sidebar-menu">
            <ul>
                <li>
                    <a href="dashboard.php">
                        <i class="fas fa-tachometer-alt"></i>
                        Dashboard
                    </a>
                </li>
                <li>
                    <a href="manage-gallery.php" class="active">
                        <i class="fas fa-images"></i>
                        Manage Gallery
                    </a>
                </li>
                <li>
                    <a href="messages.php">
                        <i class="fas fa-envelope"></i>
                        Messages
                    </a>
                </li>
                <li>
                    <a href="bookings.php">
                        <i class="fas fa-calendar"></i>
                        Bookings
                    </a>
                </li>
                <li>
                    <a href="settings.php">
                        <i class="fas fa-cog"></i>
                        Settings
                    </a>
                </li>
                <li style="margin-top: 20px; border-top: 1px solid rgba(255,255,255,0.2); padding-top: 20px;">
                    <a href="../index.php">
                        <i class="fas fa-external-link-alt"></i>
                        View Public Site
                    </a>
                </li>
                <li>
                    <a href="../logout.php">
                        <i class="fas fa-sign-out-alt"></i>
                        Logout
                    </a>
                </li>
            </ul>
        </div>
    </div>

    <!-- Top Header -->
    <div class="top-header">
        <h1><i class="fas fa-camera-retro me-2" style="color: #27ae60;"></i>Gallery Management</h1>
        <div class="header-actions">
            <span class="badge" style="background-color: #27ae60;">Images: <?php echo $total_images; ?></span>
            <span class="badge" style="background-color: #f39c12;">Featured: <?php echo $featured_images; ?></span>
            <button class="btn btn-success btn-sm">
                <i class="fas fa-plus me-1"></i>Add New Image
            </button>
        </div>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        
        <?php if (!empty($success_message)): ?>
            <div class="alert alert-success alert-custom"><?php echo htmlspecialchars($success_message); ?></div>
        <?php endif; ?>
        
        <?php if (!empty($error_message)): ?>
            <div class="alert alert-danger alert-custom"><?php echo htmlspecialchars($error_message); ?></div>
        <?php endif; ?>
        
        <!-- Gallery Statistics -->
        <div class="row mb-4">
            <div class="col-md-4">
                <div class="stats-card total">
                    <i class="fas fa-images fa-2x mb-2"></i>
                    <h3><?php echo $total_images; ?></h3>
                    <p class="mb-0">Total Images</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stats-card featured">
                    <i class="fas fa-star fa-2x mb-2"></i>
                    <h3><?php echo $featured_images; ?></h3>
                    <p class="mb-0">Featured Images</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stats-card categories">
                    <i class="fas fa-folder fa-2x mb-2"></i>
                    <h3><?php echo $total_categories; ?></h3>
                    <p class="mb-0">Categories</p>
                </div>
            </div>
        </div>
        
        <!-- CREATE: Add New Image Form -->
        <div class="card card-custom">
            <div class="card-header card-header-custom">
                <h5 class="mb-0"><i class="fas fa-plus me-2"></i>Add New Image</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label for="image_file" class="form-label">Select Image File</label>
                        <input type="file" class="form-control" id="image_file" name="image_file" 
                               accept="image/jpeg,image/png,image/gif,image/webp" required>
                        <small class="form-text text-muted">Supported formats: JPG, PNG, GIF, WebP. Maximum size: 5MB</small>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="title" class="form-label">Image Title</label>
                                <input type="text" class="form-control" id="title" name="title" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="category_id" class="form-label">Category</label>
                                <select class="form-control" id="category_id" name="category_id">
                                    <option value="">Select Category</option>
                                    <?php 
                                    if ($categories && $categories->num_rows > 0) {
                                        // Reset categories result pointer
                                        mysqli_data_seek($categories, 0);
                                        while($cat = $categories->fetch_assoc()): 
                                    ?>
                                        <option value="<?php echo $cat['category_id']; ?>">
                                            <?php echo htmlspecialchars($cat['category_name']); ?>
                                        </option>
                                    <?php 
                                        endwhile; 
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control" id="description" name="description" rows="3" placeholder="Enter image description..."></textarea>
                    </div>
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" id="is_featured" name="is_featured">
                        <label class="form-check-label" for="is_featured">
                            <i class="fas fa-star me-1"></i>Mark as Featured Image
                        </label>
                    </div>
                    <button type="submit" name="add_image" class="btn btn-custom">
                        <i class="fas fa-upload me-1"></i>Upload Image
                    </button>
                </form>
            </div>
        </div>
        
        <!-- READ: Display All Images -->
        <div class="card card-custom">
            <div class="card-header card-header-custom">
                <h5 class="mb-0"><i class="fas fa-images me-2"></i>Gallery Images</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-custom">
                            <tr>
                                <th>ID</th>
                                <th>Preview</th>
                                <th>Title</th>
                                <th>Category</th>
                                <th>Featured</th>
                                <th>Upload Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if ($images_result && $images_result->num_rows > 0) {
                                while($image = $images_result->fetch_assoc()) {
                                    ?>
                                    <tr>
                                        <td><strong>#<?php echo $image['image_id']; ?></strong></td>
                                        <td>
                                            <?php if (file_exists('../' . $image['file_path'])): ?>
                                                <img src="../<?php echo htmlspecialchars($image['file_path']); ?>" 
                                                     alt="<?php echo htmlspecialchars($image['image_title']); ?>" 
                                                     style="width: 50px; height: 50px; object-fit: cover; border-radius: 5px;">
                                            <?php else: ?>
                                                <div style="width: 50px; height: 50px; background-color: #f8f9fa; border: 1px solid #dee2e6; border-radius: 5px; display: flex; align-items: center; justify-content: center;">
                                                    <i class="fas fa-image text-muted"></i>
                                                </div>
                                            <?php endif; ?>
                                        </td>
                                        <td><?php echo htmlspecialchars($image['image_title']); ?></td>
                                        <td>
                                            <span class="badge bg-light text-dark">
                                                <?php echo htmlspecialchars($image['category_name'] ?? 'Uncategorized'); ?>
                                            </span>
                                        </td>
                                        <td>
                                            <?php if($image['is_featured']): ?>
                                                <span class="badge" style="background-color: #f0b016;">
                                                    <i class="fas fa-star me-1"></i>Featured
                                                </span>
                                            <?php else: ?>
                                                <span class="badge bg-secondary">No</span>
                                            <?php endif; ?>
                                        </td>
                                        <td><?php echo date('M d, Y', strtotime($image['upload_date'])); ?></td>
                                        <td>
                                            <a href="edit-image.php?id=<?php echo $image['image_id']; ?>" 
                                               class="btn btn-sm btn-outline-primary me-1">
                                                <i class="fas fa-edit"></i> Edit
                                            </a>
                                            <a href="?delete=<?php echo $image['image_id']; ?>&confirm=1" 
                                               class="btn btn-sm btn-outline-danger" 
                                               onclick="return confirm('Are you sure you want to delete this image?')">
                                                <i class="fas fa-trash"></i> Delete
                                            </a>
                                        </td>
                                    </tr>
                                    <?php
                                }
                            } else {
                                echo '<tr><td colspan="7" class="text-center py-4"><i class="fas fa-images fa-2x text-muted mb-2"></i><br>No images in gallery yet</td></tr>';
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Image file preview functionality
        document.getElementById('image_file').addEventListener('change', function(e) {
            const file = e.target.files[0];
            const existingPreview = document.getElementById('image-preview');
            
            // Remove existing preview
            if (existingPreview) {
                existingPreview.remove();
            }
            
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const preview = document.createElement('div');
                    preview.id = 'image-preview';
                    preview.className = 'mt-3 text-center';
                    preview.innerHTML = `
                        <div class="d-inline-block position-relative">
                            <img src="${e.target.result}" alt="Preview" style="max-width: 200px; max-height: 200px; border-radius: 10px; border: 2px solid #f0b016;">
                            <div class="mt-2">
                                <small class="text-muted">Preview: ${file.name}</small><br>
                                <small class="text-muted">Size: ${(file.size / 1024 / 1024).toFixed(2)} MB</small>
                            </div>
                        </div>
                    `;
                    
                    // Insert preview after the file input
                    document.getElementById('image_file').parentElement.appendChild(preview);
                };
                reader.readAsDataURL(file);
            }
        });
    </script>
</body>
</html>