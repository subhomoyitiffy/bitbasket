<?php
use App\Helpers\Helper;
?>
<!DOCTYPE html>
<html lang="en" class="dark-style layout-wide customizer-hide" dir="ltr" data-theme="theme-default" data-assets-path="<?=env('ADMIN_ASSETS_URL')?>assets/" data-template="vertical-menu-template-dark">
   <head>
      <?=$head?>
   </head>
   <body>
      <!-- Content -->
      <?=$maincontent?>
      <!-- / Content -->
      <!-- Core JS -->
      <!-- build:js assets/vendor/js/core.js -->
      <script src="<?=env('ADMIN_ASSETS_URL')?>assets/vendor/libs/jquery/jquery.js"></script>
      <script src="<?=env('ADMIN_ASSETS_URL')?>assets/vendor/libs/popper/popper.js"></script>
      <script src="<?=env('ADMIN_ASSETS_URL')?>assets/vendor/js/bootstrap.js"></script>
      <script src="<?=env('ADMIN_ASSETS_URL')?>assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js"></script>
      <script src="<?=env('ADMIN_ASSETS_URL')?>assets/vendor/libs/hammer/hammer.js"></script>
      <script src="<?=env('ADMIN_ASSETS_URL')?>assets/vendor/libs/i18n/i18n.js"></script>
      <script src="<?=env('ADMIN_ASSETS_URL')?>assets/vendor/libs/typeahead-js/typeahead.js"></script>
      <script src="<?=env('ADMIN_ASSETS_URL')?>assets/vendor/js/menu.js"></script>
      <!-- endbuild -->
      <!-- Vendors JS -->
      <script src="<?=env('ADMIN_ASSETS_URL')?>assets/vendor/libs/cleavejs/cleave.js"></script>
      <script src="<?=env('ADMIN_ASSETS_URL')?>assets/vendor/libs/%40form-validation/umd/bundle/popular.min.js"></script>
      <script src="<?=env('ADMIN_ASSETS_URL')?>assets/vendor/libs/%40form-validation/umd/plugin-bootstrap5/index.min.js"></script>
      <script src="<?=env('ADMIN_ASSETS_URL')?>assets/vendor/libs/%40form-validation/umd/plugin-auto-focus/index.min.js"></script>
      <!-- Main JS -->
      <script src="<?=env('ADMIN_ASSETS_URL')?>assets/js/main.js"></script>
      <!-- Page JS -->
      <script src="<?=env('ADMIN_ASSETS_URL')?>assets/js/pages-auth.js"></script>
      <script src="<?=env('ADMIN_ASSETS_URL')?>assets/js/pages-auth-two-steps.js"></script>
      <script type="text/javascript">
         $(function(){
            $('.autohide').delay(5000).fadeOut('slow');
         });
      </script>
   </body>
</html>