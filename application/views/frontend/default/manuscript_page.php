<?php
$manuscript_details = $this->crud_model->get_manuscript_by_id($manuscript_id)->row_array();
$researcher_details = $this->user_model->get_all_user($manuscript_details['user_id'])->row_array();
?>
<section class="manuscript-header-area">
  <div class="container">
    <div class="row align-items-end">
      <div class="col-lg-8">
        <div class="manuscript-header-wrap">
          <h1 class="title"><?php echo $manuscript_details['title']; ?></h1>
          <p class="subtitle"><?php echo $manuscript_details['short_description']; ?></p>
          <div class="rating-row">
            
            <?php
            $total_rating =  $this->crud_model->get_ratings('manuscript', $manuscript_details['id'], true)->row()->rating;
            $number_of_ratings = $this->crud_model->get_ratings('manuscript', $manuscript_details['id'])->num_rows();
            if ($number_of_ratings > 0) {
              $average_ceil_rating = ceil($total_rating / $number_of_ratings);
            }else {
              $average_ceil_rating = 0;
            }

            for($i = 1; $i < 6; $i++):?>
            <?php if ($i <= $average_ceil_rating): ?>
              <i class="fas fa-star filled" style="color: #f5c85b;"></i>
            <?php else: ?>
              <i class="fas fa-star"></i>
            <?php endif; ?>
          <?php endfor; ?>
          <span class="d-inline-block average-rating"><?php echo $average_ceil_rating; ?></span><span>(<?php echo $number_of_ratings.' '.get_phrase('ratings'); ?>)</span>
          <span class="enrolled-num">
            <?php
            $number_of_enrolments = $this->crud_model->enrol_history($manuscript_details['id'])->num_rows();
            echo $number_of_enrolments.' '.get_phrase('students_viewed');
            ?>
          </span>
        </div>
        <div class="created-row">
            <span class="created-by">
              <?php echo get_phrase('authors_:'); ?>
              <?php
              $authors = json_decode($manuscript_details['authors'], true);
              echo implode(', ', array_map('html_escape', $authors));
              ?>
            </span>
        </div>
        <div class="created-row">
            <span class="created-by">
              
            <?php echo 'Submitted by: ' . $researcher_details['first_name']; ?>
            </span>
        </div>    
        <div class="created-row">
            <span class="last-updated-date">
                <?php echo get_phrase('date_accomplished_:'); ?>
                <?php
                // Ensure date_accomplished is in the right format before formatting
                if (!empty($manuscript_details['date_accomplished'])) {
                    echo date('d-M-Y', strtotime($manuscript_details['date_accomplished']));
                } else {
                    echo get_phrase('no_date_available'); // Fallback message if no date is available
                }
                ?>
            </span>
        </div>
      </div>
    </div>
    <div class="col-lg-4">
    </div>
  </div>
</div>
</section>


