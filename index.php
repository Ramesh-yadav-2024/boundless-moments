<?php
$page_title = 'Home';
require_once 'includes/db_connect.php';
include 'includes/header.php';
?>

        <!-- slider section -->
        <section class="slider_section position-relative">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-4">
                <div class="brand_box">
                    <a href="index.php">
                        <span style="font-size: 2.5rem; font-weight: bold; color: #f0b016;">Boundless<br>Moments</span>
                    </a>
                </div>
            </div>
            <div class="col-md-8 px-0">
                <div class="slider_content">
                    <div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
                        <div class="carousel-indicators-box">
                            01/
                            <ol class="carousel-indicators">
                                <li data-target="#carouselExampleIndicators" data-slide-to="0" class="active">
                                    01
                                </li>
                                <li data-target="#carouselExampleIndicators" data-slide-to="1">
                                    02
                                </li>
                                <li data-target="#carouselExampleIndicators" data-slide-to="2">
                                    03
                                </li>
                            </ol>
                        </div>

                        <div class="carousel-inner">
                            <div class="carousel-item active">
                                <div class="detail_box">
                                    <h1>
                                        CAPTURING<br />
                                        BOUNDLESS MOMENTS
                                    </h1>
                                    <p>
                                        Professional photography services for weddings, portraits, and special events
                                    </p>
                                    <div class="btn-box">
                                        <a href="contact.php" class="btn-1">Contact</a>
                                        <a href="about.php" class="btn-2">About Us</a>
                                    </div>
                                </div>
                            </div>
                            <div class="carousel-item">
                                <div class="detail_box">
                                    <h1>
                                        ARTISTIC<br />
                                        VISION
                                    </h1>
                                    <p>
                                        Creating timeless memories with professional excellence and artistic flair
                                    </p>
                                    <div class="btn-box">
                                        <a href="portfolio.php" class="btn-1">Portfolio</a>
                                        <a href="services.php" class="btn-2">Services</a>
                                    </div>
                                </div>
                            </div>
                            <div class="carousel-item">
                                <div class="detail_box">
                                    <h1>
                                        MEMORABLE<br />
                                        EXPERIENCES
                                    </h1>
                                    <p>
                                        Every moment tells a story - let us help you preserve yours forever
                                    </p>
                                    <div class="btn-box">
                                        <a href="booking.php" class="btn-1">Book Now</a>
                                        <a href="gallery.php" class="btn-2">Gallery</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="carousel-btn-box">
                            <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
                                <span class="sr-only">Previous</span>
                            </a>
                            <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
                                <span class="sr-only">Next</span>
                            </a>
                        </div>
                    </div>

                    <div class="img_container">
                        <div class="slider slider-nav slick_slider-nav">
                            <div class="img-box">
                                <img src="assets/images/img-1.jpg" alt="">
                            </div>
                            <div class="img-box">
                                <img src="assets/images/img-2.jpg" alt="">
                            </div>
                            <div class="img-box">
                                <img src="assets/images/img-3.jpg" alt="">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- end slider section -->
</div>
<!-- end hero_area -->

<!-- about section -->
<section class="about_section layout_padding">
    <div class="container-fluid">
        <div class="box">
            <div class="img_container">
                <div class="img-box b1">
                    <img src="assets/images/a-1.jpg" alt="">
                </div>
                <div class="img-box b2">
                    <img src="assets/images/a-2.jpg" alt="">
                </div>
            </div>
            <div class="detail-box">
                <h2>
                    About Our Studio
                </h2>
                <p>
                    At Boundless Moments, we specialize in capturing life's most precious memories. Our team of professional photographers brings artistic vision and technical excellence to every session, ensuring your moments are preserved beautifully.
                </p>
                <a href="about.php">
                    Read More
                </a>
            </div>
        </div>
    </div>
</section>
<!-- end about section -->

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
        <div class="box">
            <img src="assets/images/p-1.jpg" alt="">
            <div class="link-box">
                <a href="">
                    <img src="assets/images/link.png" alt="">
                </a>
                <h6>
                    There are many
                </h6>
            </div>
        </div>
        <div class="box">
            <img src="assets/images/p-2.jpg" alt="">
            <div class="link-box">
                <a href="">
                    <img src="assets/images/link.png" alt="">
                </a>
                <h6>
                    There are many
                </h6>
            </div>
        </div>
        <div class="box">
            <img src="assets/images/p-3.jpg" alt="">
            <div class="link-box">
                <a href="">
                    <img src="assets/images/link.png" alt="">
                </a>
                <h6>
                    There are many
                </h6>
            </div>
        </div>
        <div class="box">
            <img src="assets/images/p-4.jpg" alt="">
            <div class="link-box">
                <a href="">
                    <img src="assets/images/link.png" alt="">
                </a>
                <h6>
                    There are many
                </h6>
            </div>
        </div>
    </div>
