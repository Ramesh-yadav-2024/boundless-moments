<?php 
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html>
<head>
    <!-- Basic -->
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <!-- Mobile Metas -->
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <!-- Site Metas -->
    <meta name="keywords" content="photography, portfolio, moments, boundless" />
    <meta name="description" content="Boundless Moments - Professional Photography Portfolio" />
    <meta name="author" content="Boundless Moments" />

    <title><?php echo isset($page_title) ? $page_title . ' - Boundless Moments' : 'Boundless Moments - Photography Portfolio'; ?></title>

    <!-- slider stylesheet -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.5.9/slick.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.5.9/slick-theme.min.css">

    <!-- bootstrap core css -->
    <link rel="stylesheet" type="text/css" href="assets/css/bootstrap.css" />

    <!-- fonts style -->
    <link href="https://fonts.googleapis.com/css?family=Dosis:500|Raleway:400,600,700&display=swap" rel="stylesheet">
    <!-- Custom styles for this template -->
    <link href="assets/css/style.css" rel="stylesheet" />
    <!-- responsive style -->
    <link href="assets/css/responsive.css" rel="stylesheet" />
    
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <?php if (isset($additional_css)): ?>
        <?php foreach ($additional_css as $css): ?>
            <link rel="stylesheet" href="<?php echo $css; ?>">
        <?php endforeach; ?>
    <?php endif; ?>
</head>

<body<?php echo (basename($_SERVER['PHP_SELF']) != 'index.php') ? ' class="sub_page"' : ''; ?>>
    <?php if (basename($_SERVER['PHP_SELF']) == 'index.php'): ?>
    <div class="hero_area">
        <div class="main slick_main">
            <div class="slider slider-for main_img-container">
                <div class="main-img-box b1"></div>
                <div class="main-img-box b2"></div>
                <div class="main-img-box b3"></div>
            </div>
        </div>
        <!-- header section starts -->
        <header class="header_section">
            <div class="container-fluid">
                <nav class="navbar navbar-expand-lg custom_nav-container">
    <?php else: ?>
        <div class="hero_area">
            <div class="main slick_main">
            </div>
            <!-- header section starts -->
            <header class="header_section">
                <div class="container-fluid">
                    <nav class="navbar navbar-expand-lg custom_nav-container">
    <?php endif; ?>
                <a href="index.php" class="navbar-brand">
                    <span style="font-size: 1.8rem; font-weight: bold; color: #f0b016;">Boundless Moments</span>
                </a>
                <button class="navbar-toggler ml-auto" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <div class="d-flex ml-auto flex-column flex-lg-row align-items-center">
                        <ul class="navbar-nav">
                            <li class="nav-item <?php echo (basename($_SERVER['PHP_SELF']) == 'index.php') ? 'active' : ''; ?>">
                                <a class="nav-link" href="index.php">Home <span class="sr-only">(current)</span></a>
                            </li>
                            <li class="nav-item <?php echo (basename($_SERVER['PHP_SELF']) == 'about.php') ? 'active' : ''; ?>">
                                <a class="nav-link" href="about.php">About</a>
                            </li>
                            <li class="nav-item <?php echo (basename($_SERVER['PHP_SELF']) == 'portfolio.php') ? 'active' : ''; ?>">
                                <a class="nav-link" href="portfolio.php">Portfolio</a>
                            </li>
                            <li class="nav-item <?php echo (basename($_SERVER['PHP_SELF']) == 'gallery.php') ? 'active' : ''; ?>">
                                <a class="nav-link" href="gallery.php">Gallery</a>
                            </li>
                            <li class="nav-item <?php echo (basename($_SERVER['PHP_SELF']) == 'services.php') ? 'active' : ''; ?>">
                                <a class="nav-link" href="services.php">Services</a>
                            </li>
                            <li class="nav-item <?php echo (basename($_SERVER['PHP_SELF']) == 'experience.php') ? 'active' : ''; ?>">
                                <a class="nav-link" href="experience.php">Experience</a>
                            </li>
                            <li class="nav-item <?php echo (basename($_SERVER['PHP_SELF']) == 'contact.php') ? 'active' : ''; ?>">
                                <a class="nav-link" href="contact.php">Contact</a>
                            </li>
                            <li class="nav-item <?php echo (basename($_SERVER['PHP_SELF']) == 'booking.php') ? 'active' : ''; ?>">
                                <a class="nav-link" href="booking.php">Booking</a>
                            </li>
                            
                            <?php if (isset($_SESSION['user_id'])): ?>
                                <li class="nav-item">
                                    <a class="nav-link" href="admin/dashboard.php">Dashboard</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="logout.php">Logout</a>
                                </li>
                            <?php else: ?>
                                <li class="nav-item">
                                    <a class="nav-link" href="login.php">Login</a>
                                </li>
                            <?php endif; ?>
                        </ul>
                    </div>
                </div>
                </nav>
            </div>
        </header>
        <!-- end header section -->