<section class="manuscript-content-area" style="color:white;">
  <div class="container">
    <div class="row">
      <div class="col-lg-8">
        <br>
    <div class="description-box view-more-parent">
      <div class="view-more" onclick="viewMore(this,'hide')">+ <?php echo get_phrase('view_more'); ?></div>
      <div class="description-title"><?php echo get_phrase('abstract'); ?></div>
      <div class="description-content-wrap">
        <div class="description-content">
          <?php echo $manuscript_details['description']; ?>
        </div>
      </div>
    </div>


    <div class="compare-box view-more-parent">
      <div class="view-more" onclick="viewMore(this)">+ <?php echo get_phrase('view_more'); ?></div>
      <div class="compare-title"><?php echo get_phrase('other_related_manuscripts'); ?></div>
      <div class="compare-manuscripts-wrap">
        <?php
        $other_realted_manuscripts = $this->crud_model->get_manuscripts($manuscript_details['category_id'], $manuscript_details['sub_category_id'])->result_array();
        foreach ($other_realted_manuscripts as $other_realted_manuscript):
          if($other_realted_manuscript['id'] != $manuscript_details['id'] && $other_realted_manuscript['status'] == 'active'): ?>
          <div class="manuscript-comparism-item-container this-manuscript">
            <div class="manuscript-comparism-item clearfix" >
             
              <div class="item-title float-left">
                <div class="title"style="color:white;"><a href="<?php echo site_url('home/manuscript/'.slugify($other_realted_manuscript['title']).'/'.$other_realted_manuscript['id']); ?>"><?php echo $other_realted_manuscript['title']; ?></a></div>
                <?php if ($other_realted_manuscript['last_modified'] > 0): ?>
                  <div class="updated-time" style="color:white;"><?php echo get_phrase('updated').' '.date('D, d-M-Y', $other_realted_manuscript['last_modified']); ?></div>
                <?php else: ?>
                  <div class="updated-time" style="color:white;"><?php echo get_phrase('updated').' '.date('D, d-M-Y', $other_realted_manuscript['date_added']); ?></div>
                <?php endif; ?>
              </div>
              <div class="item-details float-left" style="color:white;">
                <span class="item-rating">
                  <i class="fas fa-star"></i>
                  <?php
                  $total_rating =  $this->crud_model->get_ratings('manuscript', $other_realted_manuscript['id'], true)->row()->rating;
                  $number_of_ratings = $this->crud_model->get_ratings('manuscript', $other_realted_manuscript['id'])->num_rows();
                  if ($number_of_ratings > 0) {
                    $average_ceil_rating = ceil($total_rating / $number_of_ratings);
                  }else {
                    $average_ceil_rating = 0;
                  }
                  ?>
                  <span class="d-inline-block average-rating"><?php echo $average_ceil_rating; ?></span>
                </span>
                <span class="enrolled-student" style="color:white;">
                  <i class="far fa-user"></i>
                  <?php echo $this->crud_model->enrol_history($other_realted_manuscript['id'])->num_rows(); ?>
                </span>
                <?php if ($other_realted_manuscript['is_free_manuscript'] == 1): ?>
                  <span class="item-price">
                    <span class="current-price"style="color:white;"><?php echo get_phrase('free'); ?></span>
                  </span>
                <?php else: ?>
                  <?php if ($other_realted_manuscript['discount_flag'] == 1): ?>
                    <span class="item-price">
                      <span class="original-price"><?php echo currency($other_realted_manuscript['price']); ?></span>
                      <span class="current-price"><?php echo currency($other_realted_manuscript['discounted_price']); ?></span>
                    </span>
                  <?php else: ?>
                    <span class="item-price">
                      <span class="current-price"><?php echo currency($other_realted_manuscript['price']); ?></span>
                    </span>
                  <?php endif; ?>
                <?php endif; ?>
              </div>
            </div>
          </div>
        <?php endif; ?>
      <?php endforeach; ?>
    </div>
  </div>
  <div class="about-researcher-box">
  <div class="about-researcher-title">
    <?php echo get_phrase('About the Author'); ?>
  </div>
  <div class="row">
    <div class="col-lg-4">
      <div class="author-list">
        <h6><?php echo get_phrase('Authors'); ?>:</h6>
        <ul>
          <?php 
          $authors = json_decode($manuscript_details['authors']);
          foreach ($authors as $author) {
            echo '<li>' . $author . '</li>';
          }
          ?>
        </ul>
      </div>
    </div>
  </div>
</div>

<div class="about-researcher-box">
  <div class="about-researcher-title">
    <?php echo get_phrase('Advisers'); ?>
  </div>
  <div class="row">
    <div class="col-lg-4">
      <div class="adviser-list">
        <h6><?php echo get_phrase('Advisers'); ?>:</h6>
        <ul>
          <?php 
          $advisers = json_decode($manuscript_details['outcomes']);
          if (!empty($advisers)) {
            foreach ($advisers as $index => $adviser) {
              $title = '';
              if ($index == 0) {
                $title = get_phrase('Course Adviser');
              } elseif ($index == 1) {
                $title = get_phrase('Technical Adviser');
              }
              echo '<li>';
              if ($title != '') {
                echo '<strong>' . $title . ':</strong> ';
              }
              echo $adviser . '</li>';
            }
          } else {
            echo '<li>' . get_phrase('No advisers listed.') . '</li>';
          }
          ?>
        </ul>
      </div>
    </div>
  </div>
