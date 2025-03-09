<!-- Topbar Start -->
<div class="navbar-custom topnav-navbar topnav-navbar-dark" style="z-index: 99 !important; background-color: #111c4e !important;">
    <div class="container-fluid">

        <!-- LOGO -->
        <a href="<?php echo site_url($this->session->userdata('role')); ?>" class="topnav-logo" style = "min-width: unset;">
            <span class="topnav-logo-lg">
                <img src="<?php echo base_url('uploads/system/logo-light.png');?>" alt="" height="40">
            </span>
            <span class="topnav-logo-sm">
                <img src="<?php echo base_url('uploads/system/logo-light-sm.png');?>" alt="" height="40">
            </span>
        </a>

        <ul class="list-unstyled topbar-right-menu float-right mb-0">

            <!-- Dark Mode Toggle Button -->
            <li class="dropdown notification-list">
                <a class="nav-link dropdown-toggle nav-user arrow-none mr-0" id="dark-mode-toggle" href="#" role="button" aria-haspopup="true" aria-expanded="false" style=" background-color: #111c4e !important;">
                <span class="account-user-avatar">
                    <img src="<?php echo base_url('uploads/thumbnails/night-mode.png'); ?>" alt="Toggle Dark Mode" class="rounded-circle" height="40">
                </span>
                <span  style="color: #fff;">
                    <span class="account-user-name">Dark Mode</span>
                </span>
            </a>
            </li>

            <li class="dropdown notification-list">
                <a class="nav-link dropdown-toggle nav-user arrow-none mr-0" data-toggle="dropdown" id="topbar-userdrop"
                href="#" role="button" aria-haspopup="true" aria-expanded="false" style=" background-color: #111c4e !important;">
                <span class="account-user-avatar">
                    <img src="<?php echo $this->user_model->get_user_image_url($this->session->userdata('user_id')); ?>" alt="user-image" class="rounded-circle">
                </span>
                <span  style="color: #fff;">
                    <?php
                    $logged_in_user_details = $this->user_model->get_all_user($this->session->userdata('user_id'))->row_array();;
                    ?>
                    <span class="account-user-name"><?php echo $logged_in_user_details['first_name'].' '.$logged_in_user_details['last_name'];?></span>
                    <span class="account-position"><?php echo strtolower($this->session->userdata('role')) == 'user' ? get_phrase('researcher') : get_phrase('admin'); ?></span>
                </span>
            </a>
            <div class="dropdown-menu dropdown-menu-right dropdown-menu-animated topbar-dropdown-menu profile-dropdown"
            aria-labelledby="topbar-userdrop">
            <!-- item-->
            <div class=" dropdown-header noti-title">
                <h6 class="text-overflow m-0"><?php echo get_phrase('welcome'); ?> !</h6>
            </div>

            <!-- Account -->
            <a href="<?php echo site_url(strtolower($this->session->userdata('role')).'/manage_profile'); ?>" class="dropdown-item notify-item">
                <i class="mdi mdi-account-circle mr-1"></i>
                <span><?php echo get_phrase('my_account'); ?></span>
            </a>

            <?php if (strtolower($this->session->userdata('role')) == 'admin'): ?>
                <!-- settings-->
                <a href="<?php echo site_url('admin/system_settings'); ?>" class="dropdown-item notify-item">
                    <i class="mdi mdi-settings mr-1"></i>
                    <span><?php echo get_phrase('settings'); ?></span>
                </a>

            <?php endif; ?>


            <!-- Logout-->
            <a href="<?php echo site_url('login/logout'); ?>" class="dropdown-item notify-item">
                <i class="mdi mdi-logout mr-1"></i>
                <span><?php echo get_phrase('logout'); ?></span>
            </a>

        </div>
    </li>

</ul>
<a class="button-menu-mobile disable-btn">
    <div class="lines">
        <span></span>
        <span></span>
        <span></span>
    </div>
</a>
<div class="app-search">
    <h4 style="color: #fff; float: left;"> <?php echo $this->db->get_where('settings' , array('key'=>'system_name'))->row()->value; ?></h4>
    <a href="<?php echo site_url('home'); ?>" target="" class="btn btn-outline-light ml-3"><?php echo get_phrase('visit_website'); ?></a>
</div>
</div>
</div>
<!-- end Topbar -->
<style>
/* Add your dark mode styles here */
body.dark-mode {
    background-color: black;
}
.card.dark-mode {
    background-color: black;
}
.left-side-menu-detached.dark-mode {
    background-color: black;
}    
.list-group-item.dark-mode {
    background-color: black;
} 
.select2-container .select2-selection--single.dark-mode {
    background-color: black;
}   
.table-striped tbody tr:nth-of-type(odd).dark-mode {
    background-color: black;
}   
.form-control.dark-mode {
    background-color: black;
} 
p.note.dark-mode {
    background-color: black;
} 
</style>
<script>
document.addEventListener('DOMContentLoaded', function() {
    if (localStorage.getItem('dark-mode') === 'enabled') {
        document.body.classList.add('dark-mode');
        var cards = document.querySelectorAll('.card');
        cards.forEach(function(card) {
            card.classList.add('dark-mode');
        });
        var leftSideMenus = document.querySelectorAll('.left-side-menu-detached');
        leftSideMenus.forEach(function(menu) {
            menu.classList.add('dark-mode');
        });
        var listGroupItems = document.querySelectorAll('.list-group-item');
        listGroupItems.forEach(function(item) {
            item.classList.add('dark-mode');
        });
        var select2Containers = document.querySelectorAll('.select2-container .select2-selection--single');
        select2Containers.forEach(function(container) {
            container.classList.add('dark-mode');
        });
        var tableRows = document.querySelectorAll('.table-striped tbody tr:nth-of-type(odd)');
        tableRows.forEach(function(row) {
            row.classList.add('dark-mode');
        });
        var formControls = document.querySelectorAll('.form-control');
        formControls.forEach(function(control) {
            control.classList.add('dark-mode');
        });
        var notes = document.querySelectorAll('p.note');
        notes.forEach(function(note) {
            note.classList.add('dark-mode');
        });
    }

    document.getElementById('dark-mode-toggle').addEventListener('click', function() {
        document.body.classList.toggle('dark-mode');
        var cards = document.querySelectorAll('.card');
        cards.forEach(function(card) {
            card.classList.toggle('dark-mode');
        });
        var leftSideMenus = document.querySelectorAll('.left-side-menu-detached');
        leftSideMenus.forEach(function(menu) {
            menu.classList.toggle('dark-mode');
        });
        var listGroupItems = document.querySelectorAll('.list-group-item');
        listGroupItems.forEach(function(item) {
            item.classList.toggle('dark-mode');
        });
        var select2Containers = document.querySelectorAll('.select2-container .select2-selection--single');
        select2Containers.forEach(function(container) {
            container.classList.toggle('dark-mode');
        });
        var tableRows = document.querySelectorAll('.table-striped tbody tr:nth-of-type(odd)');
        tableRows.forEach(function(row) {
            row.classList.toggle('dark-mode');
        });
        var formControls = document.querySelectorAll('.form-control');
        formControls.forEach(function(control) {
            control.classList.toggle('dark-mode');
        });
        var notes = document.querySelectorAll('p.note');
        notes.forEach(function(note) {
            note.classList.toggle('dark-mode');
        });

        if (document.body.classList.contains('dark-mode')) {
            localStorage.setItem('dark-mode', 'enabled');
        } else {
            localStorage.setItem('dark-mode', 'disabled');
        }
    });
});
</script>