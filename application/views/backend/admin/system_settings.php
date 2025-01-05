<div class="row">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-body">
                <h4 class="page-title">
                    <i class="mdi mdi-apple-keyboard-command title_icon"></i>
                    <?php echo get_phrase('system_settings'); ?>
                </h4>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-xl-7">
        <div class="card">
            <div class="card-body">
                <div class="col-lg-12">
                    <h4 class="mb-3 header-title"><?php echo get_phrase('system_settings'); ?></h4>

                    <form class="required-form" action="<?php echo site_url('admin/system_settings/system_update'); ?>" method="post" enctype="multipart/form-data">
                        <div class="form-group">
                            <label for="system_name"><?php echo get_phrase('website_name'); ?><span class="required">*</span></label>
                            <input type="text" name="system_name" id="system_name" class="form-control" value="<?php echo get_settings('system_name'); ?>" required>
                        </div>

                        <div class="form-group">
                            <label for="system_title"><?php echo get_phrase('website_title'); ?><span class="required">*</span></label>
                            <input type="text" name="system_title" id="system_title" class="form-control" value="<?php echo get_settings('system_title'); ?>" required>
                        </div>

                        <div class="form-group">
                            <label for="author"><?php echo get_phrase('author'); ?><span class="required">*</span></label>
                            <input type="text" name="author" id="author" class="form-control" value="<?php echo get_settings('author'); ?>" required>
                        </div>

                        <div class="form-group">
                            <label for="system_email"><?php echo get_phrase('system_email'); ?><span class="required">*</span></label>
                            <input type="email" name="system_email" id="system_email" class="form-control" value="<?php echo get_settings('system_email'); ?>" required>
                        </div>

                        <div class="form-group">
                            <label for="address"><?php echo get_phrase('address'); ?><span class="required">*</span></label>
                            <textarea name="address" id="address" class="form-control" rows="5" required><?php echo get_settings('address'); ?></textarea>
                        </div>

                        <div class="form-group">
                            <label for="phone"><?php echo get_phrase('phone'); ?><span class="required">*</span></label>
                            <input type="text" name="phone" id="phone" class="form-control" value="<?php echo get_settings('phone'); ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="language"><?php echo get_phrase('student_email_verification'); ?></label>
                            <select class="form-control select2" data-toggle="select2" name="student_email_verification" id="student_email_verification">
                                <option value="enable" <?php if(get_settings('student_email_verification') == "enable") echo 'selected'; ?>><?php echo get_phrase('enable'); ?></option>
                                <option value="disable" <?php if(get_settings('student_email_verification') == "disable") echo 'selected'; ?>><?php echo get_phrase('disable'); ?></option>
                            </select>
                        </div>

                        <div class="form-group mb-0">
                            <button type="submit" class="btn btn-primary"><?php echo get_phrase('save_settings'); ?></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-5">
        <div class="card">
            <div class="card-body">

                <h4 class="mb-3 mt-5 header-title"><?php echo get_phrase('backup_database'); ?></h4>
                <div class="col-lg-12">
                <p class="note">Before backing up, make sure you have enough storage space and that no critical operations are ongoing in the database.</p>
                    <a href="<?php echo site_url('updater/backup_database'); ?>" class="btn btn-secondary btn-block"><?php echo get_phrase('backup_now'); ?></a>
                </div>

                <h4 class="mb-3 mt-5 header-title"><?php echo get_phrase('restore_database'); ?></h4>
                <div class="col-lg-12">
                <p class="note">Before restoring, ensure you have a recent backup and be aware that restoring will overwrite the current database.</p>
                <form id="restoreForm" action="<?php echo site_url('updater/restore_database'); ?>" method="post" enctype="multipart/form-data">
                        <div class="form-group">
                            <label for="sql_file"><?php echo get_phrase('upload_sql_file'); ?><span class="required">*</span></label>
                            <div class="upload-box">
                                <input type="file" class="form-control" name="sql_file" id="sqlFile" required onchange="updateFileName(this)">
                                <span><?php echo get_phrase('choose_file'); ?></span>
                            </div>
                        </div>

                        <div class="form-group mb-0">
                            <button type="button" id="restoreButton" class="btn btn-danger"><?php echo get_phrase('restore_now'); ?></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Confirmation Modal -->
<div id="confirmationModal" class="modal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><?php echo get_phrase('confirm_restore'); ?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p><?php echo get_phrase('are_you_sure_you_want_to_restore_the_database?'); ?></p>
                <p><?php echo get_phrase('This_action_cannot_be_undone.'); ?></p>
            </div>
            <div class="modal-footer">
                <button type="button" id="confirmRestoreButton" class="btn btn-danger"><?php echo get_phrase('yes_restore'); ?></button>
                <button type="button" id="cancelRestoreButton" class="btn btn-secondary" data-dismiss="modal"><?php echo get_phrase('cancel'); ?></button>
            </div>
        </div>
    </div>