</div>


<div class="about-researcher-box">
  <div class="about-researcher-title">
    <?php echo get_phrase('About the Contributor'); ?>
  </div>
  <div class="row">
    <div class="col-lg-4">
      <div class="about-researcher-image">
        <img src="<?php echo $this->user_model->get_user_image_url($researcher_details['id']); ?>" alt="" class="img-fluid">
        <ul>
          <li><i class="fas fa-comment"></i><b>
            <?php echo $this->crud_model->get_researcher_wise_manuscript_ratings($researcher_details['id'], 'manuscript')->num_rows(); ?>
          </b> <?php echo get_phrase('reviews'); ?></li>
          <li><i class="fas fa-user"></i><b>
            <?php
            $manuscript_ids = $this->crud_model->get_researcher_wise_manuscripts($researcher_details['id'], 'simple_array');
            $this->db->select('user_id');
            $this->db->distinct();
            $this->db->where_in('manuscript_id', $manuscript_ids);
            echo $this->db->get('enrol')->num_rows();
            ?>
          </b> <?php echo get_phrase('students_accessed'); ?></li>
          <li><i class="fas fa-play-circle"></i><b>
            <?php echo $this->crud_model->get_researcher_wise_manuscripts($researcher_details['id'])->num_rows(); ?>
          </b> <?php echo get_phrase('manuscripts'); ?></li>
        </ul>
      </div>
    </div>
    <div class="col-lg-8">
      <div class="about-researcher-details view-more-parent">
        <div class="view-more" onclick="viewMore(this)">+ <?php echo get_phrase('view_more'); ?></div>
        <div class="researcher-name" style="color: white;">
          <a href="<?php echo site_url('home/researcher_page/'.$manuscript_details['user_id']); ?>"></a>
        </div>
        <div class="researcher-title">
          <?php echo $researcher_details['first_name']; ?>
        </div>
        <div class="researcher-bio">
          <?php echo $researcher_details['biography']; ?>
        </div>
      </div>
    </div>
  </div>
