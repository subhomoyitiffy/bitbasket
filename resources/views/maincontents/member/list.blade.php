<?php
use App\Models\Package;
use App\Models\UserSubscription;
use App\Helpers\Helper;
use Illuminate\Support\Facades\DB;
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
            <a href="<?=url($controllerRoute . '/add/')?>" class="btn btn-outline-success btn-sm">Add <?=$module['title']?></a>
         </div>
         <div class="card-body pb-2">
            <div class="dt-responsive table-responsive">
               <table id="<?=((count($rows)>0)?'simpletable':'')?>" class="table table-striped table-bordered nowrap">
                  <thead>
                     <tr>
                        <th scope="col">#</th>
                        <th scope="col">Name</th>
                        <th scope="col">Email</th>
                        <th scope="col">Phone</th>
                        <th scope="col">Country</th>
                        <th scope="col">State</th>
                        <th scope="col">Membership Plan</th>
                        <th scope="col">Action</th>
                     </tr>
                  </thead>
                  <tbody class="table-border-bottom-0">
                     <?php if(count($rows) > 0){ $sl=1; foreach($rows as $row){?>
                        <tr>
                              <th scope="row"><?=$sl++?></th>
                              <td>
                                 <!-- <img src="<?=(($row->profile_image != '')?env('UPLOADS_URL').'user/'.$row->profile_image:env('NO_IMAGE_AVATAR'))?>" alt="<?=$row->first_name?>" class="d-block" height="100" width="100" id="uploadedAvatar" style="border-radius:50%;" />
                                 <br> -->
                                 <?=$row->name?>
                              </td>
                              <td><?=$row->user_email?></td>
                              <td><?=$row->user_phone?></td>
                              <td><?=$row->country?></td>
                              <td><?=$row->state_name?></td>
                              <td>
                                 <?php
                                 $getCurrentPackage = DB::table('user_subscriptions')
                                                      ->join('packages', 'user_subscriptions.subscription_id', '=', 'packages.id')
                                                      ->select('user_subscriptions.subscription_start', 'user_subscriptions.subscription_end', 'packages.name as package_name')
                                                      ->where('user_subscriptions.is_active', '=', 1)
                                                      ->where('user_subscriptions.user_id', '=', $row->user_id)
                                                      ->orderBy('user_subscriptions.id', 'DESC')
                                                      ->first();
                                 if($getCurrentPackage){
                                 ?>
                                    <span class="badge bg-info"><?=$getCurrentPackage->package_name?></span><br>
                                    <?php if($getCurrentPackage->package_name != ''){?>
                                       (<?=date_format(date_create($getCurrentPackage->subscription_start), "M d, Y")?> - <?=date_format(date_create($getCurrentPackage->subscription_end), "M d, Y")?>)
                                    <?php }?>
                                 <?php }?>
                              </td>
                              <td>
                                 <a href="<?=url($controllerRoute . '/edit/'.Helper::encoded($row->user_id))?>" class="btn btn-outline-primary btn-sm" title="Edit <?=$module['title']?>"><i class="fa fa-edit"></i></a>
                                 <a href="<?=url($controllerRoute . '/delete/'.Helper::encoded($row->user_id))?>" class="btn btn-outline-danger btn-sm" title="Delete <?=$module['title']?>" onclick="return confirm('Do You Want To Delete This <?=$module['title']?>');"><i class="fa fa-trash"></i></a>
                                 <?php if($row->user_status == 1){?>
                                   <a href="<?=url($controllerRoute . '/change-status/'.Helper::encoded($row->user_id))?>" class="btn btn-outline-success btn-sm" title="Activate <?=$module['title']?>"><i class="fa fa-check"></i></a>
                                 <?php } elseif($row->user_status == 0){?>
                                   <a href="<?=url($controllerRoute . '/change-status/'.Helper::encoded($row->user_id))?>" class="btn btn-outline-warning btn-sm" title="Deactivate <?=$module['title']?>"><i class="fa fa-times"></i></a>
                                 <?php } elseif($row->user_status == 2){?>
                                   <a href="javascript:void(0);" class="btn btn-outline-danger btn-sm" title="Declined by admin <?=$module['title']?>"><i class="fa-solid fa-ban"></i></a>
                                 <?php }?>
                                 <br><br>
                                 <?php if($row->user_status == 1){?>
                                    <span class="badge bg-success"><i class="fa fa-check"></i> Verified</span>
                                 <?php } elseif($row->user_status == 0){?>
                                    <span class="badge bg-warning"><i class="fa fa-times"></i> Verification pending</span>
                                 <?php } elseif($row->user_status == 2){?>
                                    <span class="badge bg-danger"><i class="fa-solid fa-ban"></i> Declined by admin</span>
                                 <?php }?>

                                 <br><br>
                                 <a href="<?=url($controllerRoute . '/membership-select-package/'.Helper::encoded($row->user_id))?>" class="btn btn-outline-success btn-sm" title="Renew Membership"><i class="fa-solid fa-dollar-sign"></i>&nbsp;Renew</a>

                                 <br><br>
                                 <a target="_blank" href="<?=url($controllerRoute . '/membership-history/'.Helper::encoded($row->user_id))?>" class="btn btn-outline-info btn-sm" title="Membership History"><i class="fa-solid fa-tags"></i>&nbsp;Membership History</a>

                                 <br><br>
                                 <a target="_blank" href="<?=url('/member-subject/list/'.Helper::encoded($row->user_id))?>" class="btn btn-outline-warning btn-sm" title="Subjects"><i class="fa-solid fa-book"></i>&nbsp;Subjects</a>

                                 <br><br>
                                 <a target="_blank" href="<?=url('/member-user/list/'.Helper::encoded($row->user_id))?>" class="btn btn-outline-primary btn-sm" title="Team Users"><i class="fa-solid fa-users"></i>&nbsp;SMEs</a>
                              </td>
                        </tr>
                     <?php } } else {?>
                        <tr>
                           <td colspan="8" style="color: red; text-align: center;">No records found</td>
                        </tr>
                     <?php }?>
                  </tbody>
               </table>
            </div>
         </div>
      </div>
   </div>
</div>