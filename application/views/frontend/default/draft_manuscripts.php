<div class="row no-gutters" id = "my_manuscripts_area">
    <?php
     foreach ($my_manuscripts['draft']->result_array() as $my_manuscript):
        $manuscript_details = $this->crud_model->get_manuscript_by_id($my_manuscript['id'])->row_array();?>

        <div class="col-lg-3">
            <div class="manuscript-box-wrap">
                    <div class="manuscript-box">
                        <div class="manuscript-image">
                            <img src="<?php echo $this->crud_model->get_manuscript_thumbnail_url($my_manuscript['id']); ?>" alt="" class="img-fluid">
                        </div>
                        <div class="manuscript-details">
                            <a href="<?php echo site_url('home/manuscript/'.slugify($manuscript_details['title']).'/'.$my_manuscript['id']); ?>"><h5 class="title"><?php echo $manuscript_details['title']; ?></h5></a>
                        </div>
                        <div class="row" style="padding: 5px;">
                            <div class="col-md-12" style="margin-bottom: 5px;">
                                <a href="<?php echo site_url('home/manuscript/'.slugify($manuscript_details['title']).'/'.$my_manuscript['id']); ?>" class="btn btn-block"><?php echo get_phrase('manuscript_details'); ?></a>
                            </div>

                            <div class="col-md-12" style="margin-bottom: 5px;">
                                 <a href="<?php echo site_url('home/lesson/'.slugify($manuscript_details['title']).'/'.$my_manuscript['id']); ?>" class="btn btn-block"><?php echo get_phrase('view_lessons'); ?></a>
                            </div>

                            <div class="col-md-12" style="margin-bottom: 5px;">
                                 <a href="<?php echo site_url('home/edit_manuscript/'.$my_manuscript['id']); ?>" class="btn btn-block"><?php echo get_phrase('edit_manuscript'); ?></a>
                            </div>
                        </div>
                    </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>
