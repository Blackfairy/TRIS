<?php
isset($layout) ? "": $layout = "list";
isset($selected_category_id) ? "": $selected_category_id = "all";
isset($selected_level) ? "": $selected_level = "all";
isset($selected_language) ? "": $selected_language = "all";
isset($selected_rating) ? "": $selected_rating = "all";
isset($selected_price) ? "": $selected_price = "all";
// echo $selected_category_id.'-'.$selected_level.'-'.$selected_language.'-'.$selected_rating.'-'.$selected_price;
$number_of_visible_categories = 10;
if (isset($sub_category_id)) {
    $sub_category_details = $this->crud_model->get_category_details_by_id($sub_category_id)->row_array();
    $category_details     = $this->crud_model->get_categories($sub_category_details['parent'])->row_array();
    $category_name        = $category_details['name'];
    $sub_category_name    = $sub_category_details['name'];
}
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
                                <?php echo get_phrase('manuscripts'); ?>
                            </a>
                        </li>
                        <li class="breadcrumb-item active">
                            <?php
                                if ($selected_category_id == "all") {
                                    echo get_phrase('all_category');
                                }else {
                                    $category_details = $this->crud_model->get_category_details_by_id($selected_category_id)->row_array();
                                    echo $category_details['name'];
                                }
                             ?>
                        </li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</section>


