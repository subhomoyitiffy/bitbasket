<?php
use App\Helpers\Helper;
?>
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
<title><?=$title?></title>
<meta name="title" content="<?=Helper::getSettingValue('meta_title')?>" />
<meta name="description" content="<?=Helper::getSettingValue('meta_description')?>" />
<meta name="keywords" content="<?=Helper::getSettingValue('meta_keywords')?>">
<!-- Favicon -->
<link rel="icon" type="image/x-icon" href="<?=((Helper::getSettingValue('site_favicon') != '')?env('UPLOADS_URL').Helper::getSettingValue('site_favicon'):env('NO_IMAGE'))?>" />
<!-- Fonts -->
<link rel="preconnect" href="https://fonts.googleapis.com/">
<link rel="preconnect" href="https://fonts.gstatic.com/" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&amp;family=Rubik:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&amp;display=swap" rel="stylesheet">
<!-- Icons -->
<link rel="stylesheet" href="<?=env('ADMIN_ASSETS_URL')?>assets/vendor/fonts/boxicons.css" />
<link rel="stylesheet" href="<?=env('ADMIN_ASSETS_URL')?>assets/vendor/fonts/fontawesome.css" />
<link rel="stylesheet" href="<?=env('ADMIN_ASSETS_URL')?>assets/vendor/fonts/flag-icons.css" />
<!-- Core CSS -->
<link rel="stylesheet" href="<?=env('ADMIN_ASSETS_URL')?>assets/vendor/css/rtl/core-dark.css" class="template-customizer-core-css" />
<link rel="stylesheet" href="<?=env('ADMIN_ASSETS_URL')?>assets/vendor/css/rtl/theme-default-dark.css" class="template-customizer-theme-css" />
<link rel="stylesheet" href="<?=env('ADMIN_ASSETS_URL')?>assets/css/demo.css" />
<!-- Vendors CSS -->
<link rel="stylesheet" href="<?=env('ADMIN_ASSETS_URL')?>assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css" />
<link rel="stylesheet" href="<?=env('ADMIN_ASSETS_URL')?>assets/vendor/libs/typeahead-js/typeahead.css" />
<!-- Vendor -->
<link rel="stylesheet" href="<?=env('ADMIN_ASSETS_URL')?>assets/vendor/libs/%40form-validation/umd/styles/index.min.css" />
<!-- Page CSS -->
<!-- Page -->
<link rel="stylesheet" href="<?=env('ADMIN_ASSETS_URL')?>assets/vendor/css/pages/page-auth.css">
<!-- Helpers -->
<script src="<?=env('ADMIN_ASSETS_URL')?>assets/vendor/js/helpers.js"></script>
<!--! Template customizer & Theme config files MUST be included after core stylesheets and helpers.js in the <head> section -->
<!--? Template customizer: To hide customizer set displayCustomizer value false in config.js.  -->
<script src="<?=env('ADMIN_ASSETS_URL')?>assets/vendor/js/template-customizer.js"></script>
<!--? Config:  Mandatory theme config file contain global vars & default theme options, Set your preferred theme option in this file.  -->
<script src="<?=env('ADMIN_ASSETS_URL')?>assets/js/config.js"></script>