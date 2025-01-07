<?php
    $category_name        = get_phrase('all_category');
    $sub_category_name    = get_phrase('all_sub_category');
?>

<section class="category-header-area">
    <div class="container-lg">
        <div class="row">
            <div class="col">
                <nav>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?php echo site_url('home'); ?>"><i class="fas fa-home"></i></a></li>
                        <li class="breadcrumb-item">
                            <a href="#">
                                <?php echo $category_name; ?>
                            </a>
                        </li>
                        <li class="breadcrumb-item active">
                            <?php echo $sub_category_name; ?>
                        </li>
                    </ol>
                </nav>
                <h1 class="category-name">
                    <?php echo $sub_category_name; ?>
                </h1>
            </div>
        </div>
    </div>
</section>


<section class="category-manuscript-list-area">
    <div class="container">
        <div class="row">
            <div class="col">
                <div class="category-filter-box filter-box clearfix">
                    <a href="<?php echo site_url('home/all_category'); ?>" class="btn btn-outline-secondary all-btn"><?php echo get_phrase('all'); ?></a>
                    <form action="<?php echo site_url('home/search'); ?>" method="get" class="form-inline">
                        <select name="category_id" class="form-control">
                            <option value="all"><?php echo get_phrase('select_category'); ?></option>
                            <?php foreach ($categories as $category): ?>
                                <option value="<?php echo $category['id']; ?>"><?php echo $category['name']; ?></option>
                            <?php endforeach; ?>
                        </select>
                        <select name="subcategory_id" class="form-control">
                            <option value="all"><?php echo get_phrase('select_subcategory'); ?></option>
                            <?php foreach ($subcategories as $subcategory): ?>
                                <option value="<?php echo $subcategory['id']; ?>"><?php echo $subcategory['name']; ?></option>
                            <?php endforeach; ?>
                        </select>
                        <select name="price" class="form-control">
                            <option value="all"><?php echo get_phrase('select_price'); ?></option>
                            <option value="free"><?php echo get_phrase('free'); ?></option>
                            <option value="paid"><?php echo get_phrase('paid'); ?></option>
                        </select>
                        <select name="level" class="form-control">
                            <option value="all"><?php echo get_phrase('select_level'); ?></option>
                            <option value="beginner"><?php echo get_phrase('beginner'); ?></option>
                            <option value="intermediate"><?php echo get_phrase('intermediate'); ?></option>
                            <option value="advanced"><?php echo get_phrase('advanced'); ?></option>
                        </select>
                        <select name="language" class="form-control">
                            <option value="all"><?php echo get_phrase('select_language'); ?></option>
                            <option value="english"><?php echo get_phrase('english'); ?></option>
                            <option value="french"><?php echo get_phrase('french'); ?></option>
                            <!-- Add more languages as needed -->
                        </select>
                        <select name="rating" class="form-control">
                            <option value="all"><?php echo get_phrase('select_rating'); ?></option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                        </select>
                        <button type="submit" class="btn btn-primary"><?php echo get_phrase('filter'); ?></button>
                    </form>
                </div>
                <div class="category-manuscript-list">
                    <ul>
                        <?php
                            $this->db->where('status', 'active');
                            $manuscripts = $this->db->get('manuscript', $per_page, $this->uri->segment(3));
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
                                    <a href="<?php echo site_url('home/manuscript/'.slugify($manuscript['title']).'/'.$manuscript['id']); ?>" class="manuscript-title"><?php echo $manuscript['title']; ?></a>
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
                                    </div>
                                </div>
                                <div class="manuscript-price-rating">
                                    <div class="manuscript-price">
                                        <?php if ($manuscript['is_free_manuscript'] == 1): ?>
                                            <span class="current-price"><?php echo get_phrase('free'); ?></span>
                                        <?php else: ?>
                                            <span class="current-price"><?php echo currency($manuscript['price']); ?></span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>
