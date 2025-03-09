<footer class="footer-area">
            <div class="container-xl">
                <div class="row">

    <div class="container-fluid footer bg-dark wow fadeIn" data-wow-delay=".3s">
        <div class="container pt-5 pb-4">
            <div class="row g-5">
                <div class="col-lg-3 col-md-6">
                    <a href="index.html">
                        <h1 class="text-white fw-bold d-block" style="color: #f5ec3a!important;">T<span class="text-primary">RIS</span> </h1>
                    </a>
                    <p class="mt-4 text-light">Innovate with Technological Research Information System, where Technology tailored to match your individual learning pace and needs.</p>
                    <div class="d-flex hightech-link" style="justify-content: center !important;">
                        <a href="" class="btn-light nav-fill btn btn-square rounded-circle me-2" style="background-color:white; border-color: #000000;"><i class="fab fa-facebook-f text-primary"></i></a>
                        <a href="" class="btn-light nav-fill btn btn-square rounded-circle me-2"style="background-color:white; border-color: #000000;"><i class="fab fa-twitter text-primary"></i></a>
                        <a href="" class="btn-light nav-fill btn btn-square rounded-circle me-2"style="background-color:white; border-color: #000000;"><i class="fab fa-instagram text-primary"></i></a>
                        <a href="" class="btn-light nav-fill btn btn-square rounded-circle me-0"style="background-color:white; border-color: #000000;"><i class="fab fa-linkedin-in text-primary"></i></a>
                        <a href="" class="btn-light nav-fill btn btn-square rounded-circle me-2"style="background-color:white; border-color: #000000;"><i class="fab fa-youtube text-primary"></i></a>
                        <a href="" class="btn-light nav-fill btn btn-square rounded-circle me-2"style="background-color:white; border-color: #000000;"><i class="fab fa-tumblr text-primary"></i></a>
                        <a href="" class="btn-light nav-fill btn btn-square rounded-circle me-2"style="background-color:white; border-color: #000000;"><i class="fab fa-google text-primary"></i></a>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <a href="#" class="h3 text-primary">Quick Links</a>
                    <div class="mt-4 d-flex flex-column short-link">
                        <a href="<?php echo site_url('home'); ?>" class="mb-2 text-white"><i class="fas fa-angle-right text-primary me-2"></i>Home</a>
                        <a href="<?php echo site_url('home/about_us'); ?>" class="mb-2 text-white"><i class="fas fa-angle-right text-primary me-2"></i>About</a>
                        <a href="<?php echo site_url('home/privacy_policy'); ?>" class="mb-2 text-white"><i class="fas fa-angle-right text-primary me-2"></i>Privacy Policy</a>
                        <a href="<?php echo site_url('home/terms_and_condition'); ?>" class="mb-2 text-white"><i class="fas fa-angle-right text-primary me-2"></i>Terms and Condition</a>
               
                    </div>
                </div>

                <div class="col-lg-3 col-md-6">
                    <a href="#" class="h3 text-primary">Contact Us</a>
                    <div class="text-white mt-4 d-flex flex-column contact-link">
                        <a href="#" class="pb-3 text-light border-bottom border-primary"><i class="fas fa-map-marker-alt text-primary me-2"></i>University of Makati, Philippines</a>
                        <a href="#" class="py-3 text-light border-bottom border-primary"><i class="fas fa-phone-alt text-primary me-2"></i> 888 888 8888 | 0988888888 </a>
                        <a href="#" class="py-3 text-light border-bottom border-primary"><i class="fas fa-envelope text-primary me-2"></i>umak@studentresearcher.online</a>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <iframe 
                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3859.877078018597!2d121.05357311436774!3d14.549203189829108!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3397c91bc817b2a3%3A0xa71a139cc9c97a9!2sUniversity%20of%20Makati%20-%20Administrative%20Building!5e0!3m2!1sen!2sph!4v1696258876145!5m2!1sen!2sph" 
                        width="100%" 
                        height="250" 
                        style="border:0;" 
                        allowfullscreen="" 
                        loading="lazy">
                    </iframe>
                </div>

            </div>
            <hr class="text-light mt-5 mb-4">
            <div class="row">
                <div class="col-md-6 text-center text-md-start">
                    <span class="text-light"><a href="#" class="text-primary"><i class="fas fa-copyright text-primary me-2"></i>2025</a>, All right reserved.</span>
                </div>
                <div class="col-md-6 text-center text-md-end">
                    <span class="text-light">Designed By<a href="#" class="text-primary"> Group 10</a></span>
                </div>
            </div>
        </div>
    </div>

                </div>
            </div>
        </footer>

        <!-- PAYMENT MODAL -->
        <!-- Modal -->
        <?php
            $paypal_info = json_decode(get_settings('paypal'), true);
            $stripe_info = json_decode(get_settings('stripe_keys'), true);
            if ($paypal_info[0]['active'] == 0) {
                $paypal_status = 'disabled';
            }else {
                $paypal_status = '';
            }
            if ($stripe_info[0]['active'] == 0) {
                $stripe_status = 'disabled';
            }else {
                $stripe_status = '';
            }
         ?>
        <div class="modal fade" id="paymentModal" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content payment-in-modal">
                    <div class="modal-header">
                        <h5 class="modal-title"><?php echo get_phrase('checkout'); ?>!</h5>
                        <button type="button" class="close" data-dismiss="modal">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <form action="<?php echo site_url('home/paypal_checkout'); ?>" method="post">
                                    <input type="hidden" class = "total_price_of_checking_out" name="total_price_of_checking_out" value="">
                                    <button type="submit" class="btn btn-default paypal" <?php echo $paypal_status; ?>><?php echo get_phrase('paypal'); ?></button>
                                </form>
                            </div>
                            <div class="col-md-6">
                                <form action="<?php echo site_url('home/stripe_checkout'); ?>" method="post">
                                    <input type="hidden" class = "total_price_of_checking_out" name="total_price_of_checking_out" value="">
                                    <button type="submit" class="btn btn-primary stripe" <?php echo $stripe_status; ?>><?php echo get_phrase('stripe'); ?></button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div><!-- Modal -->

        <!-- Modal -->
        <div class="modal fade multi-step" id="EditRatingModal" tabindex="-1" role="dialog" aria-hidden="true" reset-on-close="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content edit-rating-modal">
                    <div class="modal-header">
                        <h5 class="modal-title step-1" data-step="1"><?php echo get_phrase('step').' 1'; ?></h5>
                        <h5 class="modal-title step-2" data-step="2"><?php echo get_phrase('step').' 2'; ?></h5>
                        <h5 class="m-progress-stats modal-title">
                            &nbsp;of&nbsp;<span class="m-progress-total"></span>
                        </h5>

                        <button type="button" class="close" data-dismiss="modal">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="m-progress-bar-wrapper">
                        <div class="m-progress-bar">
                        </div>
                    </div>
                    <div class="modal-body step step-1">
                        <div class="container-fluid">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="modal-rating-box">
                                        <h4 class="rating-title"><?php echo get_phrase('how_would_you_rate_this_manuscript_overall'); ?>?</h4>
                                        <fieldset class="your-rating">

                                            <input type="radio" id="star5" name="rating" value="5" />
                                            <label class = "full" for="star5"></label>

                                        	<!-- <input type="radio" id="star4half" name="rating" value="4 and a half" />
                                            <label class="half" for="star4half"></label> -->

                                        	<input type="radio" id="star4" name="rating" value="4" />
                                            <label class = "full" for="star4"></label>

                                        	<!-- <input type="radio" id="star3half" name="rating" value="3 and a half" />
                                            <label class="half" for="star3half"></label> -->

                                        	<input type="radio" id="star3" name="rating" value="3" />
                                            <label class = "full" for="star3"></label>

                                        	<!-- <input type="radio" id="star2half" name="rating" value="2 and a half" />
                                            <label class="half" for="star2half"></label> -->

                                        	<input type="radio" id="star2" name="rating" value="2" />
                                            <label class = "full" for="star2"></label>

                                        	<!-- <input type="radio" id="star1half" name="rating" value="1 and a half" />
                                            <label class="half" for="star1half"></label> -->

                                        	<input type="radio" id="star1" name="rating" value="1" />
                                            <label class = "full" for="star1"></label>

                                        	<!-- <input type="radio" id="starhalf" name="rating" value="half" />
                                            <label class="half" for="starhalf"></label> -->

                                        </fieldset>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="modal-manuscript-preview-box">
                                        <div class="card">
                                            <img class="card-img-top img-fluid" id = "manuscript_thumbnail_1" alt="">
                                            <div class="card-body">
                                                <h5 class="card-title" class = "manuscript_title_for_rating" id = "manuscript_title_1"></h5>
                                                <p class="card-text" id = "researcher_details">

                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="modal-body step step-2">
                        <div class="container-fluid">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="modal-rating-comment-box">
                                        <h4 class="rating-title"><?php echo get_phrase('write_a_public_review'); ?></h4>
                                        <textarea id = "review_of_a_manuscript" name = "review_of_a_manuscript" placeholder="<?php echo get_phrase('describe_your_experience_what_you_got_out_of_the_manuscript_and_other_helpful_highlights').'. '.get_phrase('what_did_the_researcher_do_well_and_what_could_use_some_improvement') ?>?" maxlength="65000" class="form-control"></textarea>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="modal-manuscript-preview-box">
                                        <div class="card">
                                            <img class="card-img-top img-fluid" id = "manuscript_thumbnail_2" alt="">
                                            <div class="card-body">
                                                <h5 class="card-title" class = "manuscript_title_for_rating" id = "manuscript_title_2"></h5>
                                                <p class="card-text">
                                                    -
                                                    <?php
                                                        $admin_details = $this->user_model->get_admin_details()->row_array();
                                                        echo $admin_details['first_name'].' '.$admin_details['last_name'];
                                                     ?>
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <input type="hidden" name="manuscript_id" id = "manuscriptid_for_rating" value="">
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary next step step-1" data-step="1" onclick="sendEvent(2)"><?php echo get_phrase('next'); ?></button>
                        <button type="button" class="btn btn-primary previous step step-2 mr-auto" data-step="2" onclick="sendEvent(1)"><?php echo get_phrase('previous'); ?></button>
                        <button type="button" class="btn btn-primary publish step step-2" onclick="publishRating($('#manuscript_id_for_rating').val())" id = ""><?php echo get_phrase('publish'); ?></button>
                    </div>
                </div>
            </div>
        </div><!-- Modal -->


        <script type="text/javascript">
            function publishRating(manuscript_id) {
                var review = $('#review_of_a_manuscript').val();
                var starRating = 0;
                $('input:radio[name="rating"]:checked').each(function() {
                    starRating = $('input:radio[name="rating"]:checked').val();
                });

                $.ajax({
                    type : 'POST',
                    url  : '<?php echo site_url('home/rate_manuscript'); ?>',
                    data : {manuscript_id : manuscript_id, review : review, starRating : starRating},
                    success : function(response) {
                        console.log(response);
                        $('#EditRatingModal').modal('hide');
                        location.reload();
                    }
                });
            }
        </script>
<style>
    /*FOOTER*/
footer {
    margin-top: 50px; }
.footer .short-link a,
.footer .help-link a,
.footer .contact-link a {
    transition: .5s; }
.footer .short-link a:hover,
.footer .help-link a:hover,
.footer .contact-link a:hover {
    letter-spacing: 1px; }
.footer .hightech-link a:hover {
    background: var(--bs-primary);
    border: 0; }

</style>