<?php 
 include("header.php");
?>

<!-- inner banner -->
<section class="inner-bg over-layer-white" style="background-image: url('img/bg/4.jpg')">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="mini-title">
                    <h3>Contact Us</h3>
                    <p><a href="index.php">Home</a> <span class="fa fa-angle-right"></span> Contact Us</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- service start -->
<section class="contact_us bg-ff">
    <div class="container contact_info">
        <div class="section-content">
            <div class="row">
                <div class="col-sm-12 col-md-4">
                    <div class="service-item style-1">
                        <div class="service-icon">
                            <i class="pe-7s-map"></i>
                        </div>
                        <div class="content">
                            <h5>Location</h5>
                            <p>----------------------------------</p>
                            <p>--------------</p>
                        </div>
                    </div>
                </div>
                <div class="col-sm-12 col-md-4">
                    <div class="service-item style-1">
                        <div class="service-icon">
                            <i class="pe-7s-clock"></i>
                        </div>
                        <div class="content">
                            <h5>Business Hours</h5>
                            <!-- <p>Monday-Friday: 10am to 8pm <br>Saturday: 11am to 3pm</p> -->
                            <p>----------------------------</p>
                            <p>-----------------</p>
                        </div>
                    </div>
                </div>
                <div class="col-sm-12 col-md-4">
                    <div class="service-item style-1">
                        <div class="service-icon">
                            <i class="pe-7s-mail-open"></i>
                        </div>
                        <div class="content">
                            <h5>Email</h5>
                            <p>info@thelawmap.com</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <hr>
    <div class="container contact_form">
        <div class="section-content">
            <div class="row">
                <div class="col-md-7">
                    <div class="mini-title">
                        <h3>Contact Form</h3>
                    </div>
                    <form action="#" class="form" method="post">
                        <div class="col-md-12">
                            <div class="form-group">
                                <input type="text" name="your_name" class="form-control contact-error" placeholder="Your Full Name*" required>
                                <div class="form-grad-border"></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <input type="text" name="domain" class="form-control contact-error" placeholder="Your Phone*">
                                <div class="form-grad-border"></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <input type="email" name="email" class="form-control contact-error" id="email" placeholder="Email Address*" required>
                                <div class="form-grad-border"></div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <select name="" class="form-control contact-error">
                                    <option>Select reason for contact</option>
                                    <option value="1">Professional advice for solicitors (members only)</option>
                                    <option value="2">Library queries (members only)</option>
                                    <option value="3">Find a Solicitor profiles (members only)</option>
                                    <option value="4">Find a Solicitor help for consumers</option>
                                    <option value="6">TheLawMap membership</option>
                                    <option value="7">Web content or technical issues</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <textarea name="message" class="form-control contact-error" id="message" cols="30" rows="4" placeholder="Write Message"></textarea>
                                <div class="form-grad-border"></div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <button type="submit" class="btn btn-simple" data-text="Send Message"><span>Send Message</span><i class="ion-arrow-right-c"></i></button> 
                            </div>
                        </div>
                    </form>
                </div>
                <div class="col-md-5">
                    <div class="mini-title">
                        <h3>Location</h3>
                    </div>
                    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d55565170.29301636!2d-132.08532758867793!3d31.786060306224!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x54eab584e432360b%3A0x1c3bb99243deb742!2sUnited+States!5e0!3m2!1sen!2sin!4v1507984560598" width="100%" height="310" frameborder="0" style="border:0" allowfullscreen></iframe>
                </div>
            </div>
        </div>
    </div>
</section>




<?php 
 include("footer.php"); 
?>