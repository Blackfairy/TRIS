<section class="home-banner-area">
<div class="container-lg" style="display: flex; justify-content: space-between; width: 100%; padding: 0;" id="main-container">
    <!-- Left Side Div (60%) -->
    <div class="left-side" style="width: 60%; margin-left: 0;" id="leftSide">
        <div class="row">
            <div class="col">
                <div class="home-banner-wrap">
                    <h2 style="text-shadow: 
                        -1px -1px 0 rgba(0, 0, 0, 0.7),  /* Top-left outline */
                        1px -1px 0 rgba(0, 0, 0, 0.7),   /* Top-right outline */
                        -1px 1px 0 rgba(0, 0, 0, 0.7),   /* Bottom-left outline */
                        1px 1px 0 rgba(0, 0, 0, 0.7),    /* Bottom-right outline */
                        5px 5px 20px rgba(0, 0, 0, 0.7) !important; /* Larger shadow */
                    ">
                        <?php echo get_frontend_settings('banner_title'); ?>
                    </h2>
                    <p style="text-shadow: 
                        -1px -1px 0 rgba(0, 0, 0, 0.7), 
                        1px -1px 0 rgba(0, 0, 0, 0.7), 
                        -1px 1px 0 rgba(0, 0, 0, 0.7), 
                        1px 1px 0 rgba(0, 0, 0, 0.7), 
                        5px 5px 20px rgba(0, 0, 0, 0.7) !important;">
                        <?php echo get_frontend_settings('banner_sub_title'); ?>
                    </p>

                    <form class="" action="<?php echo site_url('home/search'); ?>" method="get">
                        <div class="input-group">
                            <input type="text" class="form-control" name="query" placeholder="<?php echo get_phrase('what_do_you_want_to_find'); ?>?">
                            <div class="input-group-append">
                                <button class="btn" type="submit"><i class="fas fa-search"></i></button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Right Side Div (30%) -->
    <div class="right-side" style="width: 30%; margin-left: 0;" id="rightSide">
        <div class="row">
            <div class="col">
                <div class="right-content-wrap">
                    <!-- Content here -->
                </div>
            </div>
        </div>
    </div>
</div>


</div>