</div>


  <div class="student-feedback-box">
    <div class="student-feedback-title">
      <?php echo get_phrase('student_feedback'); ?>
    </div>
    <div class="row">
      <div class="col-lg-3">
        <div class="average-rating">
          <div class="num">
            <?php
            $total_rating =  $this->crud_model->get_ratings('manuscript', $manuscript_details['id'], true)->row()->rating;
            $number_of_ratings = $this->crud_model->get_ratings('manuscript', $manuscript_details['id'])->num_rows();
            if ($number_of_ratings > 0) {
              $average_ceil_rating = ceil($total_rating / $number_of_ratings);
            }else {
              $average_ceil_rating = 0;
            }
            echo $average_ceil_rating;
            ?>
          </div>
          <div class="rating">
            <?php
            for($i = 1; $i < 6; $i++):?>
            <?php if ($i <= $average_ceil_rating): ?>
              <i class="fas fa-star filled" style="color: #f5c85b;"></i>
            <?php else: ?>
              <i class="fas fa-star" style="color: #abb0bb;"></i>
            <?php endif; ?>
          <?php endfor; ?>
        </div>
        <div class="title"><?php echo get_phrase('average_rating'); ?></div>
      </div>
    </div>
    <div class="col-lg-9">
      <div class="individual-rating">
        <ul>
          <?php for($i = 1; $i <= 5; $i++): ?>
            <li>
              <div class="progress">
                <div class="progress-bar" style="width: <?php echo $this->crud_model->get_percentage_of_specific_rating($i, 'manuscript', $manuscript_id); ?>%"></div>
              </div>
              <div>
                <span class="rating">
                  <?php for($j = 1; $j <= (5-$i); $j++): ?>
                    <i class="fas fa-star"></i>
                  <?php endfor; ?>
                  <?php for($j = 1; $j <= $i; $j++): ?>
                    <i class="fas fa-star filled"></i>
                  <?php endfor; ?>

                </span>
                <span><?php echo $this->crud_model->get_percentage_of_specific_rating($i, 'manuscript', $manuscript_id); ?>%</span>
              </div>
            </li>
          <?php endfor; ?>
        </ul>
      </div>
    </div>
  </div>
  <div class="reviews">
    <div class="reviews-title"><?php echo get_phrase('reviews'); ?></div>
    <ul>
      <?php
      $ratings = $this->crud_model->get_ratings('manuscript', $manuscript_id)->result_array();
      foreach($ratings as $rating):
        ?>
        <li>
          <div class="row">
            <div class="col-lg-4">
              <div class="reviewer-details clearfix">
                <div class="reviewer-img float-left">
                  <img src="<?php echo $this->user_model->get_user_image_url($rating['user_id']); ?>" alt="">
                </div>
                <div class="review-time">
                  <div class="time">
                    <?php echo date('D, d-M-Y', $rating['date_added']); ?>
                  </div>
                  <div class="reviewer-name">
                    <?php
                    $user_details = $this->user_model->get_user($rating['user_id'])->row_array();
                    echo $user_details['first_name'].' '.$user_details['last_name'];
                    ?>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-lg-8">
              <div class="review-details">
                <div class="rating">
                  <?php
                  for($i = 1; $i < 6; $i++):?>
                  <?php if ($i <= $rating['rating']): ?>
                    <i class="fas fa-star filled" style="color: #f5c85b;"></i>
                  <?php else: ?>
                    <i class="fas fa-star" style="color: #abb0bb;"></i>
                  <?php endif; ?>
                <?php endfor; ?>
              </div>
              <div class="review-text">
                <?php echo $rating['review']; ?>
              </div>
            </div>
          </div>
        </div>
      </li>
    <?php endforeach; ?>
  </ul>
