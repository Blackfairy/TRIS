<?php
$researcher_details = $this->user_model->get_all_user($researcher_id)->row_array();
$social_links  = json_decode($researcher_details['social_links'], true);
$manuscript_ids = $this->crud_model->get_researcher_wise_manuscripts($researcher_id, 'simple_array');
?>
<section class="researcher-header-area">
    <div class="container">
        <div class="row">
            <div class="col">
                <h1 class="researcher-name"><?php echo $researcher_details['first_name'].' '.$researcher_details['last_name']; ?></h1>
                <h2 class="researcher-title"><?php echo $researcher_details['title']; ?></h2>
            </div>
        </div>
    </div>
</section>

<section class="researcher-details-area">
    <div class="container">
        <div class="row">
            <div class="col-lg-3">
                <div class="researcher-left-box text-center">
                    <div class="researcher-image">
                        <img src="<?php echo $this->user_model->get_user_image_url($researcher_details['id']);?>" alt="" class="img-fluid">
                    </div>
                    <div class="researcher-social">
                        <ul>
                            <li><a href="<?php echo $social_links['twitter']; ?>" target="_blank"><i class="fab fa-twitter"></i></a></li>
                            <li><a href="<?php echo $social_links['facebook']; ?>" target="_blank"><i class="fab fa-facebook-f"></i></a></li>
                            <li><a href="<?php echo $social_links['linkedin']; ?>" target="_blank"><i class="fab fa-linkedin-in"></i></a></li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-lg-9">
                <div class="researcher-right-box">

                    <div class="biography-content-box view-more-parent">
                        <!-- <div class="view-more" onclick="viewMore(this,'hide')"><b><?php echo get_phrase('show_full_biography'); ?></b></div> -->
                        <div class="biography-content">
                            <?php echo $researcher_details['biography']; ?>
                        </div>
                    </div>

                    <div class="researcher-stat-box">
                        <ul>
                            <li>
                                <div class="small"><?php echo get_phrase('total_student'); ?></div>
                                <div class="num">
                                    <?php
                                    $this->db->select('user_id');
                                    $this->db->distinct();
                                    $this->db->where_in('manuscript_id', $manuscript_ids);
                                    echo $this->db->get('enrol')->num_rows();?>
                                </div>
                            </li>
                            <li>
                                <div class="small"><?php echo get_phrase('manuscripts'); ?></div>
                                <div class="num"><?php echo sizeof($manuscript_ids); ?></div>
                            </li>
                            <li>
                                <div class="small"><?php echo get_phrase('reviews'); ?></div>
                                <div class="num"><?php echo $this->crud_model->get_researcher_wise_manuscript_ratings($researcher_id, 'manuscript')->num_rows(); ?></div>
                            </li>
                        </ul>
                    </div>

                </div>
            </div>
        </div>
    </div>
</section>