</section>
<section class="home-fact-area">
    <div class="container-lg">
        <div class="row">
            <?php $manuscripts = $this->crud_model->get_manuscripts(); ?>
            <div class="col-md-4 d-flex">
                <div class="home-fact-box mr-md-auto ml-auto mr-auto">
                    <i class="fas fa-bullseye float-left"></i>
                    <div class="text-box">
                        <h4><?php
                        $status_wise_manuscripts = $this->crud_model->get_status_wise_manuscripts();
                        $number_of_manuscripts = $status_wise_manuscripts['active']->num_rows();
                        echo $number_of_manuscripts.' '.get_phrase('online_manuscripts'); ?></h4>
                        <p><?php echo get_phrase('explore_a_variety_of_topics'); ?></p>
                    </div>
                </div>
            </div>

            <div class="col-md-4 d-flex">
                <div class="home-fact-box mr-md-auto ml-auto mr-auto">
                    <i class="fa fa-check float-left"></i>
                    <div class="text-box">
                        <h4><?php echo get_phrase('defended_researches'); ?></h4>
                        <p><?php echo get_phrase('find_related_manuscript_for_you'); ?></p>
                    </div>
                </div>
            </div>

            <div class="col-md-4 d-flex">
                <div class="home-fact-box mr-md-auto ml-auto mr-auto">
                    <i class="fa fa-clock float-left"></i>
                    <div class="text-box">
                        <h4><?php echo get_phrase('lifetime_access'); ?></h4>
                        <p><?php echo get_phrase('access_conveniently_on_your_schedule'); ?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="manuscript-carousel-area">
    <div class="container-lg">
        <div class="row">
            <div class="col">
                <h2 class="manuscript-carousel-title"><?php echo get_phrase('featured_manuscripts'); ?></h2>
                <div class="manuscript-carousel">
                    <?php $top_manuscripts = $this->crud_model->get_top_manuscripts()->result_array();
                    $cart_items = $this->session->userdata('cart_items');
                    foreach ($top_manuscripts as $top_manuscript):?>
                    <div class="manuscript-box-wrap">
                        <a href="<?php echo site_url('home/manuscript/'.slugify($top_manuscript['title']).'/'.$top_manuscript['id']); ?>" class="has-popover">
                            <div class="manuscript-box">
                                <!-- <div class="manuscript-badge position best-seller">Best seller</div> -->
                                <div class="manuscript-image">
                                    <img src="<?php echo $this->crud_model->get_manuscript_thumbnail_url($top_manuscript['id']); ?>" alt="" class="img-fluid">
                                </div>
                                <div class="manuscript-details">
                                    <h5 class="title"><?php echo $top_manuscript['title']; ?></h5>
                                    <p class="researchers"><?php echo $top_manuscript['short_description']; ?></p>
                                    <div class="rating">
                                        <?php
                                        $total_rating =  $this->crud_model->get_ratings('manuscript', $top_manuscript['id'], true)->row()->rating;
                                        $number_of_ratings = $this->crud_model->get_ratings('manuscript', $top_manuscript['id'])->num_rows();
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
                                <?php if ($top_manuscript['is_free_manuscript'] == 1): ?>
                                    <p class="price text-right"><?php echo get_phrase('free'); ?></p>
                                <?php else: ?>
                                    <?php if ($top_manuscript['discount_flag'] == 1): ?>
                                        <p class="price text-right"><small><?php echo currency($top_manuscript['price']); ?></small><?php echo currency($top_manuscript['discounted_price']); ?></p>
                                    <?php else: ?>
                                        <p class="price text-right"><?php echo currency($top_manuscript['price']); ?></p>
                                    <?php endif; ?>
                                <?php endif; ?>
                            </div>
                        </div>
                    </a>

                    <div class="webui-popover-content">
                        <div class="manuscript-popover-content">
                            <?php if ($top_manuscript['last_modified'] == ""): ?>
                                <div class="last-updated"><?php echo get_phrase('last_updated').' '.date('D, d-M-Y', $top_manuscript['date_added']); ?></div>
                            <?php else: ?>
                                <div class="last-updated"><?php echo get_phrase('last_updated').' '.date('D, d-M-Y', $top_manuscript['last_modified']); ?></div>
                            <?php endif; ?>

                            <div class="manuscript-title">
                                <a href="<?php echo site_url('home/manuscript/'.slugify($top_manuscript['title']).'/'.$top_manuscript['id']); ?>"><?php echo $top_manuscript['title']; ?></a>
                            </div>
                            <div class="manuscript-meta">
                                <span class="authors"><?php echo get_phrase('authors_:'); ?>
                                <?php
                                $authors = json_decode($top_manuscript['authors'], true);
                                echo implode(', ', array_map('html_escape', $authors));
                                ?></span> <br>
                                <span class="last-updated-date">
                                    <?php echo get_phrase('date_published_:'); ?>
                                    <?php
                                    // Ensure date_published is in the right format before formatting
                                    if (!empty($top_manuscript['date_published'])) {
                                        echo date('d-M-Y', strtotime($top_manuscript['date_published']));
                                    } else {
                                        echo get_phrase('no_date_available'); // Fallback message if no date is available
                                    }
                                    ?>
                                </span>
                                
                            </div>
                            <div class="manuscript-subtitle"><?php echo $top_manuscript['short_description']; ?></div>
                            <div class="what-will-learn">
                                <ul>
                                <?php 
                                $advisers = json_decode($top_manuscript['outcomes']);
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
                        <div class="popover-btns">
                            <?php if (is_purchased($top_manuscript['id'])): ?>
                                <div class="purchased">
                                    <a href="<?php echo site_url('home/my_manuscripts'); ?>"><?php echo get_phrase('already_purchased'); ?></a>
                                </div>
                            <?php else: ?>
                                <?php if ($top_manuscript['is_free_manuscript'] == 1):
                                    if($this->session->userdata('user_login') != 1) {
                                        $url = "#";
                                    }else {
                                        $url = site_url('home/get_enrolled_to_free_manuscript/'.$top_manuscript['id']);
                                    }?>
                                    <a href="<?php echo $url; ?>" class="btn add-to-cart-btn big-cart-button" onclick="handleEnrolledButton()"><?php echo get_phrase('Access_manuscript'); ?></a>
                                <?php else: ?>
                                    <button type="button" class="btn add-to-cart-btn <?php if(in_array($top_manuscript['id'], $cart_items)) echo 'addedToCart'; ?> big-cart-button-<?php echo $top_manuscript['id'];?>" id = "<?php echo $top_manuscript['id']; ?>" onclick="handleCartItems(this)">
                                        <?php
                                        if(in_array($top_manuscript['id'], $cart_items))
                                        echo get_phrase('added_to_cart');
                                        else
                                        echo get_phrase('add_to_cart');
                                        ?>
                                    </button>
                                    <button type="button" class="wishlist-btn <?php if($this->crud_model->is_added_to_wishlist($top_manuscript['id'])) echo 'active'; ?>" title="Add to wishlist" onclick="handleWishList(this)" id = "<?php echo $top_manuscript['id']; ?>"><i class="fas fa-heart"></i></button>
                                <?php endif; ?>
                            <?php endif; ?>

                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>