</div>
<!-- Backup Confirmation Modal -->
<div id="backupConfirmationModal" class="modal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><?php echo get_phrase('confirm_backup'); ?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p><?php echo get_phrase('are_you_sure_you_want_to_backup_the_database'); ?></p>
            </div>
            <div class="modal-footer">
                <button type="button" id="confirmBackupButton" class="btn btn-secondary"><?php echo get_phrase('yes_backup'); ?></button>
                <button type="button" id="cancelBackupButton" class="btn btn-secondary" data-dismiss="modal"><?php echo get_phrase('cancel'); ?></button>
            </div>
        </div>
    </div>
</div>
<!-- Second Confirmation Modal -->
<div id="finalConfirmationModal" class="modal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><?php echo get_phrase('final_confirm_restore'); ?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p><?php echo get_phrase('this_is_your_final_chance_to_cancel_the_restore_process.'); ?></p>
                <p><?php echo get_phrase('are_you_absolutely_sure_you_want_to_proceed.?'); ?></p>
            </div>
            <div class="modal-footer">
                <button type="button" id="finalConfirmRestoreButton" class="btn btn-danger"><?php echo get_phrase('yes_final_restore'); ?></button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal"><?php echo get_phrase('cancel'); ?></button>
            </div>
        </div>
    </div>
</div>
<div id="noFileModal" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Attention</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Please select an SQL file to restore.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-dismiss="modal">OK</button>
            </div>
        </div>
    </div>
</div>

<!-- Rest of your HTML remains unchanged -->


<style>
    h2 {
        margin-bottom: 10px;
        color: #333;
    }
    p.note {
        background: #e9ecef;
        padding: 10px;
        border-left: 4px solid #007bff;
        border-radius: 5px;
        margin-bottom: 20px;
        color: #333;
    }
    .btn:hover {
        background: #0056b3;
    }
    .upload-box {
        position: relative;
        width: 100%;
        height: 50px;
        border: 2px dashed #007bff;
        border-radius: 5px;
        margin: 20px 0;
        cursor: pointer;
        display: flex;
        justify-content: center;
        align-items: center;
        background: #fff;
    }
    .upload-box input[type="file"] {
        position: absolute;
        width: 100%;
        height: 100%;
        opacity: 0;
        cursor: pointer;
    }
    .upload-box span {
        font-size: 16px;
        color: #007bff;
    }
</style>

<script>
    // Function to update file name display
    function updateFileName(input) {
        const fileName = input.files[0].name;
        const uploadBox = input.parentElement;
        const span = uploadBox.querySelector('span');
        span.textContent = fileName;
    }

// Function to handle restore button click
document.getElementById('restoreButton').addEventListener('click', function() {
    const fileInput = document.getElementById('sqlFile');
    const fileName = fileInput.value;

    // Check if a file has been selected
    if (!fileName) {
        // Display an alert or modal indicating that a file needs to be selected
        $('#noFileModal').modal('show');
        return; // Exit the function early to prevent further execution
    }

    // Display first confirmation modal
    $('#confirmationModal').modal('show');
        });

        // Function to handle confirm restore button click (first confirmation)
        document.getElementById('confirmRestoreButton').addEventListener('click', function() {
            // Hide first confirmation modal
            $('#confirmationModal').modal('hide');

            // Display final confirmation modal
            $('#finalConfirmationModal').modal('show');
        });

        // Function to handle final confirm restore button click
        document.getElementById('finalConfirmRestoreButton').addEventListener('click', function() {
            // Submit the form
            document.getElementById('restoreForm').submit();
        });

        // Function to handle cancel restore button click
        document.getElementById('cancelRestoreButton').addEventListener('click', function() {
            // Hide the confirmation modal
            $('#confirmationModal').modal('hide');
        });

  
    // Function to handle backup button click
    document.querySelector('.btn-secondary.btn-block').addEventListener('click', function(event) {
        event.preventDefault();  // Prevent the default action

        // Display confirmation modal
        $('#backupConfirmationModal').modal('show');
    });

    // Function to handle confirm backup button click
    document.getElementById('confirmBackupButton').addEventListener('click', function() {
        // Redirect to backup URL
        window.location.href = "<?php echo site_url('updater/backup_database'); ?>";
    });

    // Function to handle cancel backup button click
    document.getElementById('cancelBackupButton').addEventListener('click', function() {
        // Hide the confirmation modal
        $('#backupConfirmationModal').modal('hide');
    });

</script>