</section>
<!-- end portfolio section -->

<!-- quality section -->
<section class="quality_section layout_padding">
    <div class="container">
        <div class="quality_container">
            <h2>
                Quality <br>
                Photography
            </h2>
            <div class="box">
                <div class="detail-box">
                    <div class="img-box">
                        <img src="assets/images/q-1.png" alt="">
                    </div>
                    <div class="text-box">
                        <h5>
                            Professional Staff
                        </h5>
                        <p>
                            Our experienced photographers deliver exceptional results with artistic vision
                        </p>
                    </div>
                </div>
                <div class="detail-box">
                    <div class="img-box">
                        <img src="assets/images/q-2.png" alt="">
                    </div>
                    <div class="text-box">
                        <h5>
                            Affordable Packages
                        </h5>
                        <p>
                            Professional photography services at competitive prices for every budget
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- end quality section -->

<!-- client section -->
<section class="client_section layout_padding">
    <div class="container">
        <div class="heading_container">
            <h2>
                Testimonial
            </h2>
            <p>
                Lorem Ipsum available, but the majority
            </p>
        </div>
        <div class="client_container">
            <a class="carousel-control-prev" href="#carouselExampleControls" role="button" data-slide="prev">
                <span class="sr-only">Previous</span>
            </a>
            <a class="carousel-control-next" href="#carouselExampleControls" role="button" data-slide="next">
                <span class="sr-only">Next</span>
            </a>
            <div id="carouselExampleControls" class="carousel slide" data-ride="carousel">
                <div class="carousel-inner">
                    <div class="carousel-item active">
                        <div class="box">
                            <div class="img-box">
                                <img src="assets/images/client.png" alt="">
                            </div>
                            <div class="detail-box">
                                <h4>
                                    Believable
                                </h4>
                                <p>
                                    There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form, by injected humour, or randomised words which don't look even slightly believable. If you There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form, by injected humour, or randomised words which don't look even slightly believable. If you
                                </p>
                                <img src="assets/images/quote.png" alt="">
                            </div>
                        </div>
                    </div>
                    <div class="carousel-item">
                        <div class="box">
                            <div class="img-box">
                                <img src="assets/images/client.png" alt="">
                            </div>
                            <div class="detail-box">
                                <h4>
                                    Believable
                                </h4>
                                <p>
                                    There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form, by injected humour, or randomised words which don't look even slightly believable. If you There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form, by injected humour, or randomised words which don't look even slightly believable. If you
                                </p>
                                <img src="assets/images/quote.png" alt="">
                            </div>
                        </div>
                    </div>
                    <div class="carousel-item">
                        <div class="box">
                            <div class="img-box">
                                <img src="assets/images/client.png" alt="">
                            </div>
                            <div class="detail-box">
                                <h4>
                                    Believable
                                </h4>
                                <p>
                                    There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form, by injected humour, or randomised words which don't look even slightly believable. If you There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form, by injected humour, or randomised words which don't look even slightly believable. If you
                                </p>
                                <img src="assets/images/quote.png" alt="">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- end client section -->

<!-- contact section -->
<section class="contact_section layout_padding">
    <div class="container">
        <div class="">
            <h2 class="">
                Get In Touch
            </h2>
        </div>
    </div>
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <form action="contact.php" method="POST">
                    <div>
                        <input type="text" name="name" placeholder="Name" required />
                    </div>
                    <div>
                        <input type="email" name="email" placeholder="Email" required />
                    </div>
                    <div>
                        <input type="text" name="phone" placeholder="Phone Number" />
                    </div>
                    <div>
                        <input type="text" name="message" class="message-box" placeholder="Message" required />
                    </div>
                    <div class="d-flex">
                        <button type="submit">
                            SEND
                        </button>
                    </div>
                </form>
            </div>
            <div class="col-md-6">
                <div class="img-box">
                    <img src="assets/images/contact-img.png" alt="">
                    <div class="img_overlay">
                        <h2>
                            Best <br>
                            Photo <br>
                            Studio
                        </h2>
                        <a href="contact.php">
                            Contact Us
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- end contact section -->

<?php include 'includes/footer.php'; ?>