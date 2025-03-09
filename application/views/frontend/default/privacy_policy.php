<section class="category-header-area">
    <div class="container-lg" >
        <div class="row">
            <div class="col">
                <nav>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?php echo site_url('home'); ?>"><i class="fas fa-home"></i></a></li>
                        <li class="breadcrumb-item">
                            <a href="#">
                                <?php echo $page_title;; ?>
                            </a>
                        </li>
                    </ol>
                </nav>
                <h1 class="category-name">
                    <?php echo $page_title; ?>
                </h1>
            </div>
        </div>
    </div>
</section>

<section class="category-manuscript-list-area">
    <div class="container" style="background-color: white;">
        <div class="row">
        <div class="col" style="padding: 35px;color: black;">
                <?php echo get_frontend_settings('privacy_policy'); ?>
            </div>
        </div>
    </div>
</section>
