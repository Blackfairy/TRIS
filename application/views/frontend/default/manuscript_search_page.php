<section class="category-header-area">
    <div class="container-lg">
        <div class="row">
            <div class="col">
                <nav>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?php echo site_url('home'); ?>"><i class="fas fa-home"></i></a></li>
                        <li class="breadcrumb-item active">
                            <?php echo get_phrase('search_results'); ?>
                        </li>
                    </ol>
                </nav>
                <h1 class="category-name">
                    <?php echo get_phrase('search_results_for').' "'.$search_string.'"'; ?>
                </h1>
            </div>
        </div>
    </div>
</section>
<br>
<section class="category-manuscript-list-area">
    <div class="container">
        <div class="row">
            <div class="col">
                <div class="category-manuscript-list">
                    <ul>
                        <?php
                        foreach($manuscripts->result_array() as $manuscript):
                        $researcher_details = $this->user_model->get_all_user($manuscript['user_id'])->row_array();?>
                        <li>
                            <div class="manuscript-box-2">
                                <div class="manuscript-image">
                                    <a href="<?php echo site_url('home/manuscript/'.slugify($manuscript['title']).'/'.$manuscript['id']) ?>">
                                        <img src="<?php echo $this->crud_model->get_manuscript_thumbnail_url($manuscript['id']); ?>" alt="" class="img-fluid">
                                    </a>
                                </div>
                                <div class="manuscript-details">
                                    <a href="<?php echo site_url('home/manuscript/'.slugify($manuscript['title']).'/'.$manuscript['id']) ?>" class="manuscript-title"><?php echo $manuscript['title']; ?></a>
                                    <a href="<?php echo site_url('home/researcher_page/'.$researcher_details['id']) ?>" class="manuscript-researcher">
                                        <span class="researcher-name"><?php echo $researcher_details['first_name'].' '.$researcher_details['last_name']; ?></span> -
                                    </a>
                                    <div class="manuscript-subtitle">
                                        <?php echo $manuscript['short_description']; ?>
                                    </div>
                                    <div class="manuscript-meta">
                                        <span class=""><i class="fas fa-play-circle"></i>
                                            <?php
                                            $number_of_lessons = $this->crud_model->get_lessons('manuscript', $manuscript['id'])->num_rows();
                                            echo $number_of_lessons.' '.get_phrase('lessons');
                                            ?>
                                        </span>
                                        <span class=""><i class="far fa-clock"></i>
                                            <?php echo $this->crud_model->get_total_duration_of_lesson_by_manuscript_id($manuscript['id']); ?>
                                        </span>
                                        <span class=""><i class="fas fa-closed-captioning"></i><?php echo ucfirst($manuscript['language']); ?></span>
                                        <span class=""><i class="fa fa-level-up"></i><?php echo ucfirst($manuscript['level']); ?></span>
                                    </div>
                                </div>
                                <div class="manuscript-price-rating">
                                    <?php if ($manuscript['is_free_manuscript'] == 1): ?>
                                      <span class="current-price"><?php echo get_phrase('free'); ?></span>
                                    <?php else: ?>
                                      <div class="manuscript-price">
                                          <?php if($manuscript['discount_flag'] == 1): ?>
                                              <span class="current-price"><?php echo currency($manuscript['discounted_price']); ?></span>
                                              <span class="original-price"><?php echo currency($manuscript['price']); ?></span>
                                          <?php else: ?>
                                              <span class="current-price"><?php echo currency($manuscript['price']); ?></span>
                                          <?php endif; ?>
                                      </div>
                                    <?php endif; ?>
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
                                <div class="rating-number">
                                    <?php echo $this->crud_model->get_ratings('manuscript', $manuscript['id'])->num_rows().' '.get_phrase('ratings'); ?>
                                </div>
                            </div>
                        </div>
                    </li>
                <?php endforeach; ?>
                <?php if ($manuscripts->num_rows() == 0): ?>
                    <?php echo get_phrase('no_result_found'); ?>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</div>
</div>
</section>
