<?php
if (sizeof($my_manuscripts) > 0):
  foreach ($my_manuscripts as $my_manuscript):
          $researcher_details = $this->user_model->get_all_user($my_manuscript['user_id'])->row_array();?>
      <div class="col-lg-3">
          <div class="manuscript-box-wrap">
                <div class="manuscript-box">
                    <div class="manuscript-image">
                        <img src="<?php echo $this->crud_model->get_manuscript_thumbnail_url($my_manuscript['id']); ?>" alt="" class="img-fluid">
                        <div class="researcher-img-hover">
                            <a href="<?php echo site_url('home/researcher_page/'.$researcher_details['id']); ?>"><img src="<?php echo $this->user_model->get_user_image_url($researcher_details['id']);?>" alt=""></a>
                            <span>
                                <?php
                                    $lessons = $this->crud_model->get_lessons('manuscript', $my_manuscript['id'])->num_rows();
                                    echo $lessons.' '.get_phrase('lessons');
                                ?>
                            </span>
                            <span>
                                <?php
                                    echo $this->crud_model->get_total_duration_of_lesson_by_manuscript_id($my_manuscript['id']);
                                ?>
                            </span>
                        </div>
                        <div class="wishlist-add wishlisted">
                            <button type="button" data-toggle="tooltip" data-placement="left" title="" style="cursor : auto;" onclick="handleWishList(this)" id = "<?php echo $my_manuscript['id']; ?>">
                                <i class="fas fa-heart"></i>
                            </button>
                        </div>
                    </div>
                    <div class="manuscript-details">
                        <h5 class="title"><?php echo $my_manuscript['title']; ?></h5>
                        <p class="researchers"><?php echo $researcher_details['first_name'].' '.$researcher_details['last_name']; ?></p>
                        <!-- <div class="rating">
                            <i class="fas fa-star filled"></i>
                            <i class="fas fa-star filled"></i>
                            <i class="fas fa-star filled"></i>
                            <i class="fas fa-star half-filled"></i>
                            <i class="fas fa-star"></i>
                            <p class="d-inline-block rating-number">4.5<span>(23,990)</span></p>
                        </div> -->
                        <?php if ($my_manuscript['discount_flag'] == 1): ?>
                            <p class="price text-right"><small><?php echo currency($my_manuscript['price']); ?></small><?php echo currency($my_manuscript['discounted_price']); ?></p>
                        <?php else: ?>
                            <p class="price text-right"><?php echo currency($my_manuscript['price']); ?></p>
                        <?php endif; ?>
                    </div>
                </div>
          </div>
      </div>
  <?php endforeach; ?>
<?php endif; ?>