</div>
</div>
</div>
<div class="col-lg-4">
  <div class="manuscript-sidebar natural">
    <?php if ($manuscript_details['video_url'] != ""): ?>
      <div class="preview-video-box">
        <a data-toggle="modal" data-target="#ManuscriptPreviewModal">
          <img src="<?php echo $this->crud_model->get_manuscript_thumbnail_url($manuscript_details['id']); ?>" alt="" class="img-fluid">
          <span class="preview-text"><?php echo get_phrase('preview_this_manuscript'); ?></span>
          <span class="play-btn"></span>
        </a>
      </div>
    <?php endif; ?>
    <div class="manuscript-sidebar-text-box">
      <div class="price">
        <?php if ($manuscript_details['is_free_manuscript'] == 1): ?>
          <span class = "current-price"><span class="current-price"><?php echo get_phrase('free'); ?></span></span>
        <?php else: ?>
          <?php if ($manuscript_details['discount_flag'] == 1): ?>
            <span class = "current-price"><span class="current-price"><?php echo currency($manuscript_details['discounted_price']); ?></span></span>
            <span class="original-price"><?php echo currency($manuscript_details['price']) ?></span>
            <input type="hidden" id = "total_price_of_checking_out" value="<?php echo currency($manuscript_details['discounted_price']); ?>">
          <?php else: ?>
            <span class = "current-price"><span class="current-price"><?php echo currency($manuscript_details['price']); ?></span></span>
            <input type="hidden" id = "total_price_of_checking_out" value="<?php echo currency($manuscript_details['price']); ?>">
          <?php endif; ?>
        <?php endif; ?>
      </div>

      <?php if(is_purchased($manuscript_details['id'])) :?>
        <div class="already_purchased">
          <a href="<?php echo site_url('home/my_manuscripts'); ?>"><?php echo get_phrase('already_purchased'); ?></a>
        </div>
      <?php else: ?>
        <?php if ($manuscript_details['is_free_manuscript'] == 1): ?>
          <div class="buy-btns">
            <?php if ($this->session->userdata('user_login') != 1): ?>
              <a href = "#" class="btn btn-buy-now" onclick="handleEnrolledButton()"><?php echo get_phrase('access_now'); ?></a>
            <?php else: ?>
              <a href = "<?php echo site_url('home/get_enrolled_to_free_manuscript/'.$manuscript_details['id']); ?>" class="btn btn-buy-now"><?php echo get_phrase('access_now'); ?></a>
            <?php endif; ?>
          </div>
        <?php else: ?>
          <div class="buy-btns">
            <a href = "<?php echo site_url('home/shopping_cart'); ?>" class="btn btn-buy-now" id = "manuscript_<?php echo $manuscript_details['id']; ?>" onclick="handleBuyNow(this)"><?php echo get_phrase('buy_now'); ?></a>
            <?php if (in_array($manuscript_details['id'], $this->session->userdata('cart_items'))): ?>
              <button class="btn btn-add-cart addedToCart" type="button" id = "<?php echo $manuscript_details['id']; ?>" onclick="handleCartItems(this)"><?php echo get_phrase('added_to_cart'); ?></button>
            <?php else: ?>
              <button class="btn btn-add-cart" type="button" id = "<?php echo $manuscript_details['id']; ?>" onclick="handleCartItems(this)"><?php echo get_phrase('add_to_cart'); ?></button>
            <?php endif; ?>
          </div>
        <?php endif; ?>
      <?php endif; ?>


      <div class="includes">
        <div class="title"><b><?php echo get_phrase('includes'); ?>:</b></div>
        <ul>
          <li><i class="far fa-file"></i><?php echo $this->crud_model->get_lessons('manuscript', $manuscript_details['id'])->num_rows().' '.get_phrase('view_full-text'); ?></li>
          <li><i class="far fa-compass"></i><?php echo get_phrase('lifetime_access'); ?></li>
        </ul>
      </div>
    </div>
  </div>
</div>
</div>
</div>
</section>

