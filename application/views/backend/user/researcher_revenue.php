<?php
    $payment_histories = $this->crud_model->get_researcher_wise_payment_history($this->session->userdata('user_id'));
?>

<!-- start page title -->
<div class="row ">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-body">
                <h4 class="page-title"> <i class="mdi mdi-apple-keyboard-command title_icon"></i> <?php echo get_phrase('researcher_revenue'); ?></h4>
            </div> <!-- end card body-->
        </div> <!-- end card -->
    </div><!-- end col-->
</div>

<div class="row">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-body">
                <h4 class="mb-3 header-title"><?php echo get_phrase('researcher_revenue'); ?></h4>
                <div class="row justify-content-md-center">
                    <div class="col-xl-6">
                        <form class="form-inline" action="<?php echo site_url('user/researcher_revenue/filter_by_date_range') ?>" method="get">
                            <div class="col-xl-10">
                                <div class="form-group">
                                    <div id="reportrange" class="form-control" data-toggle="date-picker-range" data-target-display="#selectedValue"  data-cancel-class="btn-light" style="width: 100%;">
                                        <i class="mdi mdi-calendar"></i>&nbsp;
                                        <span id="selectedValue"><?php echo date("F d, Y" , $timestamp_start) . " - " . date("F d, Y" , $timestamp_end);?></span> <i class="mdi mdi-menu-down"></i>
                                    </div>
                                    <input id="date_range" type="hidden" name="date_range" value="<?php echo date("d F, Y" , $timestamp_start) . " - " . date("d F, Y" , $timestamp_end);?>">
                                </div>
                            </div>
                            <div class="col-xl-2">
                                <button type="submit" class="btn btn-info" id="submit-button" onclick="update_date_range();"> <?php echo get_phrase('filter');?></button>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="table-responsive-sm mt-4">
                    <table class="table table-striped table-centered mb-0">
                        <thead>
                            <tr>
                                <th><?php echo get_phrase('enrolled_manuscript'); ?></th>
                                <th><?php echo get_phrase('researcher_revenue'); ?></th>
                                <th><?php echo get_phrase('status'); ?></th>
                                <th><?php echo get_phrase('option'); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($payment_history as $payment):
                                $user_data = $this->db->get_where('users', array('id' => $payment['user_id']))->row_array();
                                $manuscript_data = $this->db->get_where('manuscript', array('id' => $payment['manuscript_id']))->row_array();?>
                                <?php
                                $paypal_keys          = json_decode($user_data['paypal_keys'], true);
                                $stripe_keys          = json_decode($user_data['stripe_keys'], true);
                                ?>
                                <tr class="gradeU">
                                    <td>
                                        <strong><a href="<?php echo site_url('home/manuscript/'.slugify($manuscript_data['title']).'/'.$manuscript_data['id']); ?>" target="_blank"><?php echo ellipsis($manuscript_data['title']); ?></a></strong><br>
                                        <small class="text-muted"><?php echo get_phrase('enrolment_date').': '.date('D, d-M-Y', $payment['date_added']); ?></small>
                                    </td>
                                    <td>
                                        <?php echo currency($payment['researcher_revenue']); ?><br>
                                        <small class="text-muted"><?php echo get_phrase('total_amount').': '.currency($payment['amount']); ?></small>
                                    </td>
                                    <td style="text-align: center;">
                                        <?php if ($payment['researcher_payment_status'] == 0): ?>
                                            <div class="badge badge-danger"><?php echo get_phrase('pending'); ?></div>
                                        <?php elseif($payment['researcher_payment_status'] == 1): ?>
                                            <div class="badge badge-success"><?php echo get_phrase('paid'); ?></div>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-center">
                                        <a href="<?php echo site_url('user/invoice/'.$payment['id']); ?>" class="btn btn-outline-primary btn-rounded btn-sm"><i class="mdi mdi-printer-settings"></i></a>
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
function update_date_range()
{
    var x = $("#selectedValue").html();
    $("#date_range").val(x);
}
</script>
