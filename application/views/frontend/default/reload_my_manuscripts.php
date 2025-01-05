<?php foreach ($my_manuscripts as $my_manuscript):
    $manuscript_details = $this->crud_model->get_manuscript_by_id($my_manuscript['id'])->row_array();
    $researcher_details = $this->user_model->get_all_user($manuscript_details['user_id'])->row_array();?>

    <div class="col-lg-3">
        <div class="manuscript-box-wrap">
                <div class="manuscript-box">
                    <a href="<?php echo site_url('home/lesson/'.slugify($manuscript_details['title']).'/'.$my_manuscript['id']); ?>">
                        <div class="manuscript-image">
                            <img src="<?php echo $this->crud_model->get_manuscript_thumbnail_url($my_manuscript['id']); ?>" alt="" class="img-fluid">
                            <span class="play-btn"></span>
                        </div>
                    </a>
                    <div class="manuscript-details">
                        <a href="<?php echo site_url('home/manuscript/'.slugify($manuscript_details['title']).'/'.$my_manuscript['id']); ?>"><h5 class="title"><?php echo ellipsis($manuscript_details['title']); ?></h5></a>
                        <a href="<?php echo site_url('home/researcher_page/'.$researcher_details['id']); ?>"><p class="researchers"><?php echo $researcher_details['first_name'].' '.$researcher_details['last_name']; ?></p></a>

                        <div class="rating your-rating-box" onclick="event.preventDefault();" data-toggle="modal" data-target="#EditRatingModal">

                            <?php
                             $get_my_rating = $this->crud_model->get_user_specific_rating('manuscript', $my_manuscript['id']);
                             for($i = 1; $i < 6; $i++):?>
                             <?php if ($i <= $get_my_rating['rating']): ?>
                                <i class="fas fa-star filled"></i>
                            <?php else: ?>
                                <i class="fas fa-star"></i>
                             <?php endif; ?>
                            <?php endfor; ?>
                            <p class="your-rating-text" id = "<?php echo $my_manuscript['id']; ?>" onclick="getManuscriptDetailsForRatingModal(this.id)">
                                <span class="your"><?php echo get_phrase('your'); ?></span>
                                <span class="edit"><?php echo get_phrase('edit'); ?></span>
                                <?php echo get_phrase('rating'); ?>
                            </p>
                        </div>
                    </div>
                    <div class="row" style="padding: 5px;">
                        <div class="col-md-6">
                        <a href="<?php echo site_url('home/lesson/'.slugify($manuscript_details['title']).'/'.$my_manuscript['id'].'?title='.urlencode($manuscript_details['title'])); ?>" class="btn"><?php echo get_phrase('start_lesson'); ?></a>

                        </div>
                        <div class="col-md-6">
                             <a href="<?php echo site_url('home/lesson/'.slugify($manuscript_details['title']).'/'.$my_manuscript['id']); ?>" class="btn"><?php echo get_phrase('start_lesson'); ?></a>
                        </div>
                    </div>
                </div>
        </div>
    </div>
<?php endforeach; ?>
