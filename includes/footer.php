    <?php if (basename($_SERVER['PHP_SELF']) != 'index.php'): ?>
</div>
<!-- end hero_area -->
    <?php endif; ?>
    
    <!-- info section -->
    <section class="info_section layout_padding2">
        <div class="container">
            <div class="row">
                <div class="col-md-3">
                    <div class="info-logo">
                        <a href="index.php">
                            <span style="font-size: 1.5rem; font-weight: bold; color: #f0b016;">Boundless Moments</span>
                        </a>
                        <p>
                            Capturing life's most precious moments with artistic vision and professional excellence. Creating timeless memories that last forever.
                        </p>
                    </div>
                </div>
                <div class="col-lg-2 col-md-3 offset-lg-1">
                    <div class="info-nav">
                        <h4>
                            Navigation
                        </h4>
                        <ul>
                            <li>
                                <a href="index.php">
                                    Home
                                </a>
                            </li>
                            <li>
                                <a href="about.php">
                                    About
                                </a>
                            </li>
                            <li>
                                <a href="portfolio.php">
                                    Portfolio
                                </a>
                            </li>
                            <li>
                                <a href="gallery.php">
                                    Gallery
                                </a>
                            </li>
                            <li>
                                <a href="services.php">
                                    Services
                                </a>
                            </li>
                            <li>
                                <a href="contact.php">
                                    Contact Us
                                </a>
                            </li>
                            <?php if (isset($_SESSION['user_id'])): ?>
                                <li>
                                    <a href="admin/dashboard.php">
                                        Dashboard
                                    </a>
                                </li>
                            <?php else: ?>
                                <li>
                                    <a href="login.php">
                                        Login
                                    </a>
                                </li>
                            <?php endif; ?>
                        </ul>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="info-contact">
                        <h4>
                            Contact Info
                        </h4>
                        <div class="location">
                            <h6>
                                Main Office Address:
                            </h6>
                            <a href="">
                                <img src="assets/images/location.png" alt="">
                                <span>
                                    ISMT College, New Baneshwor, Kathmandu, Nepal
                                </span>
                            </a>
                        </div>
                        <div class="call">
                            <h6>
                                Customer Service:
                            </h6>
                            <a href="">
                                <img src="assets/images/call.png" alt="">
                                <span>
                                    ( +977-1-4782573 )
                                </span>
                            </a>
                        </div>
                        <div class="call">
                            <a href="">
                                <img src="assets/images/mail.png" alt="">
                                <span>
                                    info@boundlessmoments.com
                                </span>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-2 col-md-3 offset-lg-1">
                    <div class="discover">
                        <h4>
                            Services
                        </h4>
                        <ul>
                            <li>
                                <a href="services.php">
                                    Wedding Photography
                                </a>
                            </li>
                            <li>
                                <a href="services.php">
                                    Portrait Sessions
                                </a>
                            </li>
                            <li>
                                <a href="services.php">
                                    Event Photography
                                </a>
                            </li>
                            <li>
                                <a href="booking.php">
                                    Book Session
                                </a>
                            </li>
                        </ul>
                        <div class="social-box">
                            <a href="">
                                <img src="assets/images/fb.png" alt="">
                            </a>
                            <a href="">
                                <img src="assets/images/twitter.png" alt="">
                            </a>
                            <a href="">
                                <img src="assets/images/insta.png" alt="">
                            </a>
                            <a href="">
                                <img src="assets/images/linkedin.png" alt="">
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="info_form">
                <h4>
                    Subscribe to Newsletter
                </h4>
                <form action="">
                    <input type="text" placeholder="Enter your email">
                    <div class="d-flex justify-content-end">
                        <button>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </section>
    <!-- end info_section -->

    <!-- footer section -->
    <section class="container-fluid footer_section">
        <p>
            &copy; 2024 Boundless Moments. All Rights Reserved |
            <a href="#">Privacy Policy</a> |
            <a href="#">Terms of Service</a>
        </p>
    </section>
    <!-- footer section -->

    <script type="text/javascript" src="assets/js/jquery-3.4.1.min.js"></script>
    <script type="text/javascript" src="assets/js/bootstrap.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.5.9/slick.min.js"></script>

    <?php if (isset($additional_js)): ?>
        <?php foreach ($additional_js as $js): ?>
            <script src="<?php echo $js; ?>"></script>
        <?php endforeach; ?>
    <?php endif; ?>

    <script>
        $('.slider-for').slick({
            autoplay: true,
            autoplaySpeed: 3000,
            slidesToShow: 1,
            slidesToScroll: 1,
            arrows: false,
            fade: true,
            asNavFor: '.slider-nav'
        });
        $('.slider-nav').slick({
            slidesToShow: 3,
            slidesToScroll: 1,
            asNavFor: '.slider-for',
            dots: true,
            centerMode: true,
            focusOnSelect: true,
        });
    </script>

    <script>
        $('.slick-carousel').slick({
            infinite: true,
            slidesToShow: 3,
            slidesToScroll: 1,
            responsive: [{
                breakpoint: 768,
                settings: {
                    slidesToShow: 2,
                    slidesToScroll: 1,
                }
            },
            {
                breakpoint: 420,
                settings: {
                    slidesToShow: 1,
                    slidesToScroll: 3,
                }
            }]
        });
    </script>
</body>
</html>