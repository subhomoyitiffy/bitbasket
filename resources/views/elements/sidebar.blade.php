<?php
use App\Helpers\Helper;
use Illuminate\Support\Facades\Route;
$routeName    = Route::current();
$pageName     = explode("/", $routeName->uri());
$pageSegment  = $pageName[0];
$pageFunction = ((count($pageName)>1)?$pageName[1]:'');
?>
<div class="app-brand demo ">
   <a href="<?=url('dashboard')?>" class="app-brand-link">
      <span class="app-brand-text demo menu-text fw-bold ms-2"><?=Helper::getSettingValue('site_name')?></span>
   </a>
   <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto">
   <i class="bx menu-toggle-icon d-none d-xl-block fs-4 align-middle"></i>
   <i class="bx bx-x d-block d-xl-none bx-sm align-middle"></i>
   </a>
</div>
<div class="menu-divider mt-0  ">
</div>
<div class="menu-inner-shadow"></div>
<ul class="menu-inner py-1">
   <!-- Dashboards -->
   <li class="menu-item">
      <a href="<?=url('dashboard')?>" class="menu-link">
         <i class="menu-icon tf-icons fa fa-home"></i>
         <div data-i18n="Dashboard">Dashboard</div>
      </a>
   </li>

   <!-- <li class="menu-item active open">
      <a href="javascript:void(0);" class="menu-link menu-toggle">
         <i class="menu-icon tf-icons bx bx-home-circle"></i>
         <div data-i18n="Dashboards">Dashboards</div>
         <div class="badge bg-primary rounded-pill ms-auto">5</div>
      </a>
      <ul class="menu-sub">
         <li class="menu-item active">
            <a href="index.html" class="menu-link">
               <div data-i18n="Analytics">Analytics</div>
            </a>
         </li>
         <li class="menu-item">
            <a href="app-ecommerce-dashboard.html" class="menu-link">
               <div data-i18n="eCommerce">eCommerce</div>
            </a>
         </li>
         <li class="menu-item">
            <a href="app-logistics-dashboard.html" class="menu-link">
               <div data-i18n="Logistics">Logistics</div>
            </a>
         </li>
         <li class="menu-item">
            <a href="app-academy-dashboard.html" class="menu-link">
               <div data-i18n="Academy">Academy</div>
            </a>
         </li>
      </ul>
   </li> -->
</ul>