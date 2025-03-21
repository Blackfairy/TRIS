<?php
	$status_wise_manuscripts = $this->crud_model->get_status_wise_manuscripts();
 ?>
<!-- ========== Left Sidebar Start ========== -->
<div class="left-side-menu left-side-menu-detached">
	<div class="leftbar-user">
		<a href="javascript: void(0);">
			<img src="<?php echo $this->user_model->get_user_image_url($this->session->userdata('user_id')); ?>" alt="user-image" height="42" class="rounded-circle shadow-sm">
			<?php
			$user_details = $this->user_model->get_all_user($this->session->userdata('user_id'))->row_array();
			?>
			<span class="leftbar-user-name"><?php echo $user_details['first_name'].' '.$user_details['last_name']; ?></span>
		</a>
	</div>

	<!--- Sidemenu -->
		<ul class="metismenu side-nav side-nav-light">

			<li class="side-nav-title side-nav-item"><?php echo get_phrase('navigation'); ?></li>

			<li class="side-nav-item">
				<a href="<?php echo site_url('user/manuscripts'); ?>" class="side-nav-link <?php if ($page_name == 'manuscripts' || $page_name == 'manuscript_add' || $page_name == 'manuscript_edit')echo 'active';?>">
					<i class="dripicons-archive"></i>
					<span><?php echo get_phrase('research'); ?></span>
				</a>
			</li>
			<li class="side-nav-item">
				<a href="<?php echo site_url('user/researcher_revenue'); ?>" class="side-nav-link <?php if ($page_name == 'report' || $page_name == 'invoice')echo 'active';?>">
					<i class="dripicons-media-shuffle"></i>
					<span><?php echo get_phrase('researcher_revenue'); ?></span>
				</a>
			</li>

			<li class="side-nav-item">
			<a href="javascript: void(0);" class="side-nav-link <?php if ($page_name == 'system_settings' || $page_name == 'frontend_settings' || $page_name == 'payment_settings' || $page_name == 'researcher_settings' || $page_name == 'smtp_settings' || $page_name == 'manage_language' ): ?> active <?php endif; ?>">
				<i class="dripicons-toggles"></i>
				<span> <?php echo get_phrase('settings'); ?> </span>
				<span class="menu-arrow"></span>
			</a>
			<ul class="side-nav-second-level" aria-expanded="false">
				<li class = "<?php if($page_name == 'payment_settings') echo 'active'; ?>">
					<a href="<?php echo site_url('user/payment_settings'); ?>"><?php echo get_phrase('payment_settings'); ?></a>
				</li>
			</ul>
		</li>
	    </ul>
</div>
<style>
@media (max-width: 767.98px) {
    body[data-layout=detached].sidebar-enable .left-side-menu {
        position: fixed;
        padding-top: 70px !important;
        left: 0;
        overflow-y: auto;
    }
    .left-side-menu.left-side-menu-detached {
        padding-top: 70px;
    }
}
</style>