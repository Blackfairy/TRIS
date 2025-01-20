<div class="row ">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-body">
                <h4 class="page-title"> <i class="mdi mdi-apple-keyboard-command title_icon"></i> <?php echo get_phrase('manuscripts'); ?>
                    <a href="<?php echo site_url('user/manuscript_form/add_manuscript'); ?>" class="btn btn-outline-primary btn-rounded alignToTitle"><i class="mdi mdi-plus"></i><?php echo get_phrase('add_new_manuscript'); ?></a>
                    <a href="<?php echo site_url('user/manuscripts'); ?>" class="btn btn-outline-success btn-rounded alignToTitle">
                        <i class="mdi mdi-file-excel"></i> Export to Excel
                    </a>
                </h4>
            </div> <!-- end card body-->
        </div> <!-- end card -->
    </div><!-- end col-->
</div>
<div class="row">
    <div class="col-12">
        <div class="card widget-inline">
            <div class="card-body p-0">
                <div class="row no-gutters">
                    <div class="col">
                        <a href="<?php echo site_url('user/manuscripts'); ?>" class="text-secondary">
                            <div class="card shadow-none m-0">
                                <div class="card-body text-center">
                                    <i class="dripicons-link text-muted" style="font-size: 24px;"></i>
                                    <h3><span>
                                        <?php
                                            $active_manuscripts = $this->crud_model->get_status_wise_manuscripts_for_researcher('active');
                                            echo $active_manuscripts->num_rows();
                                         ?>
                                    </span></h3>
                                    <p class="text-muted font-15 mb-0"><?php echo get_phrase('active_manuscripts'); ?></p>
                                </div>
                            </div>
                        </a>
                    </div>

                    <div class="col">
                        <a href="<?php echo site_url('user/manuscripts'); ?>" class="text-secondary">
                            <div class="card shadow-none m-0 border-left">
                                <div class="card-body text-center">
                                    <i class="dripicons-link-broken text-muted" style="font-size: 24px;"></i>
                                    <h3><span>
                                        <?php
                                            $pending_manuscripts = $this->crud_model->get_status_wise_manuscripts_for_researcher('pending');
                                            echo $pending_manuscripts->num_rows();
                                         ?>
                                    </span></h3>
                                    <p class="text-muted font-15 mb-0"><?php echo get_phrase('pending_manuscripts'); ?></p>
                                </div>
                            </div>
                        </a>
                    </div>

                    <div class="col">
                        <a href="<?php echo site_url('user/manuscripts'); ?>" class="text-secondary">
                            <div class="card shadow-none m-0 border-left">
                                <div class="card-body text-center">
                                    <i class="dripicons-bookmark text-muted" style="font-size: 24px;"></i>
                                    <h3><span>
                                        <?php
                                            $draft_manuscripts = $this->crud_model->get_status_wise_manuscripts_for_researcher('draft');
                                            echo $draft_manuscripts->num_rows();
                                         ?>
                                    </span></h3>
                                    <p class="text-muted font-15 mb-0"><?php echo get_phrase('draft_manuscripts'); ?></p>
                                </div>
                            </div>
                        </a>
                    </div>

                    <div class="col">
                        <a href="<?php echo site_url('user/manuscripts'); ?>" class="text-secondary">
                            <div class="card shadow-none m-0 border-left">
                                <div class="card-body text-center">
                                    <i class="dripicons-star text-muted" style="font-size: 24px;"></i>
                                    <h3><span><?php echo $this->crud_model->get_free_and_paid_manuscripts('free', $this->session->userdata('user_id'))->num_rows(); ?></span></h3>
                                    <p class="text-muted font-15 mb-0"><?php echo get_phrase('free_manuscripts'); ?></p>
                                </div>
                            </div>
                        </a>
                    </div>

                    <div class="col">
                        <a href="<?php echo site_url('user/manuscripts'); ?>" class="text-secondary">
                            <div class="card shadow-none m-0 border-left">
                                <div class="card-body text-center">
                                    <i class="dripicons-tags text-muted" style="font-size: 24px;"></i>
                                    <h3><span><?php echo $this->crud_model->get_free_and_paid_manuscripts('paid', $this->session->userdata('user_id'))->num_rows(); ?></span></h3>
                                    <p class="text-muted font-15 mb-0"><?php echo get_phrase('paid_manuscripts'); ?></p>
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
    <div class="col-xl-12">
        <div class="card">
            <div class="card-body">
                <h4 class="mb-3 header-title"><?php echo get_phrase('manuscript_list'); ?></h4>
                <form class="row justify-content-center" action="<?php echo site_url('user/manuscripts'); ?>" method="get">
                    <!-- Manuscript Categories -->
                    <div class="col-xl-3">
                        <div class="form-group">
                            <label for="category_id"><?php echo get_phrase('categories'); ?></label>
                            <select class="form-control select2" data-toggle="select2" name="category_id" id="category_id">
                                <option value="<?php echo 'all'; ?>" <?php if($selected_category_id == 'all') echo 'selected'; ?>><?php echo get_phrase('all'); ?></option>
                                <?php foreach ($categories->result_array() as $category): ?>
                                    <optgroup label="<?php echo $category['name']; ?>">
                                        <?php $sub_categories = $this->crud_model->get_sub_categories($category['id']);
                                        foreach ($sub_categories as $sub_category): ?>
                                        <option value="<?php echo $sub_category['id']; ?>" <?php if($selected_category_id == $sub_category['id']) echo 'selected'; ?>><?php echo $sub_category['name']; ?></option>
                                    <?php endforeach; ?>
                                </optgroup>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <!-- Manuscript Status -->
                <div class="col-xl-3">
                    <div class="form-group">
                        <label for="status"><?php echo get_phrase('status'); ?></label>
                        <select class="form-control select2" data-toggle="select2" name="status" id = 'status'>
                            <option value="all" <?php if($selected_status == 'all') echo 'selected'; ?>><?php echo get_phrase('all'); ?></option>
                            <option value="active" <?php if($selected_status == 'active') echo 'selected'; ?>><?php echo get_phrase('active'); ?></option>
                            <option value="pending" <?php if($selected_status == 'pending') echo 'selected'; ?>><?php echo get_phrase('pending'); ?></option>
                        </select>
                    </div>
                </div>

                <!-- Manuscript Price -->
                <div class="col-xl-3">
                    <div class="form-group">
                        <label for="price"><?php echo get_phrase('price'); ?></label>
                        <select class="form-control select2" data-toggle="select2" name="price" id = 'price'>
                            <option value="all"  <?php if($selected_price == 'all' ) echo 'selected'; ?>><?php echo get_phrase('all'); ?></option>
                            <option value="free" <?php if($selected_price == 'free') echo 'selected'; ?>><?php echo get_phrase('free'); ?></option>
                            <option value="paid" <?php if($selected_price == 'paid') echo 'selected'; ?>><?php echo get_phrase('paid'); ?></option>
                        </select>
                    </div>
                </div>

                <div class="col-xl-3">
                    <label for=".." class="text-white"><?php echo get_phrase('..'); ?></label>
                    <button type="submit" class="btn btn-primary btn-block" name="button"><?php echo get_phrase('filter'); ?></button>
                </div>
            </form>

            <div class="table-responsive-sm mt-4">
                <?php if (count($manuscripts) > 0): ?>
                    <table id="manuscript-datatable" class="table table-striped dt-responsive nowrap" width="100%" data-page-length='25'>
                        <thead>
                            <tr>
                                <th>#</th>
                                <th><?php echo get_phrase('title'); ?></th>
                                <th><?php echo get_phrase('authors'); ?></th>
                                <th><?php echo get_phrase('advisers'); ?></th>
                                <th><?php echo get_phrase('date_accomplished'); ?></th>
                                <th><?php echo get_phrase('company_name'); ?></th>
                                <th><?php echo get_phrase('category'); ?></th>
                                <th><?php echo get_phrase('accessed_students'); ?></th>
                                <th><?php echo get_phrase('status'); ?></th>
                                <th><?php echo get_phrase('price'); ?></th>
                                <th><?php echo get_phrase('actions'); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($manuscripts as $key => $manuscript):
                                $researcher_details = $this->user_model->get_all_user($manuscript['user_id'])->row_array();
                                $category_details = $this->crud_model->get_category_details_by_id($manuscript['sub_category_id'])->row_array();
                                $sections = $this->crud_model->get_section('manuscript', $manuscript['id']);
                                $lessons = $this->crud_model->get_lessons('manuscript', $manuscript['id']);
                                $enroll_history = $this->crud_model->enrol_history($manuscript['id']);
                                $outcomes = json_decode($manuscript['outcomes'], true); // Assuming outcomes are stored as JSON in the database
                                $authors = json_decode($manuscript['authors'], true); // Assuming authors are stored as JSON in the database
                                $company_name = $manuscript['company_name']; // Direct access to company name
                                if ($manuscript['status'] == 'draft') {
                                    continue;
                                }
                            ?>
                                <tr>
                                    <td><?php echo ++$key; ?></td>
                                    <td>
                                        <strong><a href="<?php echo site_url('user/manuscript_form/manuscript_edit/'.$manuscript['id']); ?>"><?php echo ellipsis($manuscript['title']); ?></a></strong><br>
                                        <small class="text-muted"><?php echo get_phrase('researcher').': <b>'.$researcher_details['first_name'].' '.$researcher_details['last_name'].'</b>'; ?></small>
                                    </td>
                                    <td>
                                        <?php if (is_array($authors) && !empty($authors)): ?>
                                            <small class="text-muted">
                                                <?php foreach ($authors as $author): ?>
                                                    <b><?php echo $author; ?></b><br>
                                                <?php endforeach; ?>
                                            </small>
                                        <?php else: ?>
                                            <small class="text-muted"><?php echo get_phrase('no_authors'); ?></small>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                    <?php
                                    $advisers = json_decode($manuscript['outcomes']);
                                    if (!empty($advisers)) {
                                        foreach ($advisers as $index => $adviser) {
                                            $title = '';
                                            if ($index == 0) {
                                                $title = get_phrase('Course Adviser');
                                            } elseif ($index == 1) {
                                                $title = get_phrase('Technical Adviser');
                                            }
                                            if ($title != '') {
                                                echo $title . ': ';
                                            }
                                            echo $adviser . '<br>';
                                        }
                                    } else {
                                        echo get_phrase('No advisers listed.') . '<br>';
                                    }
                                    ?>

                                    </td>
                                    <td>
                                        <small class="text-muted"><?php echo date('F j, Y', strtotime($manuscript['date_accomplished'])); ?></small>
                                    </td>
                                    <td>
                                        <small class="text-muted"><?php echo !empty($company_name) ? $company_name : get_phrase('no_company_name'); ?></small>
                                    </td>
                                    <td>
                                        <span class="badge badge-dark-lighten"><?php echo $category_details['name']; ?></span>
                                    </td>
                                    <td>
                                        <small class="text-muted"><?php echo '<b>'.get_phrase('total_accessed').'</b>: '.$enroll_history->num_rows(); ?></small>
                                    </td>
                                    <td class="text-center">
                                        <?php if ($manuscript['status'] == 'pending'): ?>
                                            <i class="mdi mdi-circle" style="color: #FFC107; font-size: 19px;" data-toggle="tooltip" data-placement="top" title="" data-original-title="<?php echo get_phrase($manuscript['status']); ?>"></i>
                                        <?php elseif ($manuscript['status'] == 'active'):?>
                                            <i class="mdi mdi-circle" style="color: #4CAF50; font-size: 19px;" data-toggle="tooltip" data-placement="top" title="" data-original-title="<?php echo get_phrase($manuscript['status']); ?>"></i>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if ($manuscript['is_free_manuscript'] == null): ?>
                                            <?php if ($manuscript['discount_flag'] == 1): ?>
                                                <span class="badge badge-dark-lighten"><?php echo currency($manuscript['discounted_price']); ?></span>
                                            <?php else: ?>
                                                <span class="badge badge-dark-lighten"><?php echo currency($manuscript['price']); ?></span>
                                            <?php endif; ?>
                                        <?php elseif ($manuscript['is_free_manuscript'] == 1):?>
                                            <span class="badge badge-success-lighten"><?php echo get_phrase('free'); ?></span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <div class="dropright dropright">
                                        <button type="button" class="btn btn-sm btn-outline-primary btn-rounded btn-icon" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i class="mdi mdi-dots-vertical"></i>
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li><a class="dropdown-item" href="<?php echo site_url('home/manuscript/'.slugify($manuscript['title']).'/'.$manuscript['id']); ?>" target="_blank"><?php echo get_phrase('view_manuscript_on_frontend');?></a></li>
                                            <li><a class="dropdown-item" href="<?php echo site_url('user/manuscript_form/manuscript_edit/'.$manuscript['id']); ?>"><?php echo get_phrase('edit_this_manuscript');?></a></li>
                                            <li><a class="dropdown-item" href="<?php echo site_url('user/manuscript_form/manuscript_edit/'.$manuscript['id']); ?>"><?php echo get_phrase('file');?></a></li>
                                            <li><a class="dropdown-item" href="#" onclick="confirm_modal('<?php echo site_url('user/manuscript_actions/delete/'.$manuscript['id']); ?>');"><?php echo get_phrase('delete'); ?></a></li>
                                        </ul>
                                    </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p><?php echo get_phrase('no_manuscripts_found'); ?></p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
</div>
