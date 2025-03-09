<section class="menu-area">
  <div class="container-xl">
    <div class="row">
      <div class="col">
        <nav class="navbar navbar-expand-lg navbar-light bg-light">

          <!-- Mobile Header Buttons (Menu & Search) -->
          <ul class="mobile-header-buttons">
            <li><a class="mobile-nav-trigger" href="#mobile-primary-nav">Menu<span></span></a></li>
          </ul>

          <!-- Navbar Brand (Logo) -->
          <a href="<?php echo site_url(''); ?>" class="navbar-brand" style="padding-right: 1rem !important;">
            <img src="<?php echo base_url().'uploads/system/logo-dark.png'; ?>" alt="" height="50">
          </a>
          <a href="<?php echo site_url(''); ?>" class="navbar-brand" style=" padding-right: 1rem !important; color: #fff !important; padding: 5px !important;   font-family: Roboto !important;">
            <?php echo get_phrase('university_of_makati'); ?>
          </a>
          <a href="<?php echo site_url(''); ?>" class="navbar-brand" style=" padding-right: 1rem !important; color: #fff !important; padding: 5px !important; font-family: Roboto !important;">
            <?php echo get_phrase('technology_based_research_hub'); ?>
          </a>
          <!-- Researcher Box for Admin Users -->
          <?php if ($this->session->userdata('admin_login')): ?>
              
              <div class="researcher-box menu-icon-box admin-box" style="padding-left: 0rem !important; padding-right: 0rem !important;">
                  <div class="icon">
                      <a href="<?php echo site_url('admin'); ?>" class="btn-researcher" style="font-size: 1.2rem;">
                          <?php echo get_phrase('administrator'); ?>
                      </a>
                  </div>
              </div>
          <?php endif; ?>

          <!-- Sign In & Sign Up Buttons -->
          <span class="signin-box-move-desktop-helper"></span>
          <div class="sign-in-box btn-group">
            <a href="<?php echo site_url('home/login'); ?>" class="btn btn-sign-in"><?php echo get_phrase('log_in'); ?></a>
            <a href="<?php echo site_url('home/sign_up'); ?>" class="btn btn-sign-up"><?php echo get_phrase('sign_up'); ?></a>
          </div>

        </nav>
      </div>
    </div>
  </div>
</section>

<!-- Include Main Menu -->
<?php include 'menu.php'; ?>

<!-- Inline CSS to hide menu by default and show only in mobile view -->
<style>
  .mobile-main-nav {
    display: none;
  }

  @media (max-width: 768px) {
    .mobile-main-nav {
      display: block;
    }
    .admin-box {
      display: none;
    }
  }
</style>

<!-- Search Form -->