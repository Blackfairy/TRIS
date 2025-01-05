<div class="row">
  <?php foreach($manuscripts as $manuscript):
   $researcher_details = $this->user_model->get_all_user($manuscript['user_id'])->row_array();?>
   <div class="col-md-4 col-lg-4">
     <div class="manuscript-box-wrap">
         <a href="<?php echo site_url('home/manuscript/'.slugify($manuscript['title']).'/'.$manuscript['id']); ?>">
             <div class="manuscript-box">
                 <div class="manuscript-image">
                     <img src="<?php echo $this->crud_model->get_manuscript_thumbnail_url($manuscript['id']); ?>" alt="" class="img-fluid">
                 </div>
                 <div class="manuscript-details">
                     <h5 class="title"><?php echo $manuscript['title']; ?></h5>
                     <p class="researchers">
                     <span class="authors">
                            Authors: <?php echo implode(', ', json_decode($manuscript['authors'], true)); ?>
                    </span>

                     </p>
                     <span class="last-updated-date">
                        <?php echo get_phrase('date_published_:'); ?>
                        <?php
                        // Ensure date_published is in the right format before formatting
                        if (!empty($manuscript_details['date_published'])) {
                            echo date('d-M-Y', strtotime($manuscript_details['date_published']));
                        } else {
                            echo get_phrase('no_date_available'); // Fallback message if no date is available
                        }
                        ?>
                    </span>
                     <div class="rating">
                         <?php
                         $total_rating =  $this->crud_model->get_ratings('manuscript', $manuscript['id'], true)->row()->rating;
                         $number_of_ratings = $this->crud_model->get_ratings('manuscript', $manuscript['id'])->num_rows();
                         if ($number_of_ratings > 0) {
                             $average_ceil_rating = ceil($total_rating / $number_of_ratings);
                         }else {
                             $average_ceil_rating = 0;
                         }

                         for($i = 1; $i < 6; $i++):?>
                         <?php if ($i <= $average_ceil_rating): ?>
                             <i class="fas fa-star filled"></i>
                         <?php else: ?>
                             <i class="fas fa-star"></i>
                         <?php endif; ?>
                     <?php endfor; ?>
                     <span class="d-inline-block average-rating"><?php echo $average_ceil_rating; ?></span>
                 </div>
                 <?php if ($manuscript['is_free_manuscript'] == 1): ?>
                     <p class="price text-right"><?php echo get_phrase('free'); ?></p>
                 <?php else: ?>
                     <?php if ($manuscript['discount_flag'] == 1): ?>
                         <p class="price text-right"><small><?php echo currency($manuscript['price']); ?></small><?php echo currency($manuscript['discounted_price']); ?></p>
                     <?php else: ?>
                         <p class="price text-right"><?php echo currency($manuscript['price']); ?></p>
                     <?php endif; ?>
                 <?php endif; ?>
             </div>
         </div>
     </a>
     </div>
   </div>
 <?php endforeach; ?>
</div>
