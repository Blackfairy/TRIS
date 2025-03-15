<div class="row">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-body">
                <h4 class="page-title"> <i class="mdi mdi-apple-keyboard-command title_icon"></i> <?php echo get_phrase('users'); ?>
                <a href="<?php echo site_url('admin/user_form/add_user_form'); ?>" class="btn btn-outline-primary btn-rounded alignToTitle"><i class="mdi mdi-plus"></i><?php echo get_phrase('add_user'); ?></a>
                <a href="<?php echo site_url('admin/admin'); ?>" class="btn btn-outline-success btn-rounded alignToTitle">
                    <i class="mdi mdi-file-excel"></i> Export to Excel
                </a>
                </h4>
            </div> <!-- end card body-->
        </div> <!-- end card -->
    </div><!-- end col-->
</div>

<div class="row">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-body">
                <h4 class="mb-3 header-title"><?php echo get_phrase('users'); ?></h4>
                <form class="row justify-content-center" action="<?php echo site_url('admin/admin'); ?>" method="get">
                    <div class="col-xl-3">
                        <div class="form-group">
                            <label for="role"><?php echo get_phrase('role'); ?></label>
                            <select class="form-control" name="role" id="role">
                                <option value=""><?php echo get_phrase('select_role'); ?></option>
                                <option value="1" <?php echo isset($_GET['role']) && $_GET['role'] == '1' ? 'selected' : ''; ?>><?php echo get_phrase('admin'); ?></option>
                                <option value="2" <?php echo isset($_GET['role']) && $_GET['role'] == '2' ? 'selected' : ''; ?>><?php echo get_phrase('user'); ?></option>
                            </select>
                        </div>
                    </div>
                    <div class="col-xl-2">
                        <label for=".." class="text-white"><?php echo get_phrase('..'); ?></label>
                        <button type="submit" class="btn btn-primary btn-block" name="button"><?php echo get_phrase('filter'); ?></button>
                    </div>
                </form>
                <div class="table-responsive-sm mt-4">
                    <table id="basic-datatable" class="table table-striped table-centered mb-0">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th><?php echo get_phrase('photo'); ?></th>
                                <th><?php echo get_phrase('name'); ?></th>
                                <th><?php echo get_phrase('email'); ?></th>
                                <th><?php echo get_phrase('role'); ?></th>
                                <th><?php echo get_phrase('actions'); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($users->result_array() as $key => $user): ?>
                                <tr>
                                    <td><?php echo $key + 1; ?></td>
                                    <td>
                                        <img src="<?php echo $this->user_model->get_user_image_url($user['id']); ?>" alt="" height="50" width="50" class="img-fluid rounded-circle img-thumbnail">
                                    </td>
                                    <td><?php echo $user['first_name'] . ' ' . $user['last_name']; ?></td>
                                    <td><?php echo $user['email']; ?></td>
                                    <td>
                                        <?php
                                        if ($user['email'] == 'superadmin@gmail.com') {
                                            echo "Super Admin";
                                        } elseif ($user['role_id'] == 1) {
                                            echo "Admin";
                                        } elseif ($user['role_id'] == 2) {
                                            echo "User";
                                        } else {
                                            echo "Unknown Role"; // Optional: handle cases for undefined role IDs
                                        }
                                        ?>
                                    </td>
                                    <td>
                                        <div class="dropright dropright">
                                            <button type="button" class="btn btn-sm btn-outline-primary btn-rounded btn-icon" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                <i class="mdi mdi-dots-vertical"></i>
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li><a class="dropdown-item" href="<?php echo site_url('admin/user_form/edit_user_form/' . $user['id']) ?>"><?php echo get_phrase('edit'); ?></a></li>
                                                <li><a class="dropdown-item" href="#" onclick="confirm_modal('<?php echo site_url('admin/users/delete/' . $user['id']); ?>');"><?php echo get_phrase('delete'); ?></a></li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                    <?php if (empty($users->result_array())): ?>
                        <p><?php echo get_phrase('no_users_found'); ?></p>
                    <?php endif; ?>
                </div>
            </div> <!-- end card body-->
        </div> <!-- end card -->
    </div><!-- end col-->
</div>