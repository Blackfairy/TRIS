<?php
$manuscript_details = $this->crud_model->get_manuscript_by_id($manuscript_id)->row_array();
?>
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-3">
        <div class="text-center" style="margin-top: 10px; color: #fff !important;">
            <h4><?php echo $manuscript_details['title']; ?></h4>
        </div>
            <div class="accordion" id="accordionExample">
                <?php
                $section_counter = 0;
                foreach ($sections as $section):
                    $section_counter++;
                    $lessons = $this->crud_model->get_lessons('section', $section['id'])->result_array();?>
                    <div class="card" style="margin:10px 0px;">
                        <div class="card-header" id="<?php echo 'heading-'.$section['id']; ?>">

                            <h5 class="mb-0">
                                <button class="btn btn-link w-100 text-left" type="button" data-toggle="collapse" data-target="<?php echo '#collapse-'.$section['id']; ?>" aria-expanded="true" aria-controls="<?php echo 'collapse-'.$section['id']; ?>" style="color: #535a66; background: none; border: none; white-space: normal;">
                                    <h6 style="color: #686f7a; font-size: 15px;"> <?php echo $section['title']; ?></h6>
                                   
                                </button>
                            </h5>
                        </div>

                        <div id="<?php echo 'collapse-'.$section['id']; ?>" class="collapse <?php if($section_id == $section['id']) echo 'show'; ?>" aria-labelledby="<?php echo 'heading-'.$section['id']; ?>" data-parent="#accordionExample">
                            <div class="card-body"  style="padding:0px;">
                                <table style="width: 100%;">
                                    <?php foreach ($lessons as $lesson): ?>

                                        <tr style="width: 100%; padding: 5px 0px;">
                                            <td style="text-align: left;padding:10px; border-bottom:1px solid #ccc;">
                                                <a href="<?php echo site_url('home/lesson/'.slugify($manuscript_details['title']).'/'.$manuscript_id.'/'.$lesson['id']); ?>" id = "<?php echo $lesson['id']; ?>">
                                                    <i class="fa fa-play" style="font-size: 12px;color: #909090;padding: 10px;"></i>
                                                    <?php if ($lesson['lesson_type'] != 'other'):?>
                                                        <?php echo $lesson['title']; ?>
                                                    <?php else: ?>
                                                        <?php echo $lesson['title']; ?> <i class="fa fa-paperclip"></i>
                                                    <?php endif; ?>
                                                </a>
                                            </td>
                                            <td style="text-align: right; padding:10px; border-bottom:1px solid #ccc;">
                                                <span class="lesson_duration">
                                                    <?php if ($lesson['lesson_type'] == 'video' || $lesson['lesson_type'] == '' || $lesson['lesson_type'] == NULL): ?>
                                                        <?php echo $lesson['duration']; ?>
                                                    <?php elseif($lesson['lesson_type'] == 'quiz'): ?>
                                                        <i class="far fa-question-circle"></i>
                                                    <?php else:
                                                        $tmp           = explode('.', $lesson['attachment']);
                                                        $fileExtension = strtolower(end($tmp));?>

                                                        <?php if ($fileExtension == 'jpg' || $fileExtension == 'jpeg' || $fileExtension == 'png' || $fileExtension == 'bmp' || $fileExtension == 'svg'): ?>
                                                            <i class="fas fa-camera-retro"></i>
                                                        <?php elseif($fileExtension == 'pdf'): ?>
                                                            <i class="far fa-file-pdf"></i>
                                                        <?php elseif($fileExtension == 'doc' || $fileExtension == 'docx'): ?>
                                                            <i class="far fa-file-word"></i>
                                                        <?php elseif($fileExtension == 'txt'): ?>
                                                            <i class="far fa-file-alt"></i>
                                                        <?php else: ?>
                                                            <i class="fa fa-file"></i>
                                                        <?php endif; ?>

                                                    <?php endif; ?>
                                                </span>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </table>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php if (isset($lesson_id)): ?>
            <div class="col-lg-9" id = "video_player_area">
                <!-- <div class="" style="background-color: #333;"> -->
                <div class="" style="text-align: center;">
                    <?php
                    $lesson_details = $this->crud_model->get_lessons('lesson', $lesson_id)->row_array();
                    $lesson_thumbnail_url = $this->crud_model->get_lesson_thumbnail_url($lesson_id);

                    // If the lesson type is video
                    // i am checking the null and empty values because of the existing users does not have video in all video lesson as type
                    if($lesson_details['lesson_type'] == 'video' || $lesson_details['lesson_type'] == '' || $lesson_details['lesson_type'] == NULL):
                        $video_url = $lesson_details['video_url'];
                        $provider = $lesson_details['video_type'];
                        ?>

                        <div class="mt-5">
                            <a href="<?php echo base_url().'uploads/lesson_files/'.$lesson_details['attachment']; ?>" class="btn btn-sign-up" download style="color: #fff;">
                                <i class="fa fa-download" style="font-size: 20px;"></i> <?php echo get_phrase('download').' '.$lesson_details['title']; ?>
                            </a>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="" style="margin: 20px 0;" id="lesson-summary">
                    <div class="card">
                        <div class="card-body">
                                <div class="pdf-viewer mt-3">
                                    <iframe src="<?php echo base_url('uploads/lesson_files/'.$lesson_details['attachment']); ?>" width="100%" height="500px"></iframe>
                                </div>
                                <div class="text-center mt-2">
                                    <a href="<?php echo base_url('uploads/lesson_files/'.$lesson_details['attachment']); ?>" class="btn btn-primary" download><?php echo get_phrase('download').' '.$lesson_details['title']; ?></a>
                                </div>
                        </div>
                    </div>
                </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