</div>
</div>
</section>

<section class="manuscript-carousel-area">
    <div class="container-lg">
        <div class="row">
            <div class="col">
                <h2 class="manuscript-carousel-title"><?php echo get_phrase('top').' 10 '.get_phrase('latest_manuscripts'); ?></h2>
                <div class="manuscript-carousel">
                    <?php
                    $latest_manuscripts = $this->crud_model->get_latest_10_manuscript();
                    foreach ($latest_manuscripts as $latest_manuscript):?>
                    <div class="manuscript-box-wrap">
                        <a href="<?php echo site_url('home/manuscript/'.slugify($latest_manuscript['title']).'/'.$latest_manuscript['id']); ?>">
                            <div class="manuscript-box">
                                <div class="manuscript-image">
                                    <img src="<?php echo $this->crud_model->get_manuscript_thumbnail_url($latest_manuscript['id']); ?>" alt="" class="img-fluid">
                                </div>
                                <div class="manuscript-details">
                                    <h5 class="title"><?php echo $latest_manuscript['title']; ?></h5>
                                    <p class="researchers">
                                        <?php
                                        $researcher_details = $this->user_model->get_all_user($latest_manuscript['user_id'])->row_array();
                                        echo $researcher_details['first_name'].' '.$researcher_details['last_name']; ?>
                                    </p>
                                    <div class="rating">
                                        <?php
                                        $total_rating =  $this->crud_model->get_ratings('manuscript', $latest_manuscript['id'], true)->row()->rating;
                                        $number_of_ratings = $this->crud_model->get_ratings('manuscript', $latest_manuscript['id'])->num_rows();
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
                                <?php if ($latest_manuscript['is_free_manuscript'] == 1): ?>
                                    <p class="price text-right"><?php echo get_phrase('free'); ?></p>
                                <?php else: ?>
                                    <?php if ($latest_manuscript['discount_flag'] == 1): ?>
                                        <p class="price text-right"><small><?php echo currency($latest_manuscript['price']); ?></small><?php echo currency($latest_manuscript['discounted_price']); ?></p>
                                    <?php else: ?>
                                        <p class="price text-right"><?php echo currency($latest_manuscript['price']); ?></p>
                                    <?php endif; ?>
                                <?php endif; ?>
                            </div>
                        </div>
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>
</div>
</section>

<script type="text/javascript">
function handleWishList(elem) {

    $.ajax({
        url: '<?php echo site_url('home/handleWishList');?>',
        type : 'POST',
        data : {manuscript_id : elem.id},
        success: function(response)
        {
            if (!response) {
                window.location.replace("<?php echo site_url('home/login'); ?>");
            }else {
                if ($(elem).hasClass('active')) {
                    $(elem).removeClass('active')
                }else {
                    $(elem).addClass('active')
                }
                $('#wishlist_items').html(response);
            }
        }
    });
}

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
                $('.big-cart-button-'+elem.id).removeClass('addedToCart')
                $('.big-cart-button-'+elem.id).text("<?php echo get_phrase('add_to_cart'); ?>");
            }else {
                $('.big-cart-button-'+elem.id).addClass('addedToCart')
                $('.big-cart-button-'+elem.id).text("<?php echo get_phrase('added_to_cart'); ?>");
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

function handleEnrolledButton() {
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
</script>
<script>
    function adjustLayout() {
        const rightSide = document.getElementById('rightSide');
        const mainContainer = document.getElementById('main-container');
        const leftSide = document.getElementById('leftSide');

        if (window.innerWidth <= 768) { // Check if screen is smaller than 768px
            // Remove the right-side container
            if (rightSide) {
                rightSide.remove();
            }

            // Center the left-side container
            mainContainer.style.justifyContent = 'center';
            leftSide.style.width = '100%'; // Make the left side take full width
        } else {
            // Add the right-side container back if it's not there
            if (!document.getElementById('rightSide')) {
                const rightSideContainer = document.createElement('div');
                rightSideContainer.classList.add('right-side');
                rightSideContainer.style.width = '30%';
                rightSideContainer.style.marginLeft = '0';
                rightSideContainer.id = 'rightSide';
                rightSideContainer.innerHTML = `
                    <div class="row">
                        <div class="col">
                            <div class="right-content-wrap">
                                <!-- Content here -->
                            </div>
                        </div>
                    </div>
                `;
                mainContainer.appendChild(rightSideContainer);
            }

            // Restore the original layout
            mainContainer.style.justifyContent = 'space-between';
            leftSide.style.width = '60%'; // Set left-side width back to 60%
        }
    }

    // Call the function on page load and resize
    window.addEventListener('load', adjustLayout);
    window.addEventListener('resize', adjustLayout);
</script>