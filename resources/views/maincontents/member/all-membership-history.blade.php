<?php
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
            
         </div>
         <div class="card-body pb-2">
            <div class="dt-responsive table-responsive">
               <table id="<?=((count($rows)>0)?'simpletable':'')?>" class="table table-striped table-bordered nowrap">
                  <thead>
                     <tr>
                        <th scope="col">#</th>
                        <th scope="col">User Info</th>
                        <th scope="col">Membership Plan</th>
                        <th scope="col">Coupon Info</th>
                        <th scope="col">Amount</th>
                        <th scope="col">Txn No.</th>
                        <th scope="col">Start/End</th>
                        <th scope="col">Comment</th>
                        <th scope="col">Txn Timestamp</th>
                     </tr>
                  </thead>
                  <tbody class="table-border-bottom-0">
                     <?php if($rows){ $sl=1; foreach($rows as $row){?>
                        <tr>
                            <th scope="row"><?=$sl++?></th>
                            <td><?=$row->user_name?><br><?=$row->user_email?></td>
                            <td><span class="badge bg-primary"><?=$row->package_name?></span></td>
                            <td><?=number_format($row->coupon_discount,2)?><br><?=$row->coupon_code?></td>
                            <td><?=number_format($row->payable_amount,2)?></td>
                            <td><?=$row->stripe_subscription_id?></td>
                            <td><?=date_format(date_create($row->subscription_start), "M d, Y")?><br><?=date_format(date_create($row->subscription_end), "M d, Y")?></td>
                            <td><?=$row->comment?></td>
                            <td><?=date_format(date_create($row->created_at), "M d, Y")?></td>
                        </tr>
                     <?php } }?>
                  </tbody>
               </table>
            </div>
         </div>
      </div>
   </div>
</div>