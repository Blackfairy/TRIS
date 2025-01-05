<?php
	$status_wise_manuscripts = $this->crud_model->get_status_wise_manuscripts();
 ?>
<!-- ========== Left Sidebar Start ========== -->
<div class="left-side-menu left-side-menu-detached">
	<div class="leftbar-user">
		<a href="javascript: void(0);">
			<img src="<?php echo $this->user_model->get_user_image_url($this->session->userdata('user_id')); ?>" alt="user-image" height="42" class="rounded-circle shadow-sm">
			<?php
			$admin_details = $this->user_model->get_all_user($this->session->userdata('user_id'))->row_array();
			?>
			<span class="leftbar-user-name"><?php echo $admin_details['first_name'].' '.$admin_details['last_name']; ?></span>
		</a>
	</div>

	<!--- Sidemenu -->
		<ul class="metismenu side-nav side-nav-light">

			<li class="side-nav-title side-nav-item"><?php echo get_phrase('navigation'); ?></li>

			<li class="side-nav-item <?php if ($page_name == 'dashboard')echo 'active';?>">
				<a href="<?php echo site_url('admin/dashboard'); ?>" class="side-nav-link">
					<i class="dripicons-view-apps"></i>
					<span><?php echo get_phrase('dashboard'); ?></span>
				</a>
			</li>

			<li class="side-nav-item <?php if ($page_name == 'categories' || $page_name == 'category_add' || $page_name == 'category_edit' ): ?> active <?php endif; ?>">
				<a href="javascript: void(0);" class="side-nav-link <?php if ($page_name == 'categories' || $page_name == 'category_add' || $page_name == 'category_edit' ): ?> active <?php endif; ?>">
					<i class="dripicons-network-1"></i>
					<span> <?php echo get_phrase('categories'); ?> </span>
					<span class="menu-arrow"></span>
				</a>
				<ul class="side-nav-second-level" aria-expanded="false">
					<li class = "<?php if($page_name == 'categories' || $page_name == 'category_edit') echo 'active'; ?>">
						<a href="<?php echo site_url('admin/categories'); ?>"><?php echo get_phrase('categories'); ?></a>
					</li>

					<li class = "<?php if($page_name == 'category_add') echo 'active'; ?>">
						<a href="<?php echo site_url('admin/category_form/add_category'); ?>"><?php echo get_phrase('add_new_category'); ?></a>
					</li>
				</ul>
			</li>

			<li class="side-nav-item">
				<a href="<?php echo site_url('admin/manuscripts'); ?>" class="side-nav-link <?php if ($page_name == 'manuscripts' || $page_name == 'manuscript_add' || $page_name == 'manuscript_edit')echo 'active';?>">
					<i class="dripicons-archive"></i>
					<span><?php echo get_phrase('manuscripts'); ?></span>
				</a>
			</li>
			<li class="side-nav-item">
				<a href="<?php echo site_url('admin/users'); ?>" class="side-nav-link <?php if ($page_name == 'users' || $page_name == 'user_add' || $page_name == 'user_edit')echo 'active';?>">
					<i class="dripicons-user-group"></i>
					<span><?php echo get_phrase('students'); ?></span>
				</a>
			</li>
			<?php if($admin_details['email'] == 'superadmin@gmail.com'): ?>
			<li class="side-nav-item">
				<a href="<?php echo site_url('admin/admin'); ?>" class="side-nav-link <?php if ($page_name == 'users' || $page_name == 'user_add' || $page_name == 'user_edit')echo 'active';?>">
					<i class="dripicons-user-group"></i>
					<span><?php echo get_phrase('users'); ?></span>
				</a>
			</li>
			<?php endif; ?>

			<li class="side-nav-item <?php if ($page_name == 'accessed_history' || $page_name == 'access_student'): ?> active <?php endif; ?>">
				<a href="javascript: void(0);" class="side-nav-link <?php if ($page_name == 'enrol_history' || $page_name == 'enrol_student'): ?> active <?php endif; ?>">
					<i class="dripicons-network-3"></i>
					<span> <?php echo get_phrase('User Logs'); ?> </span>
					<span class="menu-arrow"></span>
				</a>
				<ul class="side-nav-second-level" aria-expanded="false">
					<li class = "<?php if($page_name == 'accessed_history') echo 'active'; ?>">
						<a href="<?php echo site_url('admin/enrol_history'); ?>"><?php echo get_phrase('accessed_history'); ?></a>
					</li>
					<li class = "<?php if($page_name == 'enrol_student') echo 'active'; ?>">
						<a href="<?php echo site_url('admin/enrol_student'); ?>"><?php echo get_phrase('student_permit_to_ access'); ?></a>
					</li>

				</ul>
			</li>
			<li class="side-nav-item">
				<a href="javascript: void(0);" class="side-nav-link <?php if ($page_name == 'admin_revenue' || $page_name == 'researcher_revenue' || $page_name == 'invoice'): ?> active <?php endif; ?>">
					<i class="dripicons-box"></i>
					<span> <?php echo get_phrase('report'); ?> </span>
					<span class="menu-arrow"></span>
				</a>
				<ul class="side-nav-second-level" aria-expanded="false">
					<li class = "<?php if($page_name == 'admin_revenue') echo 'active'; ?>" > <a href="<?php echo site_url('admin/admin_revenue'); ?>"><?php echo get_phrase('admin_revenue'); ?></a> </li>
					<?php if (get_settings('allow_researcher') == 1): ?>
							<li class = "<?php if($page_name == 'researcher_revenue') echo 'active'; ?>" >
									<a href="<?php echo site_url('admin/researcher_revenue'); ?>">
											<?php echo get_phrase('researcher_revenue');?> <span class = "badge badge-danger-lighten badge-pill"><?php echo $this->db->get_where('payment', array('researcher_payment_status' => 0))->num_rows() ?></span>
									</a>
							</li>
					<?php endif; ?>
				</ul>
			</li>

			<li class="side-nav-item">
				<a href="<?php echo site_url('admin/message'); ?>" class="side-nav-link <?php if ($page_name == 'message' || $page_name == 'message_new' || $page_name == 'message_read')echo 'active';?>">
					<i class="dripicons-message"></i>
					<span><?php echo get_phrase('message'); ?></span>
				</a>
			</li>
			<?php if($admin_details['email'] == 'superadmin@gmail.com'): ?>
			<li class="side-nav-item">
			<a href="javascript: void(0);" class="side-nav-link <?php if ($page_name == 'system_settings' || $page_name == 'frontend_settings' || $page_name == 'payment_settings' || $page_name == 'researcher_settings' || $page_name == 'smtp_settings' || $page_name == 'manage_language' || $page_name == 'about' || $page_name == 'themes' || $page_name == 'mobile_app' ): ?> active <?php endif; ?>">
				<i class="dripicons-toggles"></i>
				<span> <?php echo get_phrase('settings'); ?> </span>
				<span class="menu-arrow"></span>
			</a>
			<ul class="side-nav-second-level" aria-expanded="false">
				<li class = "<?php if($page_name == 'system_settings') echo 'active'; ?>">
					<a href="<?php echo site_url('admin/system_settings'); ?>"><?php echo get_phrase('system_settings'); ?></a>
				</li>
				<li class = "<?php if($page_name == 'frontend_settings') echo 'active'; ?>">
					<a href="<?php echo site_url('admin/frontend_settings'); ?>"><?php echo get_phrase('website_settings'); ?></a>
				</li>
				<li class = "<?php if($page_name == 'researcher_settings') echo 'active'; ?>">
					<a href="<?php echo site_url('admin/researcher_settings'); ?>"><?php echo get_phrase('researcher_settings'); ?></a>
				</li>
				<li class = "<?php if($page_name == 'smtp_settings') echo 'active'; ?>">
					<a href="<?php echo site_url('admin/smtp_settings'); ?>"><?php echo get_phrase('smtp_settings'); ?></a>
				</li>
				<li class = "<?php if($page_name == 'payment_settings') echo 'active'; ?>">
					<a href="<?php echo site_url('admin/payment_settings'); ?>"><?php echo get_phrase('payment_settings'); ?></a>
				</li>
				
			</ul>
		</li>
		<?php endif; ?>
	    </ul>
</div>
