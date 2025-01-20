<ul>
    <?php foreach ($manuscripts as $manuscript):
        $researcher_details = $this->user_model->get_all_user($manuscript['user_id'])->row_array(); ?>
        <li>
            <div class="manuscript-box-2">
                <div class="manuscript-image">
                    <a href="<?php echo site_url('home/manuscript/' . slugify($manuscript['title']) . '/' . $manuscript['id']); ?>">
                        <img src="<?php echo $this->crud_model->get_manuscript_thumbnail_url($manuscript['id']); ?>" alt="<?php echo $manuscript['title']; ?>" class="img-fluid">
                    </a>
                </div>
                <div class="manuscript-details">
                    <a href="<?php echo site_url('home/manuscript/' . slugify($manuscript['title']) . '/' . $manuscript['id']); ?>" class="manuscript-title">
                        <?php echo $manuscript['title']; ?>
                    </a>
                        <span class="authors">
                            Authors: <?php echo implode(', ', json_decode($manuscript['authors'], true)); ?>
                        </span> <br>
                    <span class="last-updated-date">
                    <?php echo get_phrase('date_accomplished_:'); ?>
                    <?php
                    // Ensure date_accomplished is in the right format before formatting
                    if (!empty($manuscript['date_accomplished'])) {
                        echo date('d-M-Y', strtotime($manuscript['date_accomplished']));
                    } else {
                        echo get_phrase('no_date_available'); // Fallback message if no date is available
                    }
                    ?>
                </span>

                    <div class="manuscript-subtitle">
                        <?php echo $manuscript['short_description']; ?>
                    </div>
                    <div class="manuscript-meta">
                        <span class="created-by">
                        <?php echo $researcher_details['first_name']; ?>
                        </span> <br>
                        <span class="company-name">
                            <i class="fas fa-building"></i> <?php echo $manuscript['company_name']; ?>
                        </span>
                    </div>
                </div>
                <div class="manuscript-price-rating">
                    <div class="manuscript-price">
                        <?php if ($manuscript['is_free_manuscript'] == 1): ?>
                            <span class="current-price"><?php echo get_phrase('Free'); ?></span>
                        <?php else: ?>
                            <?php if ($manuscript['discount_flag'] == 1): ?>
                                <span class="current-price"><?php echo currency($manuscript['discounted_price']); ?></span>
                                <span class="original-price"><?php echo currency($manuscript['price']); ?></span>
                            <?php else: ?>
                                <span class="current-price"><?php echo currency($manuscript['price']); ?></span>
                            <?php endif; ?>
                        <?php endif; ?>
                    </div>
                    <div class="rating">
                        <?php
                        $total_rating = $this->crud_model->get_ratings('manuscript', $manuscript['id'], true)->row()->rating;
                        $number_of_ratings = $this->crud_model->get_ratings('manuscript', $manuscript['id'])->num_rows();
                        $average_ceil_rating = $number_of_ratings > 0 ? ceil($total_rating / $number_of_ratings) : 0;

                        for ($i = 1; $i <= 5; $i++): ?>
                            <?php if ($i <= $average_ceil_rating): ?>
                                <i class="fas fa-star filled" style="color: #f5c85b;"></i>
                            <?php else: ?>
                                <i class="fas fa-star"></i>
                            <?php endif; ?>
                        <?php endfor; ?>
                        <span class="average-rating"><?php echo $average_ceil_rating; ?> Stars</span>
                    </div>
                    <div class="rating-number">
                        <?php echo $number_of_ratings . ' ' . get_phrase('ratings'); ?>
                    </div>
                </div>
            </div>
        </li>
    <?php endforeach; ?>
</ul>
