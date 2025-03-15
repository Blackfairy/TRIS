<div class="row justify-content-center">
    <div class="col-xl-12 mb-4 text-center mt-3">
        <a href="javascript:void(0)" class="btn btn-outline-primary btn-rounded btn-sm ml-1" onclick="addResearchSection('<?php echo site_url('modal/popup/lesson_add/'.$manuscript_id); ?>', '<?php echo get_phrase('add_new_research'); ?>')"><i class="mdi mdi-plus"></i> <?php echo get_phrase('add_research'); ?></a>
    </div>

    <div class="col-xl-8">
        <div class="row">
            <?php
            $lesson_counter = 0;
            $quiz_counter   = 0;
            $sections = $this->crud_model->get_section('manuscript', $manuscript_id)->result_array();
            foreach ($sections as $key => $section):?>
            <div class="col-xl-12">
                <div class="card bg-light text-seconday on-hover-action mb-5" id = "section-<?php echo $section['id']; ?>">
                    <div class="card-body">
                        <h5 class="card-title" class="mb-3" style="min-height: 35px;"><span class="font-weight-light"><?php echo get_phrase('section').' '.++$key; ?></span>: <?php echo $section['title']; ?>
                            <div class="row justify-content-center alignToTitle float-right display-none" id = "widgets-of-section-<?php echo $section['id']; ?>">
                                <button type="button" class="btn btn-outline-secondary btn-rounded btn-sm" name="button" onclick="showLargeModal('<?php echo site_url('modal/popup/sort_lesson/'.$section['id']); ?>', '<?php echo get_phrase('sort'); ?>')" ><i class="mdi mdi-sort-variant"></i> <?php echo get_phrase('sort'); ?></button>
                                <button type="button" class="btn btn-outline-secondary btn-rounded btn-sm ml-1" name="button" onclick="showAjaxModal('<?php echo site_url('modal/popup/section_edit/'.$section['id'].'/'.$manuscript_id); ?>', '<?php echo get_phrase('update_section'); ?>')" ><i class="mdi mdi-pencil-outline"></i> <?php echo get_phrase('edit_section'); ?></button>
                                
                            </div>
                        </h5>
                        <div class="clearfix"></div>
                        <?php
                        $lessons = $this->crud_model->get_lessons('section', $section['id'])->result_array();
                        foreach ($lessons as $index => $lesson):?>
                        <div class="col-md-12">
                            <!-- Portlet card -->
                            <div class="card text-secondary on-hover-action mb-2" id = "<?php echo 'lesson-'.$lesson['id']; ?>">
                                <div class="card-body thinner-card-body">
                                    <div class="card-widgets display-none" id = "widgets-of-lesson-<?php echo $lesson['id']; ?>">
                                        <?php if ($lesson['lesson_type'] == 'quiz'): ?>
                                            <a href="javascript::" onclick="showLargeModal('<?php echo site_url('modal/popup/quiz_questions/'.$lesson['id']); ?>', '<?php echo get_phrase('manage_quiz_questions'); ?>')"><i class="mdi mdi-comment-question-outline"></i></a>
                                            <a href="javascript::" onclick="showAjaxModal('<?php echo site_url('modal/popup/quiz_edit/'.$lesson['id'].'/'.$manuscript_id); ?>', '<?php echo get_phrase('update_quiz_information'); ?>')"><i class="mdi mdi-pencil-outline"></i></a>
                                        <?php else: ?>
                                            <a href="javascript::" onclick="showAjaxModal('<?php echo site_url('modal/popup/lesson_edit/'.$lesson['id'].'/'.$manuscript_id); ?>', '<?php echo get_phrase('update_manuscript'); ?>')"><i class="mdi mdi-pencil-outline"></i></a>
                                        <?php endif; ?>
                                        <a href="javascript::" onclick="confirm_modal('<?php echo site_url('admin/lessons/'.$manuscript_id.'/delete'.'/'.$lesson['id']); ?>');"><i class="mdi mdi-window-close"></i></a>
                                    </div>
                                    <h5 class="card-title mb-0">
                                        <span class="font-weight-light">
                                            <?php
                                            if ($lesson['lesson_type'] == 'quiz') {
                                                $quiz_counter++; // Keeps track of number of quiz
                                                $lesson_type = $lesson['lesson_type'];
                                            }else {
                                                $lesson_counter++; // Keeps track of number of lesson
                                                if ($lesson['attachment_type'] == 'txt' || $lesson['attachment_type'] == 'pdf' || $lesson['attachment_type'] == 'doc' || $lesson['attachment_type'] == 'img') {
                                                    $lesson_type = $lesson['attachment_type'];
                                                }else {
                                                    $lesson_type = 'video';
                                                }
                                            }
                                            ?>
                                            <img src="<?php echo base_url('assets/backend/lesson_icon/'.$lesson_type.'.png'); ?>" alt="" height = "16">
                                            <?php echo $lesson['lesson_type'] == 'quiz' ? get_phrase('quiz').' '.$quiz_counter : get_phrase('file').' '.$lesson_counter; ?>
                                        </span>: <?php echo $lesson['title']; ?>
                                    </h5>
                                    <?php if ($lesson['attachment_type'] == 'pdf'): ?>
                                        <div class="pdf-viewer mt-3">
                                            <iframe src="<?php echo base_url('uploads/lesson_files/'.$lesson['attachment']); ?>" width="100%" height="500px"></iframe>
                                        </div>
                                        <div class="text-center mt-2">
                                            <a href="<?php echo base_url('uploads/lesson_files/'.$lesson['attachment']); ?>" class="btn btn-primary" download><?php echo get_phrase('download_pdf'); ?></a>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div> <!-- end card-->
                        </div>
                    <?php endforeach; ?>
                </div> <!-- end card-body-->
            </div> <!-- end card-->
        </div>
    <?php endforeach; ?>
</div>
</div>
</div>

<script>
function addResearchSection(url, title) {
    // Check if the "Research File" section already exists
    var sectionExists = false;
    $('.card-title').each(function() {
        if ($(this).text().includes('Research File')) {
            sectionExists = true;
            return false; // Break the loop
        }
    });

    if (!sectionExists) {
        // Add the "Research File" section
        $.ajax({
            url: '<?php echo site_url('admin/sections/'.$manuscript_id.'/add'); ?>',
            type: 'POST',
            data: {title: 'Research File', manuscript_id: '<?php echo $manuscript_id; ?>'},
            success: function(response) {
                // Show the modal for adding a new research
                showAjaxModal(url, title);
            }
        });
    } else {
        // Show the modal for adding a new research
        showAjaxModal(url, title);
    }
}
</script>