<?php
// $param2 = manuscript id
$manuscript_details = $this->crud_model->get_manuscript_by_id($param2)->row_array();
$sections = $this->crud_model->get_section('manuscript', $param2)->result_array();
?>
<form action="<?php echo site_url('admin/lessons/'.$param2.'/add'); ?>" method="post" enctype="multipart/form-data">

    <div class="form-group">
        <label><?php echo get_phrase('title'); ?></label>
        <input type="text" name="title" class="form-control" required>
    </div>

    <input type="hidden" name="manuscript_id" value="<?php echo $param2; ?>">

    <div class="form-group">
        <label for="section_id"><?php echo get_phrase('section'); ?></label>
        <select class="form-control select2" data-toggle="select2" name="section_id" id="section_id" required>
            <?php foreach ($sections as $section): ?>
                <option value="<?php echo $section['id']; ?>"><?php echo $section['title']; ?></option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="form-group">
        <label for="lesson_type"><?php echo get_phrase('research_document_type'); ?></label>
        <select class="form-control select2" data-toggle="select2" name="lesson_type" id="lesson_type" required onchange="show_lesson_type_form(this.value)">
            <option value=""><?php echo get_phrase('select_type_of_file'); ?></option>
            <option value="other-pdf"><?php echo get_phrase('pdf_file'); ?></option>
            <option value="other-doc"><?php echo get_phrase('document_file'); ?></option>
        </select>
    </div>

    <div class="form-group" id="other" style="display: none;">
        <label><?php echo get_phrase('attachment'); ?></label>
        <div class="input-group">
            <div class="custom-file">
                <input type="file" class="custom-file-input" id="attachment" name="attachment" onchange="changeTitleOfImageUploader(this)">
                <label class="custom-file-label" for="attachment"><?php echo get_phrase('attachment'); ?></label>
            </div>
        </div>
    </div>

    <div class="text-center">
        <button class="btn btn-success" type="submit" name="button"><?php echo get_phrase('add_research'); ?></button>
    </div>
</form>

<script type="text/javascript">
    $(document).ready(function() {
        initSelect2(['#section_id','#lesson_type']);
    });

    function show_lesson_type_form(param) {
        if (param === "other-pdf" || param === "other-doc") {
            $('#other').show();
        } else {
            $('#other').hide();
        }
    }
</script>