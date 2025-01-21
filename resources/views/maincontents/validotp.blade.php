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
            <h4 class="mb-2"><?=$page_header?> 💬</h4>
            <p class="text-start mb-4">
              We sent a verification code to your email. Enter the code from the email in the field below.
              <span class="fw-medium d-block mt-2">******@domain.com</span>
            </p>
            <p class="mb-0 fw-medium">Type your 6 digit security code</p>
            <form id="twoStepsForm" action="" method="POST">
              @csrf
              <div class="mb-3">
                <div class="auth-input-wrapper d-flex align-items-center justify-content-sm-between numeral-mask-wrapper">
                  <input type="password" name="otp1" class="form-control auth-input h-px-50 text-center numeral-mask mx-1 my-2" maxlength="1" autofocus>
                  <input type="password" name="otp2" class="form-control auth-input h-px-50 text-center numeral-mask mx-1 my-2" maxlength="1">
                  <input type="password" name="otp3" class="form-control auth-input h-px-50 text-center numeral-mask mx-1 my-2" maxlength="1">
                  <input type="password" name="otp4" class="form-control auth-input h-px-50 text-center numeral-mask mx-1 my-2" maxlength="1">
                  <input type="password" name="otp5" class="form-control auth-input h-px-50 text-center numeral-mask mx-1 my-2" maxlength="1">
                  <input type="password" name="otp6" class="form-control auth-input h-px-50 text-center numeral-mask mx-1 my-2" maxlength="1">
                </div>
                <!-- Create a hidden field which is combined by 3 fields above -->
                <input type="hidden" name="otp" />
              </div>
              <button type="submit" class="btn btn-primary d-grid w-100 mb-3">
                Verify my account
              </button>
              <div class="text-center">Didn't get the code?
                <a href="<?=url('resendOtp/' . Helper::encoded($id))?>">
                  Resend
                </a>
              </div>
            </form>
            <div class="mb-2 mb-md-0">
               © <script>document.write(new Date().getFullYear())</script>, developed & maintained by <a href="https://itiffyconsultants.com/" target="_blank" class="footer-link fw-medium">Itiffy Consultants</a>
            </div>
         </div>
      </div>
      <!-- /Login -->
   </div>
</div>