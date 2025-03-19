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
         <div class="card-body pb-2">
            <form method="POST" action="" id="subscriptionPaymentForm">
               @csrf
               <input type="hidden" name="package_id" id="package_id" value="<?=$package_id?>">
               <div class="form-group">
                  <div class="row">
                      <div class="col-lg-6 mb-3">
                        <label for="card_number">Card Number</label>
                      <input type="text" class="form-control" name="cardNo" id="card_number" placeholder="Card Number" maxlength="16" minlength="16" onkeypress="return isNumber(event)">
                      </div>
                      <div class="col-lg-6 mb-3">
                        <label for="card_name">Card Holder Name</label>
                      <input type="text" class="form-control" name="cardHolderName" id="card_name" placeholder="Card Holder Name">
                      </div>
                     <div class="col-lg-4 mb-3">
                        <label for="card_expiry_month">Card Expiry Month</label>
                      <select class="form-control" name="cardExpiryMM" id="card_expiry_month">
                        <option value="" selected>Select Card Expiry Month</option>
                        <option value="01">01</option>
                        <option value="02">02</option>
                        <option value="03">03</option>
                        <option value="04">04</option>
                        <option value="05">05</option>
                        <option value="06">06</option>
                        <option value="07">07</option>
                        <option value="08">08</option>
                        <option value="09">09</option>
                        <option value="10">10</option>
                        <option value="11">11</option>
                        <option value="12">12</option>
                      </select>
                      </div>
                      <div class="col-lg-4 mb-3">
                      <div class="form-group">
                          <label for="card_expiry_year">Card Expiry Year</label>
                          <select class="form-control" name="cardExpiryYY" id="card_expiry_year">
                              <option value="" selected>Select Card Expiry Year</option>
                              <?php for($y=date('Y');$y<=2050;$y++){?>
                              <option value="<?=$y?>"><?=$y?></option>
                              <?php }?>
                          </select>
                      </div>
                     </div>
                     <div class="col-lg-4 mb-3">
                         <div class="form-group">
                             <label for="card_cvc">Card CVC</label>
                             <input type="password" class="form-control" name="cardCvv" id="card_cvc" placeholder="Card CVC" maxlength="3" minlength="3" onkeypress="return isNumber(event)">
                         </div>
                     </div>
                  </div>
               </div>
               <div class="form-group">
                  <button type="submit" class="theme-btn" id="payment-btn"><span id="payment-btn-text">PAY NOW</span> <img src="<?=env('FRONT_ASSETS_URL')?>assets/img/loading-payment.gif" id="payment-loader" style="display:none; width: 25px; height:25px;"></button>
               </div>
            </form>
         </div>
      </div>
   </div>
</div>
<script type="text/javascript">
  function isNumber(evt) {
    evt = (evt) ? evt : window.event;
    var charCode = (evt.which) ? evt.which : evt.keyCode;
    if (charCode > 31 && (charCode < 48 || charCode > 57)) {
        return false;
    }
    return true;
  }
</script>