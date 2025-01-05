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
            <img src="<?php echo base_url().'uploads/system/logo-dark.png'; ?>" alt="" height="35">
          </a>

          <!-- Include Main Menu -->
          <?php include 'menu.php'; ?>

          <!-- Search Form -->
          <form class="inline-form" action="<?php echo site_url('home/search'); ?>" method="get" style="width: 55%;">
            <div class="input-group search-box mobile-search">
              <input type="text" name='query' class="form-control" placeholder="<?php echo get_phrase('search_for_manuscripts'); ?>">
              <div class="input-group-append">
                <button class="btn" type="submit"><i class="fas fa-search"></i></button>
              </div>
            </div>
          </form>

          <!-- Researcher Box for Admin Users -->
          <?php if ($this->session->userdata('admin_login')): ?>
              <div class="researcher-box menu-icon-box" style="padding-right: 8rem !important; padding-right: 5rem !important;">
                  <div class="icon">
                      <a href="<?php echo site_url('admin'); ?>" class="btn-researcher" style="font-size: 1.2rem;"><?php echo get_phrase('administrator'); ?></a>
                  </div>
              </div>
          <?php else: ?>
              <!-- Join for Free Box -->
              <div class="join-box menu-icon-box" id="join_free">
                  <a href="<?php echo site_url('home/sign_up'); ?>" class="btn btn-sign-up" style="padding: 10px 20px; font-size: 14px; text-transform: uppercase; background-color: #fff; border-color: black; color: black; border-radius: 5px; text-align: center; display: inline-block;">
                      <?php echo get_phrase('Join for Free'); ?>
                  </a>
              </div>
          <?php endif; ?>



          <!-- Mobile Search Container -->
          <div class="mobile-search-container">
            <a class="mobile-search-trigger" href="#mobile-search"><span></span></a>
          </div>

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

<style>
  /* Hide mobile search container on screens larger than 900px */
  @media (min-width: 900px) {
    .mobile-search-container {
      display: none !important;
    }
  }
</style>
