<?php
$page_title = 'About';
include 'includes/header.php';
?>

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
                    Welcome to Boundless Moments, where we believe every moment tells a story worth capturing. Founded in 2020, we have been dedicated to preserving life's most precious memories through the art of photography.
                </p>
                <p>
                    Our passion lies in creating timeless images that you'll treasure for generations. Whether it's the joy of your wedding day, the warmth of a family portrait, or the beauty of nature, we approach each project with creativity and professionalism.
                </p>
                <a href="portfolio.php">
                    View Our Work
                </a>
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
                Boundless Moments
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
                            Award-winning photographers with years of experience in capturing perfect moments
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
                Our Achievements
            </h2>
            <p>
                Recognition and milestones in our photography journey
            </p>
        </div>
        <div class="client_container">
            <div id="carouselExampleControls" class="carousel slide" data-ride="carousel">
                <div class="carousel-inner">
                    <div class="carousel-item active">
                        <div class="box">
                            <div class="img-box">
                                <i class="fas fa-award" style="font-size: 4rem; color: #f0b016;"></i>
                            </div>
                            <div class="detail-box">
                                <h4>
                                    Award Winning
                                </h4>
                                <p>
                                    Recognized for excellence in wedding and portrait photography by the Professional Photographers Association. Our dedication to artistic vision and technical expertise has earned us multiple awards in the industry.
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="carousel-item">
                        <div class="box">
                            <div class="img-box">
                                <i class="fas fa-users" style="font-size: 4rem; color: #f0b016;"></i>
                            </div>
                            <div class="detail-box">
                                <h4>
                                    500+ Happy Clients
                                </h4>
                                <p>
                                    Trusted by hundreds of families and couples throughout Nepal and internationally. Our commitment to capturing your most precious moments has built lasting relationships with our clients who return for life's important milestones.
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="carousel-item">
                        <div class="box">
                            <div class="img-box">
                                <i class="fas fa-globe" style="font-size: 4rem; color: #f0b016;"></i>
                            </div>
                            <div class="detail-box">
                                <h4>
                                    Worldwide Service
                                </h4>
                                <p>
                                    Available for destination weddings and international projects. Based in Kathmandu, we serve clients throughout Nepal and beyond, bringing our unique vision and expertise to every location and cultural celebration.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <a class="carousel-control-prev" href="#carouselExampleControls" role="button" data-slide="prev">
                <span class="sr-only">Previous</span>
            </a>
            <a class="carousel-control-next" href="#carouselExampleControls" role="button" data-slide="next">
                <span class="sr-only">Next</span>
            </a>
        </div>
    </div>
</section>
<!-- end client section -->

<?php include 'includes/footer.php'; ?>