<!-- Modal -->
<?php if ($manuscript_details['video_url'] != ""):
  $provider = "";
  $video_details = array();
  if ($manuscript_details['manuscript_overview_provider'] == "html5") {
    $provider = 'html5';
  }else {
    $video_details = $this->video_model->getVideoDetails($manuscript_details['video_url']);
    $provider = $video_details['provider'];
  }
  ?>
  <div class="modal fade" id="ManuscriptPreviewModal" tabindex="-1" role="dialog" aria-hidden="true" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content manuscript-preview-modal">
        <div class="modal-header">
          <h5 class="modal-title"><span><?php echo get_phrase('manuscript_preview') ?>:</span><?php echo $manuscript_details['title']; ?></h5>
          <button type="button" class="close" data-dismiss="modal" onclick="pausePreview()">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="manuscript-preview-video-wrap">
            <div class="embed-responsive embed-responsive-16by9">
              <?php if (strtolower(strtolower($provider)) == 'youtube'): ?>
                <!------------- PLYR.IO ------------>
                <link rel="stylesheet" href="<?php echo base_url();?>assets/global/plyr/plyr.css">

                <div class="plyr__video-embed" id="player">
                  <iframe height="500" src="<?php echo $manuscript_details['video_url'];?>?origin=https://plyr.io&amp;iv_load_policy=3&amp;modestbranding=1&amp;playsinline=1&amp;showinfo=0&amp;rel=0&amp;enablejsapi=1" allowfullscreen allowtransparency allow="autoplay"></iframe>
                </div>

                <script src="<?php echo base_url();?>assets/global/plyr/plyr.js"></script>
                <script>const player = new Plyr('#player');</script>
                <!------------- PLYR.IO ------------>
              <?php elseif (strtolower($provider) == 'vimeo'): ?>
                <!------------- PLYR.IO ------------>
                <link rel="stylesheet" href="<?php echo base_url();?>assets/global/plyr/plyr.css">
                <div class="plyr__video-embed" id="player">
                  <iframe height="500" src="https://player.vimeo.com/video/<?php echo $video_details['video_id']; ?>?loop=false&amp;byline=false&amp;portrait=false&amp;title=false&amp;speed=true&amp;transparent=0&amp;gesture=media" allowfullscreen allowtransparency allow="autoplay"></iframe>
                </div>

                <script src="<?php echo base_url();?>assets/global/plyr/plyr.js"></script>
                <script>const player = new Plyr('#player');</script>
                <!------------- PLYR.IO ------------>
              <?php else :?>
                <!------------- PLYR.IO ------------>
                <link rel="stylesheet" href="<?php echo base_url();?>assets/global/plyr/plyr.css">
                <video poster="<?php echo $this->crud_model->get_manuscript_thumbnail_url($manuscript_details['id']);?>" id="player" playsinline controls>
                  <?php if (get_video_extension($manuscript_details['video_url']) == 'mp4'): ?>
                    <source src="<?php echo $manuscript_details['video_url']; ?>" type="video/mp4">
                    <?php elseif (get_video_extension($manuscript_details['video_url']) == 'webm'): ?>
                      <source src="<?php echo $manuscript_details['video_url']; ?>" type="video/webm">
                      <?php else: ?>
                        <h4><?php get_phrase('video_url_is_not_supported'); ?></h4>
                      <?php endif; ?>
                    </video>

                    <style media="screen">
                    .plyr__video-wrapper {
                      height: 450px;
                    }
                    </style>

                    <script src="<?php echo base_url();?>assets/global/plyr/plyr.js"></script>
                    <script>const player = new Plyr('#player');</script>
                    <!------------- PLYR.IO ------------>
                  <?php endif; ?>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    <?php endif; ?>
    <!-- Modal -->

    <style media="screen">
    .embed-responsive-16by9::before {
      padding-top : 0px;
    }
    </style>
    <script type="text/javascript">
    function handleCartItems(elem) {
      url1 = '<?php echo site_url('home/handleCartItems');?>';
      url2 = '<?php echo site_url('home/refreshWishList');?>';
      $.ajax({
        url: url1,
        type : 'POST',
        data : {manuscript_id : elem.id},
        success: function(response)
        {
          $('#cart_items').html(response);
          if ($(elem).hasClass('addedToCart')) {
            $(elem).removeClass('addedToCart')
            $(elem).text("<?php echo get_phrase('add_to_cart'); ?>");
          }else {
            $(elem).addClass('addedToCart')
            $(elem).text("<?php echo get_phrase('added_to_cart'); ?>");
          }
          $.ajax({
            url: url2,
            type : 'POST',
            success: function(response)
            {
              $('#wishlist_items').html(response);
            }
          });
        }
      });
    }

    function handleBuyNow(elem) {

      url1 = '<?php echo site_url('home/handleCartItemForBuyNowButton');?>';
      url2 = '<?php echo site_url('home/refreshWishList');?>';
      var explodedArray = elem.id.split("_");
      var manuscript_id = explodedArray[1];

      $.ajax({
        url: url1,
        type : 'POST',
        data : {manuscript_id : manuscript_id},
        success: function(response)
        {
          $('#cart_items').html(response);
          $.ajax({
            url: url2,
            type : 'POST',
            success: function(response)
            {
              $('#wishlist_items').html(response);
            }
          });
        }
      });
    }

    function handleEnrolledButton() {
      console.log('here');
      $.ajax({
        url: '<?php echo site_url('home/isLoggedIn');?>',
        success: function(response)
        {
          if (!response) {
            window.location.replace("<?php echo site_url('home/login'); ?>");
          }
        }
      });
    }

    function pausePreview() {
      player.pause();
    }
    </script>
