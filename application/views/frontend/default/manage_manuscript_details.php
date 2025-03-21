<?php
    $manuscript_details = $this->crud_model->get_manuscript_by_id($manuscript_id)->row_array();
?>
<section class="page-header-area my-manuscript-area">
    <div class="container">
        <div class="row">
            <div class="col">
                <nav>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?php echo site_url('home'); ?>"><i class="fas fa-home"></i></a></li>
                        <li class="breadcrumb-item"><a href="<?php echo site_url('home/dashboard'); ?>"><?php echo get_phrase('dashboard'); ?></a></li>
                        <li class="breadcrumb-item active"><a href="#"><?php echo get_phrase('edit_manuscript_detail'); ?></a></li>
                    </ol>
                </nav>
                <h1 class="page-title"><?php echo $page_title .' - '.$manuscript_details['title'];; ?></h1>
                <ul>
                    <li class="<?php if($type == 'edit_manuscript') echo "active"; ?>"><a href="<?php echo site_url('home/edit_manuscript/'.$manuscript_id.'/edit_manuscript'); ?>"><?php echo get_phrase('edit_basic_informations'); ?></a></li>
                    <li class="<?php if($type == 'manage_section') echo "active"; ?>"><a href="<?php echo site_url('home/edit_manuscript/'.$manuscript_id.'/manage_section'); ?>"><?php echo get_phrase('manage_section'); ?></a></li>
                    <li class="<?php if($type == 'manage_lesson') echo "active"; ?>"><a href="<?php echo site_url('home/edit_manuscript/'.$manuscript_id.'/manage_lesson'); ?>"><?php echo get_phrase('manage_lesson'); ?></a></li>
                </ul>
            </div>
        </div>
    </div>
</section>

<section class="category-manuscript-list-area">
    <div class="container">
        <div class="row">
            <?php if ($type == 'edit_manuscript'): ?>
                <?php include 'edit_manuscript.php'; ?>
            <?php endif; ?>
            <?php if ($type == 'manage_section'): ?>
                <?php include 'manage_section.php'; ?>
            <?php endif; ?>
            <?php if ($type == 'manage_lesson'): ?>
                <?php include 'manage_lesson.php'; ?>
            <?php endif; ?>
        </div>
    </div>
</section>
