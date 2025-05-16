<?php
use App\Helpers\Helper;
?>
<div class="authentication-wrapper authentication-cover">
   <div class="authentication-inner row m-0">
      <!-- /Left Text -->
      <div class="d-none d-lg-flex col-lg-7 col-xl-8 align-items-center">
         <div class="flex-row text-center mx-auto">
            <img src="<?=((Helper::getSettingValue('site_logo') != '')?env('UPLOADS_URL').Helper::getSettingValue('site_logo'):env('NO_IMAGE'))?>" alt="<?=Helper::getSettingValue('site_name')?>" width="520" class="img-fluid authentication-cover-img">
            <div class="mx-auto">
               <p>
                  <?=Helper::getSettingValue('description')?>
               </p>
            </div>
         </div>
      </div>
      <!-- /Left Text -->
      <!-- Login -->
      <div class="d-flex col-12 col-lg-5 col-xl-4 align-items-center authentication-bg p-sm-5 p-4">
         <div class="w-px-400 mx-auto">
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
            <!-- Logo -->
            <div class="app-brand mb-4">
               <a href="<?=url('/')?>" class="app-brand-link gap-2 mb-2">
                  <span class="app-brand-text demo h3 mb-0 fw-bold"><?=Helper::getSettingValue('site_name')?></span>
               </a>
            </div>
            <!-- /Logo -->
            <h4 class="mb-2"><?=$page_header?>? 🔒</h4>
            <p class="mb-4">Enter your email and we'll send you instructions to reset your password</p>
            <form id="formAuthentication" class="mb-3" action="" method="POST">
              @csrf
              <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="text" class="form-control" id="email" name="email" placeholder="Enter your email" autofocus>
              </div>
              <button type="submit" class="btn btn-primary d-grid w-100">Send OTP</button>
            </form>
            <div class="text-center">
              <a href="<?=url('/')?>" class="d-flex align-items-center justify-content-center">
                <i class="bx bx-chevron-left scaleX-n1-rtl"></i>
                Back to login
              </a>
            </div>
            <div class="mb-2 mb-md-0">
               © <script>document.write(new Date().getFullYear())</script>, developed & maintained by <a href="https://itiffyconsultants.com/" target="_blank" class="footer-link fw-medium">Itiffy Consultants</a>
            </div>
         </div>
      </div>
      <!-- /Login -->
   </div>
</div>