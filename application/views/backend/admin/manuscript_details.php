<ol class="breadcrumb bc-3">
    <li>
        <a href="<?php echo site_url('admin/dashboard'); ?>">
            <i class="entypo-folder"></i>
            <?php echo get_phrase('dashboard'); ?>
        </a>
    </li>
    <li><a href="<?php echo site_url('admin/manuscripts'); ?>"><?php echo get_phrase('manuscripts'); ?></a> </li>
    <li><a href="#" class="active"><?php echo get_phrase('manuscript_details'); ?></a> </li>
</ol>
<h2><i class="fa fa-arrow-circle-o-right"></i> <?php echo '"'.$manuscript_details['title'].'" '.get_phrase('details'); ?></h2>
<br />
<div class="row">
	<div class="col-md-8 col-md-offset-2">
		<div class="panel panel-primary" data-collapsed="0">
			<div class="panel-body">
                <table class="table table-bordered datatable" id="table-1">
                    <tbody>
                        <tr>
                            <th width = "50%"><?php echo get_phrase('title'); ?>:</th>
                            <td><?php echo $manuscript_details['title']; ?></td>
                        </tr>

                        <tr>
                            <th width = "50%"><?php echo get_phrase('researcher'); ?>:</th>
                            <td>
                                <?php
                                    $admin_details = $this->user_model->get_admin_details()->row_array();
                                    echo $admin_details['first_name'].' '.$admin_details['last_name'];
                                ?>
                            </td>
                        </tr>

                        <tr>
                            <th><?php echo get_phrase('category'); ?>:</th>
                            <td>
                                <?php
                                    $category_name = $this->db->get_where('category', array('id' => $manuscript_details['category_id']))->row()->name;
                                    echo $category_name;
                                ?>
                            </td>
                        </tr>
                        <tr>
                            <th><?php echo get_phrase('sub_category'); ?>:</th>
                            <td>
                                <?php
                                    $sub_category_name = $this->db->get_where('category', array('id' => $manuscript_details['sub_category_id']))->row()->name;
                                    echo $sub_category_name;
                                ?>
                            </td>
                        </tr>
                        <tr>
                            <th><?php echo get_phrase('number_of_lessons'); ?>:</th>
                            <td>
                                <?php
                                    $lessons = $this->db->get_where('lesson', array('manuscript_id' => $manuscript_details['id']));
                                    echo $lessons->num_rows().' '.get_phrase('lessons');
                                ?>
                            </td>
                        </tr>
                        <tr>
                            <th><?php echo get_phrase('enrolled_user'); ?>:</th>
                            <td>
                                <?php
                                    $enrolled_students = $this->db->get_where('enrol', array('manuscript_id' => $manuscript_details['id']));
                                    echo $enrolled_students->num_rows().' '.get_phrase('students');
                                ?>
                            </td>
                        </tr>
                        <tr>
                            <th><?php echo get_phrase('date_added'); ?>:</th>
                            <td>
                                <?php
                                    echo date('D,d-M-Y', $manuscript_details['date_added']);
                                ?>
                            </td>
                        </tr>
                        <tr>
                            <th><?php echo get_phrase('last_modified'); ?>:</th>
                            <td>
                                <?php
                                    if ($manuscript_details['last_modified'] > 0) {
                                        echo date('D,d-M-Y', $manuscript_details['last_modified']);
                                    }
                                ?>
                            </td>
                        </tr>
                    </tbody>
                  </table>
			</div>
		</div>
	</div>
</div>
