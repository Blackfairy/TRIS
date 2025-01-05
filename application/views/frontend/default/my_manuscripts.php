<?php

$my_manuscripts = $this->user_model->my_manuscripts()->result_array();

$categories = array();
foreach ($my_manuscripts as $my_manuscript) {
    $manuscript_details = $this->crud_model->get_manuscript_by_id($my_manuscript['manuscript_id'])->row_array();
    if (!in_array($manuscript_details['category_id'], $categories)) {
        array_push($categories, $manuscript_details['category_id']);
    }
}
?>
<section class="page-header-area my-manuscript-area">
    <div class="container">
        <div class="row">
            <div class="col">
                <h1 class="page-title"><?php echo get_phrase('my_manuscripts'); ?></h1>
                <ul>
                  <li class="active"><a href="<?php echo site_url('home/my_manuscripts'); ?>"><?php echo get_phrase('all_manuscripts'); ?></a></li>
                  
                  <li><a href="<?php echo site_url('home/my_messages'); ?>"><?php echo get_phrase('my_messages'); ?></a></li>
                  <li><a href="<?php echo site_url('home/purchase_history'); ?>"><?php echo get_phrase('purchase_history'); ?></a></li>
                  <li><a href="<?php echo site_url('home/profile/user_profile'); ?>"><?php echo get_phrase('user_profile'); ?></a></li>
                </ul>
            </div>
        </div>
    </div>
</section>

<section class="my-manuscripts-area">
    <div class="container">
        <div class="row align-items-baseline">
            <div class="col-lg-6">
                <div class="my-manuscript-filter-bar filter-box">
                    <span><?php echo get_phrase('filter_by'); ?></span>
                    <div class="btn-group">
                        <a class="btn btn-outline-secondary dropdown-toggle all-btn" href="#"data-toggle="dropdown">
                            <?php echo get_phrase('categories'); ?>
                        </a>

                        <div class="dropdown-menu">
                            <?php foreach ($categories as $category):
                                $category_details = $this->crud_model->get_categories($category)->row_array();
                                ?>
                                <a class="dropdown-item" href="#" id = "<?php echo $category; ?>" onclick="getManuscriptsByCategoryId(this.id)"><?php echo $category_details['name']; ?></a>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <!-- <div class="btn-group">
                        <a class="btn btn-outline-secondary dropdown-toggle" href="#"data-toggle="dropdown">
                            <?php echo get_phrase('researchers'); ?>
                        </a>

                        <div class="dropdown-menu">
                            <a class="dropdown-item" href="#"><?php echo $instructor_details['first_name'].' '.$instructor_details['last_name']; ?></a>

                        </div>
                    </div> -->
                    <div class="btn-group">
                        <a href="<?php echo site_url('home/my_manuscripts'); ?>" class="btn reset-btn" disabled><?php echo get_phrase('reset'); ?></a>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="my-manuscript-search-bar">
                    <form action="">
                        <div class="input-group">
                            <input type="text" class="form-control" placeholder="<?php echo get_phrase('search_my_manuscripts'); ?>" onkeyup="getManuscriptsBySearchString(this.value)">
                            <div class="input-group-append">
                                <button class="btn" type="submit"><i class="fas fa-search"></i></button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="row no-gutters" id="my_manuscripts_area">
    <?php foreach ($my_manuscripts as $my_manuscript):
        $manuscript_details = $this->crud_model->get_manuscript_by_id($my_manuscript['manuscript_id'])->row_array();
        $instructor_details = $this->user_model->get_all_user($manuscript_details['user_id'])->row_array();?>

        <div class="col-lg-3">
            <div class="manuscript-box-wrap">
                <div class="manuscript-box">
                    <a href="<?php echo site_url('home/lesson/'.slugify($manuscript_details['title']).'/'.$my_manuscript['manuscript_id']); ?>">
                        <div class="manuscript-image">
                            <img src="<?php echo $this->crud_model->get_manuscript_thumbnail_url($my_manuscript['manuscript_id']); ?>" alt="" class="img-fluid">
                            <span class="play-btn"></span>
                        </div>
                    </a>
                    <div class="manuscript-details">
                        <a href="<?php echo site_url('home/manuscript/'.slugify($manuscript_details['title']).'/'.$my_manuscript['manuscript_id']); ?>"><h5 class="title"><?php echo ellipsis($manuscript_details['title']); ?></h5></a>
                        <a href="<?php echo site_url('home/instructor_page/'.$instructor_details['id']); ?>"><p class="researchers"><?php echo $instructor_details['last_name']; ?></p></a>

                        <div class="rating your-rating-box" onclick="event.preventDefault();" data-toggle="modal" data-target="#EditRatingModal">
                            <?php
                             $get_my_rating = $this->crud_model->get_user_specific_rating('manuscript', $my_manuscript['manuscript_id']);
                             for($i = 1; $i < 6; $i++):?>
                             <?php if ($i <= $get_my_rating['rating']): ?>
                                <i class="fas fa-star filled"></i>
                            <?php else: ?>
                                <i class="fas fa-star"></i>
                             <?php endif; ?>
                            <?php endfor; ?>
                            <p class="your-rating-text" id="<?php echo $my_manuscript['manuscript_id']; ?>" onclick="getManuscriptDetailsForRatingModal(this.id)">
                                <span class="your"><?php echo get_phrase('your'); ?></span>
                                <span class="edit"><?php echo get_phrase('edit'); ?></span>
                                <?php echo get_phrase('rating'); ?>
                            </p>
                        </div>
                    </div>
                    <div class="row" style="padding: 5px;">
                        <div class="col-md-6" style="padding-right: 2%; padding-left: 2%; text-align: center;">
                            <a href="<?php echo site_url('home/manuscript/'.slugify($manuscript_details['title']).'/'.$my_manuscript['manuscript_id']); ?>" class="btn" style="display: inline-block; width: auto; min-width: 120px; text-align: center; margin: 0 1% !important;"><?php echo get_phrase('Abstract'); ?></a>
                        </div>
                        <div class="col-md-6" style="padding-right: 2%; padding-left: 2%; text-align: center;">
                             <a href="<?php echo site_url('home/lesson/'.slugify($manuscript_details['title']).'/'.$my_manuscript['manuscript_id']); ?>" class="btn" style="display: inline-block; width: auto; min-width: 120px; text-align: center; margin: 0 1% !important;" target="_blank"><?php echo get_phrase('full_text'); ?></a>
                        </div>
                    </div>
                </div>
            </div>
       
    <?php endforeach; ?>
