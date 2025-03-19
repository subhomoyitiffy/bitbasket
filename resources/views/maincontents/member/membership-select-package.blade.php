<?php
use App\Models\Package;
use App\Models\User;
use App\Models\UserDetails;
use App\Models\UserSubscription;
use App\Helpers\Helper;
$controllerRoute = $module['controller_route'];
?>
<h4><?=$page_header?></h4>
<h6 class="py-3 breadcrumb-wrapper mb-4">
   <span class="text-muted fw-light"><a href="<?=url('dashboard')?>">Dashboard</a> /</span> <?=$page_header?>
</h6>
<div class="row">
   <div class="col-lg-12 col-md-12 mb-4">
      <?php if(session('success_message')){?>
         <div class="alert alert-success alert-dismissible autohide" role="alert">
            <h6 class="alert-heading mb-1"><i class="bx bx-xs bx-desktop align-top me-2"></i>Success!</h6>
            <span><?=session('success_message')?></span>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
            </button>
         </div>
      <?php }?>
      <?php if(session('error_message')){?>
         <div class="alert alert-danger alert-dismissible autohide" role="alert">
            <h6 class="alert-heading mb-1"><i class="bx bx-xs bx-store align-top me-2"></i>Danger!</h6>
            <span><?=session('error_message')?></span>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
            </button>
         </div>
      <?php }?>
      <div class="card">
         <div class="card-header mb-3">
            <?php if($current_package){?>
                <div class="col-lg-12 mb-3">
                    <p>Your current package : <span class="font-weight-bold" style="color: #FFF;"><?=$current_package->package_name?></span></p>
                    <p>Your current package actives from <span class="font-weight-bold" style="color: #FFF;"><?=date_format(date_create($current_package->subscription_start), "M d, Y")?></span> to <span class="font-weight-bold" style="color: #FFF;"><?=date_format(date_create($current_package->subscription_end), "M d, Y")?></span></p>
                    <p>Status : <span class="font-weight-bold" style="color: <?=(($current_package->is_active)?'#FFF':'#FFF')?>;"><?=(($current_package->is_active)?'ACTIVE':'EXPIRED')?></span></p>
                </div>
            <?php } else {?>
                <div class="col-lg-12 mb-3">
                    <p class="text-danger">You are not subscribed to any packages. Please subscribed any of the packages below</p>
                </div>
            <?php }?>
         </div>
         <div class="card-body pb-2">
            <?php if($packages){ foreach($packages as $package) {?>
               <div class="col-lg-4">
                  <div class="membership-card">
                     <div class="membership-cad-header">
                       <span><img src="<?=env('ADMIN_ASSETS_URL')?>assets/img/freetrielicon.svg"></span>
                       <h2><?=$package->name?></h2>
                       <p><?=$package->description?></p>
                     </div>
                     <div class="price">$<?=$package->price?></div>
                     <?php if($current_package){?>
                         <?php if($current_package->subscription_id != $package->id){?>
                             <a href="<?=url('/member/subscription-checkout/'.Helper::encoded($package->id).'/'.Helper::encoded($member_id))?>"><button>Get it now</button></a>
                         <?php } else {?>
                             <button class="essenetial-btn">SUBSCRIBED</button>
                         <?php }?>
                     <?php } else {?>
                         <a href="<?=url('/member/subscription-checkout/'.Helper::encoded($package->id).'/'.Helper::encoded($member_id))?>"><button>Get it now</button></a>
                     <?php }?>
                     <ul>
                        <li><p><span class="font-weight-bold">Duration : </span><?=$package->duration?> Months</p></li>
                     </ul>
                  </div>
               </div>
            <?php } }?>
         </div>
      </div>
   </div>
</div>