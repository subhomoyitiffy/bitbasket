<?php
use Illuminate\Support\Facades\Route;;
$routeName    = Route::current();
$pageName     = explode("/", $routeName->uri());
$pageSegment  = $pageName[0];
$pageFunction = ((count($pageName)>1)?$pageName[1]:'');
?>
<!DOCTYPE html>
<html lang="en" class="dark-style layout-navbar-fixed layout-menu-fixed layout-compact " dir="ltr" data-theme="theme-default" data-assets-path="<?=env('ADMIN_ASSETS_URL')?>assets/" data-template="vertical-menu-template-dark">
   <head>
      <?=$head?>
   </head>
   <body>
      <!-- Layout wrapper -->
      <div class="layout-wrapper layout-content-navbar">
         <div class="layout-container">
            <!-- Menu -->
            <aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
               <?=$sidebar?>
            </aside>
            <!-- / Menu -->
            <!-- Layout container -->
            <div class="layout-page">
               <!-- Navbar -->
               <nav class="layout-navbar navbar navbar-expand-xl align-items-center bg-navbar-theme" id="layout-navbar">
                  <?=$header?>
               </nav>
               <!-- / Navbar -->
               <!-- Content wrapper -->
               <div class="content-wrapper">
                  <!-- Content -->
                  <div class="container-xxl flex-grow-1 container-p-y">
                     <?=$maincontent?>
                  </div>
                  <!-- / Content -->
                  <!-- Footer -->
                  <footer class="content-footer footer bg-footer-theme">
                     <?=$footer?>
                  </footer>
                  <!-- / Footer -->
                  <!-- <div class="content-backdrop fade"></div> -->
               </div>
               <!-- Content wrapper -->
            </div>
            <!-- / Layout page -->
         </div>
         <!-- Overlay -->
         <div class="layout-overlay layout-menu-toggle"></div>
         <!-- Drag Target Area To SlideIn Menu On Small Screens -->
         <div class="drag-target"></div>
      </div>
      <!-- / Layout wrapper -->
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
      <script src="<?=env('ADMIN_ASSETS_URL')?>assets/vendor/libs/apex-charts/apexcharts.js"></script>
      <!-- Main JS -->
      <script src="<?=env('ADMIN_ASSETS_URL')?>assets/js/main.js"></script>
      <!-- Page JS -->
      <script src="<?=env('ADMIN_ASSETS_URL')?>assets/js/dashboards-analytics.js"></script>
   </body>
</html>