</div>

    </div>
</section>


<script type="text/javascript">
function getManuscriptsByCategoryId(category_id) {
    $.ajax({
        type : 'POST',
        url : '<?php echo site_url('home/my_manuscripts_by_category'); ?>',
        data : {category_id : category_id},
        success : function(response){
            $('#my_manuscripts_area').html(response);
        }
    });
}

function getManuscriptsBySearchString(search_string) {
    $.ajax({
        type : 'POST',
        url : '<?php echo site_url('home/my_manuscripts_by_search_string'); ?>',
        data : {search_string : search_string},
        success : function(response){
            $('#my_manuscripts_area').html(response);
        }
    });
}

function getManuscriptDetailsForRatingModal(manuscript_id) {
    $.ajax({
        type : 'POST',
        url : '<?php echo site_url('home/get_manuscript_details'); ?>',
        data : {manuscript_id : manuscript_id},
        success : function(response){
            $('#manuscript_title_1').append(response);
            $('#manuscript_title_2').append(response);
            $('#manuscript_thumbnail_1').attr('src', "<?php echo base_url().'uploads/thumbnails/manuscript_thumbnails/';?>"+manuscript_id+".jpg");
            $('#manuscript_thumbnail_2').attr('src', "<?php echo base_url().'uploads/thumbnails/manuscript_thumbnails/';?>"+manuscript_id+".jpg");
            $('#manuscript_id_for_rating').val(manuscript_id);
            // $('#instructor_details').text(manuscript_id);
            console.log(response);
        }
    });
}
</script>
