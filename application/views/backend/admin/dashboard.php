<?php
    $status_wise_manuscripts = $this->crud_model->get_status_wise_manuscripts();
    $number_of_manuscripts = $status_wise_manuscripts['pending']->num_rows() + $status_wise_manuscripts['active']->num_rows();
    $number_of_lessons = $this->crud_model->get_lessons()->num_rows();
    $number_of_pending_manuscripts = $status_wise_manuscripts['pending']->num_rows(); // New variable for pending manuscripts
    $number_of_enrolment = $this->crud_model->enrol_history()->num_rows();
    $number_of_students = $this->user_model->get_user()->num_rows();
?>
<div class="row">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-body">
                <h4 class="page-title"> <i class="mdi mdi-apple-keyboard-command title_icon"></i> <?php echo get_phrase('dashboard'); ?></h4>
            </div> <!-- end card body-->
        </div> <!-- end card -->
    </div><!-- end col-->
</div>
<div class="row">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-body">

                <h4 class="header-title mb-4"><?php echo get_phrase('admin_revenue_this_year'); ?></h4>

                <div class="mt-3 chartjs-chart" style="height: 320px;">
                    <canvas id="task-area-chart"></canvas>
                </div>
            </div> <!-- end card body-->
        </div> <!-- end card -->
    </div><!-- end col-->
</div>


<div class="row">
    <div class="col-12">
        <div class="card widget-inline">
            <div class="card-body p-0">
                <div class="row no-gutters">
                    <div class="col-sm-6 col-xl-3">
                        <a href="<?php echo site_url('admin/manuscripts'); ?>" class="text-secondary">
                            <div class="card shadow-none m-0">
                                <div class="card-body text-center">
                                    <i class="dripicons-archive text-muted" style="font-size: 24px;"></i>
                                    <h3><span><?php echo $number_of_manuscripts; ?></span></h3>
                                    <p class="text-muted font-15 mb-0"><?php echo get_phrase('number_manuscripts'); ?></p>
                                </div>
                            </div>
                        </a>
                    </div>

                    <div class="col-sm-6 col-xl-3">
                        <a href="<?php echo site_url('admin/manuscripts'); ?>" class="text-secondary">
                            <div class="card shadow-none m-0 border-left">
                                <div class="card-body text-center">
                                    <i class="dripicons-camcorder text-muted" style="font-size: 24px;"></i>
                                    <h3><span><?php echo $number_of_pending_manuscripts; ?></span></h3> <!-- Updated to display pending manuscripts -->
                                    <p class="text-muted font-15 mb-0"><?php echo get_phrase('number_of_pending_manuscripts'); ?></p> <!-- Updated label -->
                                </div>
                            </div>
                        </a>
                    </div>

                    <div class="col-sm-6 col-xl-3">
                        <a href="<?php echo site_url('admin/enrol_history'); ?>" class="text-secondary">
                            <div class="card shadow-none m-0 border-left">
                                <div class="card-body text-center">
                                    <i class="dripicons-network-3 text-muted" style="font-size: 24px;"></i>
                                    <h3><span><?php echo $number_of_enrolment; ?></span></h3>
                                    <p class="text-muted font-15 mb-0"><?php echo get_phrase('number_of_manuscript_accessed'); ?></p>
                                </div>
                            </div>
                        </a>
                    </div>

                    <div class="col-sm-6 col-xl-3">
                        <a href="<?php echo site_url('admin/users'); ?>" class="text-secondary">
                            <div class="card shadow-none m-0 border-left">
                                <div class="card-body text-center">
                                    <i class="dripicons-user-group text-muted" style="font-size: 24px;"></i>
                                    <h3><span><?php echo $number_of_students; ?></span></h3>
                                    <p class="text-muted font-15 mb-0"><?php echo get_phrase('number_of_student'); ?></p>
                                </div>
                            </div>
                        </a>
                    </div>

                </div> <!-- end row -->
            </div>
        </div> <!-- end card-box-->
    </div> <!-- end col-->
</div>
<div class="row">
    <div class="col-xl-4">
        <div class="card">
            <div class="card-body">
                <h4 class="header-title mb-4"><?php echo get_phrase('manuscript_overview'); ?></h4>
                <div class="my-4 chartjs-chart" style="height: 202px;">
                    <canvas id="project-status-chart"></canvas>
                </div>
                <div class="row text-center mt-2 py-2">
                    <div class="col-6">
                        <i class="mdi mdi-trending-up text-success mt-3 h3"></i>
                        <h3 class="font-weight-normal">
                            <span><?php echo $status_wise_manuscripts['active']->num_rows(); ?></span>
                        </h3>
                        <p class="text-muted mb-0"><?php echo get_phrase('active_manuscripts'); ?></p>
                    </div>
                    <div class="col-6">
                        <i class="mdi mdi-trending-down text-warning mt-3 h3"></i>
                        <h3 class="font-weight-normal">
                            <span><?php echo $status_wise_manuscripts['pending']->num_rows(); ?></span>
                        </h3>
                        <p class="text-muted mb-0"> <?php echo get_phrase('pending_manuscripts'); ?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-8">
        <div class="card" id = 'unpaid-researcher-revenue'>
            <div class="card-body">
                <h4 class="header-title mb-3"><?php echo get_phrase('unpaid_researcher_revenues'); ?>
                    <a href="<?php echo site_url('admin/researcher_revenue'); ?>" class="alignToTitle" id ="go-to-researcher-revenue"> <i class="mdi mdi-logout"></i> </a>
                </h4>
                <div class="table-responsive">
                    <table class="table table-centered table-hover mb-0">
                        <tbody>

                            <?php
                                $this->db->where('researcher_payment_status', 0);
                                $this->db->where('researcher_revenue >', 0);
                                $unpaid_researcher_revenues = $this->db->get('payment')->result_array();
                                foreach ($unpaid_researcher_revenues as $key => $row):
                                $manuscript_details = $this->crud_model->get_manuscript_by_id($row['manuscript_id'])->row_array();
                                $researcher_details = $this->user_model->get_all_user($manuscript_details['user_id'])->row_array();
                            ?>
                            <tr>
                                <td>
                                    <h5 class="font-14 my-1"><a href="<?php echo site_url('home/manuscript/'.slugify($manuscript_details['title']).'/'.$manuscript_details['id']) ?>" class="text-body" target="_blank"><?php echo $manuscript_details['title']; ?></a></h5>
                                    <span class="text-muted font-13"><?php echo get_phrase('manuscript_title'); ?></span>
                                </td>
                                <td>
                                    <h5 class="font-14 my-1"><a href="javascript:void(0);" class="text-body" style="cursor: auto;"><?php echo $researcher_details['first_name'].' '.$researcher_details['last_name']; ?></a></h5>
                                    <small><?php echo get_phrase('email'); ?>: <span class="text-muted font-13"><?php echo $researcher_details['email']; ?></span></small>
                                </td>
                                <td>
                                    <h5 class="font-14 my-1"><a href="javascript:void(0);" class="text-body" style="cursor: auto;"><?php echo currency($row['researcher_revenue']); ?></a></h5>
                                    <small><span class="text-muted font-13"><?php echo get_phrase('researcher_revenue'); ?></span></small>
                                </td>
                            </tr>
                            <?php endforeach; ?>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $('#unpaid-researcher-revenue').mouseenter(function() {
        $('#go-to-researcher-revenue').show();
    });
    $('#unpaid-researcher-revenue').mouseleave(function() {
        $('#go-to-researcher-revenue').hide();
    });
</script>
