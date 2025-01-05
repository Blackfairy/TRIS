<div class="row">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-body">
                <h4 class="page-title">
                    <i class="mdi mdi-apple-keyboard-command title_icon"></i>
                    <?php echo $page_title; ?>
                </h4>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-body">
                <h4 class="mb-3 header-title"><?php echo get_phrase('session_data'); ?></h4>
                <div class="table-responsive-sm mt-4">
                    <table id="basic-datatable" class="table table-striped table-centered mb-0">
                    <thead>
                        <tr>
                            <th>Ip Address</th>
                            <th>Session Data</th>
                            <th>Last Activity</th>
                            <th><?php echo get_phrase('actions'); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($session_data as $session): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($session['ip_address']); ?></td>
                                <td><?php echo htmlspecialchars($session['data']); ?></td>
                                <td><?php echo htmlspecialchars($session['timestamp']); ?></td>
                                <td>
                                  <div class="dropright dropright">
                                    <button type="button" class="btn btn-sm btn-outline-primary btn-rounded btn-icon" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="mdi mdi-dots-vertical"></i>
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li><a class="dropdown-item" href="<?php echo site_url('admin/user_form/edit_user_form/'.$user['id']) ?>"><?php echo get_phrase('edit'); ?></a></li>
                                        <li><a class="dropdown-item" href="#" onclick="confirm_modal('<?php echo site_url('admin/users/delete/'.$user['id']); ?>');"><?php echo get_phrase('delete'); ?></a></li>
                                    </ul>
                                </div>
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
