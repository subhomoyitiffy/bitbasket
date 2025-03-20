<?php
use App\Models\Package;
use App\Models\User;
use App\Models\UserDetails;
use App\Models\UserSubscription;
use App\Helpers\Helper;
$controllerRoute = $module['controller_route'];
?>
<link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.0.1/css/toastr.css" rel="stylesheet"/>
<style type="text/css">    
    .toast-success {
        background-color: #000;
        color: #28a745 !important;
    }
    .toast-error {
        background-color: #000;
        color: #dc3545 !important;
    }
    .toast-warning {
        background-color: #000;
        color: #ffc107 !important;
    }
    .toast-info {
        background-color: #000;
        color: #007bff !important;
    }
</style>
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
               <input type="hidden" name="user_id" id="user_id" value="<?=$member_id?>">
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
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.0.1/js/toastr.js"></script>
<script type="text/javascript">
  function isNumber(evt) {
      evt = (evt) ? evt : window.event;
      var charCode = (evt.which) ? evt.which : evt.keyCode;
      if (charCode > 31 && (charCode < 48 || charCode > 57)) {
         return false;
      }
      return true;
   }
   function toastAlert(type, message, redirectStatus = false, redirectUrl = ''){
       toastr.options = {
           "closeButton": true,
           "debug": true,
           "newestOnTop": false,
           "progressBar": true,
           "positionClass": "toast-bottom-left",
           "preventDuplicates": false,
           "showDuration": "3000",
           "hideDuration": "1000000",
           "timeOut": "5000",
           "extendedTimeOut": "1000",
           "showEasing": "swing",
           "hideEasing": "linear",
           "showMethod": "fadeIn",
           "hideMethod": "fadeOut"
       }
       toastr[type](message);
       if(redirectStatus){        
           setTimeout(function(){ window.location = redirectUrl; }, 3000);
       }
   }
   $(function(){
      $('#subscriptionPaymentForm').submit(function(e){
         var base_url    = '<?=url('/')?>';
          e.preventDefault();
          var card_number         = $('#card_number').val();
          var card_name           = $('#card_name').val();
          var card_expiry_month   = $('#card_expiry_month').val();
          var card_expiry_year    = $('#card_expiry_year').val();
          var card_cvc            = $('#card_cvc').val();
          var package_id          = $('#package_id').val();
          var formData            = new FormData(this);
          $.ajax({
              type:'POST',
              url: base_url + "/member/subscription-payment",
              // data: { "_token": "{{ csrf_token() }}", "cardNo": card_number, "cardHolderName": card_name, "cardExpiryMM": card_expiry_month, "cardExpiryYY": card_expiry_year, "cardCvv": card_cvc, "package_id": package_id },
              data: formData,
              cache: false,
              contentType: false,
              processData: false,
              dataType: "JSON",
              beforeSend: function () {
                  // $('#payment-loader').show();
                  // $('#payment-btn').prop('disabled', true);
                  // $('#payment-btn-text').html('Processing');
                  // $(".page-loader").show();
              },
              success:function(data){
                  $(".page-loader").hide();
                  $('#payment-loader').hide();
                  $('#payment-btn').prop('disabled', false);
                  $('#payment-btn-text').html('PAY NOW');

                  if(data.status){
                      $('#subscriptionPaymentForm')[0].reset();
                      toastAlert("success", data.message);
                      // redirect to google after 5 seconds
                      window.setTimeout(function() {
                          window.location.href = base_url + '/member/list';
                      }, 3000);
                  } else {
                      // $('#subscriptionPaymentForm')[0].reset();
                      toastAlert("error", data.message);
                  }
              }
          }); 
      });
   })
</script>