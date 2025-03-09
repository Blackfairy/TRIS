<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<div class="row ">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-body">
                <h4 class="page-title"> <i class="mdi mdi-apple-keyboard-command title_icon"></i> <?php echo get_phrase('audit_trail'); ?>
                    <a href="<?php echo site_url('admin/spreadhseet_format_download'); ?>" class="btn btn-outline-success btn-rounded alignToTitle">
                        <i class="mdi mdi-file-excel"></i> Export to Excel
                    </a>

                </h4>
            </div> <!-- end card body-->
        </div> <!-- end card -->
    </div><!-- end col-->
</div>
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <table id="basic-datatable" class="table dt-responsive nowrap">
                    <thead>
                        <tr>
                            <th><?php echo get_phrase('user_email'); ?></th>
                            <th><?php echo get_phrase('action'); ?></th>
                            <th><?php echo get_phrase('table_name'); ?></th>
                            <th><?php echo get_phrase('record_id'); ?></th>
                            <th><?php echo get_phrase('details'); ?></th>
                            <th><?php echo get_phrase('timestamp'); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($audit_trails as $audit_trail): ?>
                            <tr>
                                <td><?php echo $audit_trail['user_email']; ?></td>
                                <td><?php echo $audit_trail['action']; ?></td>
                                <td><?php echo $audit_trail['table_name']; ?></td>
                                <td><?php echo $audit_trail['record_id']; ?></td>
                                <td><?php echo $audit_trail['details']; ?></td>
                                <td><?php echo $audit_trail['timestamp']; ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>