<section class="category-manuscript-list-area">
    <div class="container">
        <div class="category-filter-box filter-box clearfix">
            <span><?php echo get_phrase('showing_on_this_page'); ?> : <?php echo count($manuscripts); ?></span>
            <a href="javascript::" onclick="toggleLayout('grid')" style="color:white;float: right; font-size: 19px; margin-left: 5px;"><i class="fas fa-th"></i></a>
            <a href="javascript::" onclick="toggleLayout('list')" style="color:white;float: right; font-size: 19px;"><i class="fas fa-th-list"></i></a>
            <a href="<?php echo site_url('home/manuscripts'); ?>" style="color:white;float: right; font-size: 19px; margin-right: 5px;"><i class="fas fa-sync-alt"></i></a>
        </div>
        <div class="row">
            <div class="col-lg-3 filter-area">
                <div class="card">
                    <a href="javascript::"  style="color: unset;">
                        <div class="card-header filter-card-header" id="headingOne" data-toggle="collapse" data-target="#collapseFilter" aria-expanded="true" aria-controls="collapseFilter">
                            <h6 class="mb-0">
                                <?php echo get_phrase('filter'); ?>
                                <i class="fas fa-sliders-h" style="float: right;"></i>
                            </h6>
                        </div>
                    </a>
                    <div id="collapseFilter" class="collapse show" aria-labelledby="headingOne" data-parent="#accordion">
                        <div class="card-body pt-0">
                        <div class="filter_type">
                        <h6><?php echo get_phrase('categories'); ?></h6>
                        <ul>
                            <span class="parent-category"><?php echo get_phrase('all_category'); ?></span>
                            <li class="ml-2">
                                <div class="">
                                    <input type="radio" id="category_all" name="category" class="categories custom-radio" value="all" onclick="filter(this)" <?php if($selected_category_id == 'all') echo 'checked'; ?>>
                                    <label for="category_all"><?php echo get_phrase('all_category'); ?></label>
                                </div>
                            </li>
                            <?php foreach ($categories as $category): ?>
                                <li class="ml-2">
                                    <div class="">
                                        <input type="radio" id="category-<?php echo $category['id']; ?>" name="category" class="categories custom-radio" value="<?php echo $category['id']; ?>" onclick="filter(this)" <?php if($selected_category_id == $category['id']) echo 'checked'; ?>>
                                        <label for="category-<?php echo $category['id']; ?>"><?php echo $category['name']; ?></label>
                                    </div>
                                </li>
                            <?php endforeach; ?>
                        </ul>

                        <a href="javascript:void(0);" id="city-toggle-btn" onclick="showToggle(this, 'hidden-categories')" style="font-size: 12px;">
                            <?php echo $total_number_of_categories > $number_of_visible_categories ? get_phrase('show_more') : ""; ?>
                        </a>
                    </div>

                            <hr>
    
                            <div class="filter_type">
                                <h6><?php echo get_phrase('sub categories'); ?></h6>
                                <ul>
                                    <?php
                                    // Initialize an array to keep track of already displayed sub-categories
                                    $displayed_sub_categories = [];

                                    foreach ($categories as $category):
                                        // Loop through sub-categories for the current category
                                        foreach ($this->crud_model->get_sub_categories($category['id']) as $sub_category):
                                            // Skip displaying this sub-category if it's already been displayed
                                            if (in_array($sub_category['slug'], $displayed_sub_categories)) {
                                                continue;
                                            }
                                            // Mark this sub-category as displayed
                                            $displayed_sub_categories[] = $sub_category['slug'];
                                            ?>
                                            <li class="ml-2">
                                                <div class="<?php if ($counter > $number_of_visible_categories): ?> hidden-categories hidden <?php endif; ?>">
                                                    <input type="radio" id="sub_category-<?php echo $sub_category['id']; ?>" name="sub_category" class="categories custom-radio" value="<?php echo $sub_category['slug']; ?>" onclick="filter(this)" <?php if($selected_sub_category == $sub_category['slug']) echo 'checked'; ?>>
                                                    <label for="sub_category-<?php echo $sub_category['id']; ?>"><?php echo $sub_category['name']; ?></label>
                                                </div>
                                            </li>
                                        <?php endforeach; ?>
                                    <?php endforeach; ?>
                                </ul>

                                <a href="javascript:void(0);" id="city-toggle-btn" onclick="showToggle(this, 'hidden-categories')" style="font-size: 12px;">
                                    <?php echo $total_number_of_categories > $number_of_visible_categories ? get_phrase('show_more') : ""; ?>
                                </a>
                            </div>

                            <hr>
                            <div class="filter_type">
                                <div class="form-group">
                                    <h6><?php echo get_phrase('price'); ?></h6>
                                    <ul>
                                        <li>
                                            <div class="">
                                                <input type="radio" id="price_all" name="price" class="prices custom-radio" value="all" onclick="filter(this)" <?php if($selected_price == 'all') echo 'checked'; ?>>
                                                <label for="price_all"><?php echo get_phrase('all'); ?></label>
                                            </div>
                                            <div class="">
                                                <input type="radio" id="price_free" name="price" class="prices custom-radio" value="free" onclick="filter(this)" <?php if($selected_price == 'free') echo 'checked'; ?>>
                                                <label for="price_free"><?php echo get_phrase('free'); ?></label>
                                            </div>
                                            <div class="">
                                                <input type="radio" id="price_paid" name="price" class="prices custom-radio" value="paid" onclick="filter(this)" <?php if($selected_price == 'paid') echo 'checked'; ?>>
                                                <label for="price_paid"><?php echo get_phrase('paid'); ?></label>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <hr>
                            
                            <hr>
                            <div class="filter_type">
                                <h6><?php echo get_phrase('language'); ?></h6>
                                <ul>
                                    <li>
                                        <div class="">
                                            <input type="radio" id="all_language" name="language" class="languages custom-radio" value="<?php echo 'all'; ?>" onclick="filter(this)" <?php if($selected_language == "all") echo 'checked'; ?>>
                                            <label for="<?php echo 'all_language'; ?>"><?php echo get_phrase('all'); ?></label>
                                        </div>
                                    </li>
                                    <?php
                                    $fields = $this->db->list_fields('language');
                                    foreach ($fields as $field):
                                        if ($field == 'phrase_id' || $field == 'phrase') continue;?>
                                        <li>
                                            <div class="">
                                                <input type="radio" id="language_<?php echo $field; ?>" name="language" class="languages custom-radio" value="<?php echo $field; ?>" onclick="filter(this)" <?php if($selected_language == $field) echo 'checked'; ?>>
                                                <label for="language_<?php echo $field; ?>"><?php echo ucfirst($field); ?></label>
                                            </div>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                            <hr>
                            <div class="filter_type">
                                <h6><?php echo get_phrase('ratings'); ?></h6>
                                <ul>
                                    <li>
                                        <div class="">
                                            <input type="radio" id="all_rating" name="rating" class="ratings custom-radio" value="<?php echo 'all'; ?>" onclick="filter(this)" <?php if($selected_rating == "all") echo 'checked'; ?>>
                                            <label for="all_rating"><?php echo get_phrase('all'); ?></label>
                                        </div>
                                    </li>
                                    <?php for($i = 1; $i <= 5; $i++): ?>
                                        <li>
                                            <div class="">
                                                <input type="radio" id="rating_<?php echo $i; ?>" name="rating" class="ratings custom-radio" value="<?php echo $i; ?>" onclick="filter(this)" <?php if($selected_rating == $i) echo 'checked'; ?>>
                                                <label for="rating_<?php echo $i; ?>">
                                                    <?php for($j = 1; $j <= $i; $j++): ?>
                                                        <i class="fas fa-star" style="color: #f4c150;"></i>
                                                    <?php endfor; ?>
                                                    <?php for($j = $i; $j < 5; $j++): ?>
                                                        <i class="far fa-star" style="color: #dedfe0;"></i>
                                                    <?php endfor; ?>
                                                </label>
                                            </div>
                                        </li>
                                    <?php endfor; ?>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-9">
                <div class="category-manuscript-list">
                    <?php include 'category_wise_manuscript_'.$layout.'_layout.php'; ?>
                    <?php if (count($manuscripts) == 0): ?>
                        <?php echo get_phrase('no_result_found'); ?>
                    <?php endif; ?>
                </div>
                <nav>
                    <?php if ($selected_category_id == "all" && $selected_price == 0 && $selected_level == 'all' && $selected_language == 'all' && $selected_rating == 'all'){
                        echo $this->pagination->create_links();
                    }?>
                </nav>
            </div>
        </div>
    </div>
