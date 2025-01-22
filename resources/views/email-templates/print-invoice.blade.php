<?php
use App\Models\GeneralSetting;
use App\Models\Product;
use App\Models\ProductAttribute;
use App\Models\Attribute;
use App\Models\AttributeValue;
use App\Models\ProductImage;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\CancelOrderReason;
use App\Helpers\Helper;

$generalSetting = GeneralSetting::find(1);
?>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
<div class="container-fluid invoice-container" style="margin: 15px auto;padding: 40px;max-width: 850px;background-color: #fff;border: 1px solid #ccc;-moz-border-radius: 6px;-webkit-border-radius: 6px;-o-border-radius: 6px;border-radius: 6px;">
   <table class="table table-bordered border border-secondary mb-0">
       <tbody>
          <tr>
             <td colspan="2" class="bg-light text-center">
                <h3 class="mb-0"><strong><?=$generalSetting->site_name?></strong></h3>
             </td>
          </tr>
          <tr>
             <td class="col-7">
                <div class="row gx-2 gy-2">
                   <div class="col-auto"><strong>M/s. :</strong></div>
                   <div class="col">
                      <address>
                         <strong><?=$getOrderDetail->b_fname.' '.$getOrderDetail->b_lname?></strong><br>
                         <?=$getOrderDetail->b_street?>, <?=$getOrderDetail->b_suburb?><br>
                         <?=$getOrderDetail->b_state?> <?=$getOrderDetail->b_postcode?><br>
                         <?=$getOrderDetail->b_country?><br>
                         <?=$getOrderDetail->b_phone?><br>
                         <?=$getOrderDetail->b_email?><br>
                      </address>
                   </div>
                </div>
             </td>
             <td class="col-5 bg-light">
                <div class="row gx-2 gy-1 fw-600">
                   <div class="col-5">Invoice No <span class="float-end">:</span></div>
                   <div class="col-7">#<?=$getOrderDetail->order_no?></div>
                   <div class="col-5">Date <span class="float-end">:</span></div>
                   <div class="col-7"><?=date_format(date_create($getOrderDetail->order_date), "M d, Y")?> <?=date_format(date_create($getOrderDetail->order_time), "h:i A")?></div>

                   <div class="col-5">Payment Status <span class="float-end">:</span></div>
                   <div class="col-7" style="font-size: 11px;"><?=(($getOrderDetail->payment_status)?'SUCCESS':'FAILED')?></div>
                   <div class="col-5">Payment Mode <span class="float-end">:</span></div>
                   <div class="col-7" style="font-size: 11px;"><?=$getOrderDetail->payment_mode?></div>
                   <div class="col-5">Txn No. <span class="float-end">:</span></div>
                   <div class="col-7" style="font-size: 11px;"><?=$getOrderDetail->payment_txn_no?></div>
                   <div class="col-5">Date/Time <span class="float-end">:</span></div>
                   <div class="col-7" style="font-size: 11px;"><?=date_format(date_create($getOrderDetail->payment_date_time), "M d, Y h:i A")?></div>
                </div>
             </td>
          </tr>
          <tr>
             <td colspan="2" class="p-0">
                <table class="table table-sm mb-0">
                   <thead>
                      <tr class="bg-light">
                         <td class="col-1 text-center"><strong>#</strong></td>
                         <td class="col-6 "><strong>Product Name</strong></td>
                         <td class="col-1 text-center"><strong>Qty</strong></td>
                         <td class="col-2 text-end"><strong>Rate</strong></td>
                         <td class="col-2 text-end"><strong>Amount</strong></td>
                      </tr>
                   </thead>
                   <tbody>
                      <?php
                      $orderDetails = OrderDetail::where('order_id', '=', $getOrderDetail->id)->get();
                      $sl=1;
                      $subtotal=0;
                      if($orderDetails){ foreach($orderDetails as $orderDetail){
                         $getProduct    = Product::where('id', '=', $orderDetail->product_id)->first();
                         $subtotal      += $orderDetail->total;
                         $parent_id_val    = json_decode($orderDetail->parent_id_val);
                         $child_id_val  = json_decode($orderDetail->child_id_val);
                      ?>
                         <tr>
                            <td class="col-1 text-center"><?=$sl++?></td>
                            <td class="col-6">
                               <?=$getProduct->name?><br>
                               <?php if($getProduct->external_product_link != ''){?>
                                 <a href="<?=$getProduct->external_product_link?>" target="_blank"><span class="badge bg-info"><i class="fa fa-link"></i> External Product Link</span></a>
                               <?php }?>
                               <?php if($parent_id_val){ for($i=0;$i<count($parent_id_val);$i++){?>
                                   <p class="text-muted mb-0"><?=$parent_id_val[$i]?> : <small><?=$child_id_val[$i]?></small></p>
                               <?php } }?>
                            </td>
                            <td class="col-1 text-center"><?=$orderDetail->qty?></td>
                            <td class="col-2 text-end">$<?=number_format($orderDetail->rate,2)?></td>
                            <td class="col-2 text-end">$<?=number_format($orderDetail->total,2)?></td>
                         </tr>
                      <?php } }?>
                   </tbody>
                </table>
             </td>
          </tr>
          <tr class="bg-light fw-600">
             <td class="col-7 py-1"></td>
             <td class="col-5 py-1 pe-1">Sub Total: <span class="float-end">$<?=number_format($subtotal,2)?></span></td>
          </tr>
          <tr class="bg-light fw-600">
             <td class="col-7 py-1"></td>
             <td class="col-5 py-1 pe-1">Discount: <span class="float-end">$<?=number_format($getOrderDetail->disc_amount,2)?></span></td>
          </tr>
          <tr class="bg-light fw-600">
             <td class="col-7 py-1"></td>
             <td class="col-5 py-1 pe-1">Shipping: <span class="float-end">$<?=number_format($getOrderDetail->shipping_amt,2)?></span></td>
          </tr>
          <tr class="bg-light fw-600">
             <td class="col-7 py-1"></td>
             <td class="col-5 py-1 pe-1">Tax:<span class="float-end">$<?=number_format($getOrderDetail->tax_amt,2)?></span></td>
          </tr>
          <tr class="bg-light fw-600">
             <td class="col-7 py-1"></td>
             <td class="col-5 py-1 pe-1">Total: <span class="float-end">$<?=number_format($getOrderDetail->net_amt,2)?></span></td>
          </tr>
          <tr class="bg-light fw-600">
             <td class="col-7 py-1">Tracking Number : <?=$getOrderDetail->tracking_number?></td>
             <td class="col-5 py-1 pe-1">Amount In Words: <span class="float-end"><i><?=Helper::getIndianCurrency($getOrderDetail->net_amt)?></i></span></td>
          </tr>
       </tbody>
   </table>
</div>
