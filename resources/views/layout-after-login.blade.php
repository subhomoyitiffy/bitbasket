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
      <style>
        .dt-buttons button{
          padding: 2px 20px;
          background-color: #FFF;
          color: #040273;
          border-radius: 50px;
          border:2px solid #FFF;
          transition: all .3s ease-in-out;
          box-shadow: 0 9px 20px -10px #a5a5a5;
        }
        .dt-buttons button:hover{
          background: transparent;
          color: #040273;
          border:2px solid #FFF;
        }
        .dataTables_wrapper .dataTables_top {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
        }
        .dataTables_wrapper .dataTables_buttons {
            flex: 1;
        }
        .dataTables_wrapper .dataTables_length {
            flex: 1;
            margin-left: 10px;
            margin-bottom: 10px;
            margin-top: 10px;
        }
        .dataTables_wrapper .dataTables_filter {
            flex: 1;
            text-align: right;
        }
        .dt-search{
          float: right;
          margin-bottom: 10px;
          margin-left: 10px;
          margin-top: 10px;
        }
        .dt-length{
          margin-top: 10px;
        }
        .dt-paging{
          float: right !important;
        }
        .pagination{
          justify-content: end;
        }
      </style>
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

      <script src="<?=env('ADMIN_ASSETS_URL')?>assets/js/data-basic-custom.js"></script>
      <link href="https://cdn.datatables.net/v/dt/dt-2.0.3/datatables.min.css" rel="stylesheet">
      <script src="https://cdn.datatables.net/v/dt/dt-2.0.3/datatables.min.js"></script>
      <script src="https://cdn.datatables.net/2.0.3/js/dataTables.js"></script>
      <script src="https://cdn.datatables.net/buttons/3.0.1/js/dataTables.buttons.js"></script>
      <script src="https://cdn.datatables.net/buttons/3.0.1/js/buttons.dataTables.js"></script>
      <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
      <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
      <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
      <script src="https://cdn.datatables.net/buttons/3.0.1/js/buttons.html5.min.js"></script>
      <script src="https://cdn.datatables.net/buttons/3.0.1/js/buttons.print.min.js"></script>

      <script type="text/javascript">
         $(function(){
            $('.autohide').delay(5000).fadeOut('slow');
         });
      </script>
      <link rel="stylesheet" href="https://cdn.ckeditor.com/ckeditor5/43.1.0/ckeditor5.css" />
      <script type="importmap">
        {
            "imports": {
                "ckeditor5": "https://cdn.ckeditor.com/ckeditor5/43.1.0/ckeditor5.js",
                "ckeditor5/": "https://cdn.ckeditor.com/ckeditor5/43.1.0/"
            }
        }
      </script>
      <script type="module">
        import {
            ClassicEditor,
            Essentials,
            Bold,
            Italic,
            Strikethrough,
            Subscript,
            Superscript,
            CodeBlock,
            Font,
            Link,
            List,
            Paragraph,
            Image,
            ImageCaption,
            ImageResize,
            ImageStyle,
            ImageToolbar,
            LinkImage,
            PictureEditing,
            ImageUpload,
            CloudServices,
            CKBox,
            CKBoxImageEdit,
            SourceEditing,
            ImageInsert
        } from 'ckeditor5';

        for (let i = 0; i <= 50; i++) {
          ClassicEditor
            .create( document.querySelector( '#ckeditor' + i ), {
              plugins: [ Essentials, Bold, Italic, Strikethrough, Subscript, Superscript, CodeBlock, Font, Link, List, Paragraph, Image, ImageToolbar, ImageCaption, ImageStyle, ImageResize, LinkImage, PictureEditing, ImageUpload, CloudServices, CKBox, CKBoxImageEdit, SourceEditing, ImageInsert ],
              toolbar: {
                items: [
                  'undo', 'redo',
                  '|',
                  'heading',
                  '|',
                  'sourceEditing',
                  '|',
                  'fontfamily', 'fontsize', 'fontColor', 'fontBackgroundColor', 'formatPainter',
                  '|',
                  'bold', 'italic', 'strikethrough', 'subscript', 'superscript', 'code',
                  '|',
                  'link', 'uploadImage', 'blockQuote', 'codeBlock',
                  '|',
                  'bulletedList', 'numberedList', 'todoList', 'outdent', 'indent',
                  '|',
                  'ckbox', 'ckboxImageEdit', 'toggleImageCaption', 'imageTextAlternative', 'ckboxImageEdit',
                  '|',
                  'imageStyle:block',
                  'imageStyle:side',
                  '|',
                  'toggleImageCaption',
                  'imageTextAlternative',
                  '|',
                  'linkImage', 'insertImage', 'insertImageViaUrl'
                ]
              },
              menuBar: {
                isVisible: true
              }
            })
            .then( /* ... */ )
            .catch( /* ... */ );
        }
      </script>
   </body>
</html>