</section>

<script type="text/javascript">

function get_url() {
    var urlPrefix = '<?php echo site_url('home/manuscripts?'); ?>';
    var urlSuffix = "";
    var selectedCategory = "";
    var selectedSubCategory = "";
    var selectedPrice = "";
    var selectedLevel = "";
    var selectedLanguage = "";
    var selectedRating = "";

    // Get selected category
    $('.categories:checked').each(function() {
        selectedCategory = $(this).val();  // Use .val() instead of .attr('value')
    });

    // Get selected sub-category
    $('.sub-categories:checked').each(function() {
        selectedSubCategory = $(this).val();  // Handle sub-category similarly
    });

    // Get selected price
    $('.prices:checked').each(function() {
        selectedPrice = $(this).val();  // Use .val() instead of .attr('value')
    });

    // Get selected difficulty Level
    $('.level:checked').each(function() {
        selectedLevel = $(this).val();  // Use .val() instead of .attr('value')
    });

    // Get selected language
    $('.languages:checked').each(function() {
        selectedLanguage = $(this).val();  // Use .val() instead of .attr('value')
    });

    // Get selected rating
    $('.ratings:checked').each(function() {
        selectedRating = $(this).val();  // Use .val() instead of .attr('value')
    });

    // Construct URL with all selected parameters
    urlSuffix = "category=" + selectedCategory + 
                "&&sub_category=" + selectedSubCategory + 
                "&&price=" + selectedPrice + 
                "&&level=" + selectedLevel + 
                "&&language=" + selectedLanguage + 
                "&&rating=" + selectedRating;

    var url = urlPrefix + urlSuffix;
    return url;
}

function filter() {
    var url = get_url();
    window.location.replace(url);  // Redirect to the generated URL
    // console.log(url);  // Uncomment for debugging
}

function toggleLayout(layout) {
    $.ajax({
        type: 'POST',
        url: '<?php echo site_url('home/set_layout_to_session'); ?>',
        data: {layout: layout},
        success: function(response) {
            location.reload();  // Reload the page after layout change
        }
    });
}

function showToggle(elem, selector) {
    $('.' + selector).slideToggle(20);
    if ($(elem).text() === "<?php echo get_phrase('show_more'); ?>") {
        $(elem).text('<?php echo get_phrase('show_less'); ?>');
    } else {
        $(elem).text('<?php echo get_phrase('show_more'); ?>');
    }
}
</script>
