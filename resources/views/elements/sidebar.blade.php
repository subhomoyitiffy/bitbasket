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
<div class="menu-divider mt-0">
</div>
<div class="menu-inner-shadow"></div>
<ul class="menu-inner py-1">
   <?php if(in_array(1, $module_id)){?>
      <!-- Dashboards -->
      <li class="menu-item <?=(($pageSegment == 'dashboard')?'active':'')?>">
         <a href="<?=url('dashboard')?>" class="menu-link">
            <i class="menu-icon tf-icons fa fa-home"></i>
            <div data-i18n="Dashboard">Dashboard</div>
         </a>
      </li>
   <?php }?>
   <?php if((in_array(2, $module_id)) || (in_array(3, $module_id)) || (in_array(4, $module_id))){?>
      <!-- Access & Permission -->
      <li class="menu-item active <?=(($pageSegment == 'module' || $pageSegment == 'role' || $pageSegment == 'admin-users')?'open':'')?>">
         <a href="javascript:void(0);" class="menu-link menu-toggle">
            <i class="menu-icon tf-icons fa fa-key"></i>
            <div data-i18n="Access & Permission">Access & Permission</div>
            <!-- <div class="badge bg-primary rounded-pill ms-auto">5</div> -->
         </a>
         <ul class="menu-sub">
            <?php if(in_array(2, $module_id)){?>
               <li class="menu-item <?=(($pageSegment == 'module')?'active':'')?>">
                  <a href="<?=url('module/list')?>" class="menu-link">
                     <div data-i18n="Modules">Modules</div>
                  </a>
               </li>
            <?php }?>
            <?php if(in_array(3, $module_id)){?>
               <li class="menu-item <?=(($pageSegment == 'role')?'active':'')?>">
                  <a href="<?=url('role/list')?>" class="menu-link">
                     <div data-i18n="Roles">Roles</div>
                  </a>
               </li>
            <?php }?>
            <?php if(in_array(4, $module_id)){?>
               <li class="menu-item <?=(($pageSegment == 'admin-users')?'active':'')?>">
                  <a href="<?=url('admin-users/list')?>" class="menu-link">
                     <div data-i18n="Admin Users">Admin Users</div>
                  </a>
               </li>
            <?php }?>
         </ul>
      </li>
   <?php }?>
   <?php if((in_array(5, $module_id)) || (in_array(6, $module_id))){?>
      <!-- FAQ -->
      <li class="menu-item active <?=(($pageSegment == 'faq-category' || $pageSegment == 'faq')?'open':'')?>">
         <a href="javascript:void(0);" class="menu-link menu-toggle">
            <i class="menu-icon tf-icons fa fa-question-circle"></i>
            <div data-i18n="FAQ">FAQ</div>
            <!-- <div class="badge bg-primary rounded-pill ms-auto">5</div> -->
         </a>
         <ul class="menu-sub">
            <?php if(in_array(5, $module_id)){?>
               <li class="menu-item <?=(($pageSegment == 'faq-category')?'active':'')?>">
                  <a href="<?=url('faq-category/list')?>" class="menu-link">
                     <div data-i18n="FAQ Category">FAQ Category</div>
                  </a>
               </li>
            <?php }?>
            <?php if(in_array(6, $module_id)){?>
               <li class="menu-item <?=(($pageSegment == 'faq')?'active':'')?>">
                  <a href="<?=url('faq/list')?>" class="menu-link">
                     <div data-i18n="FAQs">FAQs</div>
                  </a>
               </li>
            <?php }?>
         </ul>
      </li>
   <?php }?>
   <?php if(in_array(7, $module_id)){?>
      <!-- Membership Plans -->
      <li class="menu-item <?=(($pageSegment == 'package')?'active':'')?>">
         <a href="<?=url('package/list')?>" class="menu-link">
            <i class="menu-icon tf-icons fa-solid fa-tag"></i>
            <div data-i18n="Membership Plans">Membership Plans</div>
         </a>
      </li>
   <?php }?>
   <?php if(in_array(8, $module_id)){?>
      <!-- Members -->
      <li class="menu-item <?=(($pageSegment == 'member' && $pageFunction == 'list')?'active':'')?>">
         <a href="<?=url('member/list')?>" class="menu-link">
            <i class="menu-icon tf-icons fa-solid fa-users"></i>
            <div data-i18n="Members">Members</div>
         </a>
      </li>
   <?php }?>
   <?php if(in_array(13, $module_id)){?>
      <!-- Member Users-->
      <li class="menu-item <?=(($pageSegment == 'member-user' && $pageFunction == 'list')?'active':'')?>">
         <a href="<?=url('member-user/list/all')?>" class="menu-link">
            <i class="menu-icon tf-icons fa fa-users"></i>
            <div data-i18n="Member Users">Member Users</div>
         </a>
      </li>
   <?php }?>
   <?php if(in_array(9, $module_id)){?>
      <!-- Member's Membership Plan -->
      <li class="menu-item <?=(($pageSegment == 'member' && $pageFunction == 'all-member-membership-plan')?'active':'')?>">
         <a href="<?=url('member/all-member-membership-plan')?>" class="menu-link">
            <i class="menu-icon tf-icons fa-solid fa-tags"></i>
            <div data-i18n="Member's Membership Plan">Member's Membership Plan</div>
         </a>
      </li>
   <?php }?>
   <?php if(in_array(10, $module_id)){?>
      <!-- Member's Membership History -->
      <li class="menu-item <?=(($pageSegment == 'member' && $pageFunction == 'all-membership-history')?'active':'')?>">
         <a href="<?=url('member/all-membership-history')?>" class="menu-link">
            <i class="menu-icon tf-icons fa fa-list"></i>
            <div data-i18n="Member's Membership History">Member's Membership History</div>
         </a>
      </li>
   <?php }?>
   <?php if(in_array(11, $module_id)){?>
      <!-- Lesson Plans -->
      <li class="menu-item <?=(($pageSegment == '')?'active':'')?>">
         <a href="javascript:void(0);" class="menu-link">
            <i class="menu-icon tf-icons fa-solid fa-person-chalkboard"></i>
            <div data-i18n="Lesson Plans">Lesson Plans</div>
         </a>
      </li>
   <?php }?>
   <?php if(in_array(12, $module_id)){?>
      <!-- View Chat History -->
      <li class="menu-item <?=(($pageSegment == '')?'active':'')?>">
         <a href="javascript:void(0);" class="menu-link">
            <i class="menu-icon tf-icons fa-brands fa-rocketchat"></i>
            <div data-i18n="View Chat History">View Chat History</div>
         </a>
      </li>
   <?php }?>
   <?php if(in_array(14, $module_id)){?>
      <!-- Subscribed Users-->
      <li class="menu-item <?=(($pageSegment == 'subscriber')?'active':'')?>">
         <a href="<?=url('subscriber/list')?>" class="menu-link">
            <i class="menu-icon tf-icons fa-solid fa-at"></i>
            <div data-i18n="Subscribed Users">Subscribed Users</div>
         </a>
      </li>
   <?php }?>
   <?php if(in_array(15, $module_id)){?>
      <!-- Notifications-->
      <li class="menu-item <?=(($pageSegment == 'notification')?'active':'')?>">
         <a href="<?=url('notification/list')?>" class="menu-link">
            <i class="menu-icon tf-icons fa fa-bell"></i>
            <div data-i18n="Notifications">Notifications</div>
         </a>
      </li>
   <?php }?>
   <?php if(in_array(16, $module_id)){?>
      <!-- Account Setting -->
      <li class="menu-item <?=(($pageSegment == 'settings')?'active':'')?>">
         <a href="<?=url('settings')?>" class="menu-link">
            <i class="menu-icon tf-icons fa fa-cogs"></i>
            <div data-i18n="Account Settings">Account Settings</div>
         </a>
      </li>
   <?php }?>
   <?php if(in_array(17, $module_id)){?>
      <!-- Email Logs -->
      <li class="menu-item <?=(($pageSegment == 'email-logs')?'active':'')?>">
         <a href="<?=url('email-logs')?>" class="menu-link">
            <i class="menu-icon tf-icons fa fa-envelope"></i>
            <div data-i18n="Email Logs">Email Logs</div>
         </a>
      </li>
   <?php }?>
   <?php if(in_array(18, $module_id)){?>
      <!-- Login Logs -->
      <li class="menu-item <?=(($pageSegment == 'login-logs')?'active':'')?>">
         <a href="<?=url('login-logs')?>" class="menu-link">
            <i class="menu-icon tf-icons fa fa-sign-in"></i>
            <div data-i18n="Login Logs">Login Logs</div>
         </a>
      </li>
   <?php }?>
</ul>