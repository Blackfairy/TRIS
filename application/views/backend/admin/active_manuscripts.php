<table class="table table-bordered" id="active_manuscripts_table">
    <thead>
      <tr>
        <th><?php echo get_phrase('title'); ?></th>
        <th><?php echo get_phrase('category'); ?></th>
        <th><?php echo get_phrase('researcher'); ?></th>
        <th><?php echo get_phrase('number_of_sections'); ?></th>
        <th><?php echo get_phrase('number_of_lessons'); ?></th>
        <th><?php echo get_phrase('number_of_enrolled_users'); ?></th>
        <th><?php echo get_phrase('action'); ?></th>
      </tr>
    </thead>
    <tbody>
        <?php
            $active_manuscripts = 0;
            foreach ($manuscripts->result_array() as $manuscript):
            if ($manuscript['status'] != 'active')
                continue;
            else
                $active_manuscripts++;
            ?>
            <tr>
                <td><?php echo $manuscript['title']; ?></td>
                <td>
                    <?php
                          $category_details = $this->crud_model->get_categories($manuscript['category_id'])->row_array();
                          echo $category_details['name'];
                     ?>
                 </td>
                 <td>
                     <?php
                        if ($manuscript['user_id'] > 0) {
                            $researcher_details = $this->user_model->get_all_user($manuscript['user_id'])->row_array();
                            echo $researcher_details['first_name'].' '.$researcher_details['last_name'];
                        }else {
                            $admin_details = $this->user_model->get_admin_details();
                            echo $admin_details['first_name'].' '.$admin_details['last_name'];
                        }
                     ?>
                 </td>
                <td hidden>
                    <ul style="list-style-type:square">
                    <?php
                      $lessons = $this->crud_model->get_lessons('manuscript', $manuscript['id'])->result_array();
                      foreach ($lessons as $lesson):?>
                      <a href="<?php echo site_url('admin/watch_video/'.slugify($lesson['title']).'/'.$lesson['id']); ?>"><li><?php echo $lesson['title']; ?></li></a>
                    <?php endforeach; ?>
                    </ul>
                </td>
                <td>
                    <?php
                        $sections = $this->crud_model->get_section('manuscript', $manuscript['id']);
                        echo $sections->num_rows();
                    ?>
                </td>
                <td>
                    <?php
                        $lessons = $this->crud_model->get_lessons('manuscript', $manuscript['id']);
                        echo $lessons->num_rows();
                    ?>
                </td>
                <td>
                    <?php
                        $enrol_history = $this->crud_model->enrol_history($manuscript['id']);
                        echo $enrol_history->num_rows();
                     ?>
                </td>
                <td>
                    <div class="btn-group">
                        <button class="btn btn-small btn-default btn-demo-space" data-toggle="dropdown"> <i class = "fa fa-ellipsis-v"></i> </button>
                        <ul class="dropdown-menu">
                            <li>
                                <a href="<?php echo site_url('home/manuscript/'.slugify($manuscript['title']).'/'.$manuscript['id']); ?>" target="_blank">
                                    <?php echo get_phrase('view_manuscript_on_frontend');?>
                                </a>
                            </li>

                            <li>
                                <a href="<?php echo site_url('admin/sections/'.$manuscript['id']); ?>">
                                    <?php echo get_phrase('manage_section');?>
                                </a>
                            </li>

                            <li>
                                <a href="<?php echo site_url('admin/lessons/'.$manuscript['id']); ?>">
                                    <?php echo get_phrase('manage_lesson');?>
                                </a>
                            </li>


                            <li>
                                <?php if ($manuscript['user_id'] != $this->session->userdata('user_id')): ?>
                                    <a href="#" onclick="showAjaxModal('<?php echo base_url();?>modal/popup/mail_on_manuscript_status_changing_modal/pending/<?php echo $manuscript['id'].'/'.$default_category_id.'/'.$default_sub_category_id;?>');">
                                        <?php echo get_phrase('mark_as_pending');?>
                                    </a>
                                <?php else: ?>
                                    <a href="#" onclick="confirm_modal('<?php echo site_url();?>admin/change_manuscript_status_for_admin/pending/<?php echo $manuscript['id'];?>', 'generic_confirmation');">
                                        <?php echo get_phrase('mark_as_pending');?>
                                    </a>
                                <?php endif; ?>
                            </li>


                            <li>
                                <a href="<?php echo site_url('admin/manuscript_form/manuscript_edit/'.$manuscript['id']) ?>">
                                    <?php echo get_phrase('edit');?>
                                </a>
                            </li>

                            <li class="divider"></li>
                            <li>
                                <a href="#" onclick="confirm_modal('<?php echo site_url('admin/manuscript_actions/delete/'.$manuscript['id']); ?>');">
                                    <?php echo get_phrase('delete');?>
                                </a>
                            </li>
                        </ul>
                    </div>
                </td>
            </tr>
        <?php endforeach; ?>
        <?php if ($active_manuscripts == 0): ?>
            <tr>
                <td colspan="7"><?php echo get_phrase('no_data_found'); ?></td>
            </tr>
        <?php endif; ?>
    </tbody>
  </table>
<!--
  <script type="text/javascript">
  var responsiveHelper;
  var breakpointDefinition = {
      tablet: 1024,
      phone : 480
  };
  var tableContainer;

      jQuery(document).ready(function($)
      {
          tableContainer = $("#active_manuscripts_table");

          tableContainer.dataTable({
              "sPaginationType": "bootstrap",
              "aLengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
              "bStateSave": true,


              // Responsive Settings
              bAutoWidth     : false,
              fnPreDrawCallback: function () {
                  // Initialize the responsive datatables helper once.
                  if (!responsiveHelper) {
                      responsiveHelper = new ResponsiveDatatablesHelper(tableContainer, breakpointDefinition);
                  }
              },
              fnRowCallback  : function (nRow, aData, iDisplayIndex, iDisplayIndexFull) {
                  responsiveHelper.createExpandIcon(nRow);
              },
              fnDrawCallback : function (oSettings) {
                  responsiveHelper.respond();
              }
          });

          $(".dataTables_wrapper select").select2({
              minimumResultsForSearch: -1
          });
      });
  </script> -->
