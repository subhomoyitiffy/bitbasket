<?php
use App\Models\User;
use App\Models\Subject;
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
            <a href="<?=url($controllerRoute . '/add/')?>" class="btn btn-outline-success btn-sm">Add <?=$module['title']?></a>
         </div>
         <div class="card-body pb-2">
            <div class="dt-responsive table-responsive">
               <table id="<?=((count($rows)>0)?'simpletable':'')?>" class="table table-striped table-bordered nowrap">
                  <thead>
                     <tr>
                        <th scope="col">#</th>
                        <th scope="col">Parent Member</th>
                        <th scope="col">Name</th>
                        <th scope="col">Email</th>
                        <th scope="col">Phone</th>
                        <th scope="col">Country</th>
                        <th scope="col">State</th>
                        <th scope="col">Subjects</th>
                        <th scope="col">Action</th>
                     </tr>
                  </thead>
                  <tbody class="table-border-bottom-0">
                     <?php if(count($rows) > 0){ $sl=1; foreach($rows as $row){?>
                        <tr>
                              <th scope="row"><?=$sl++?></th>
                              <td>
                                 <?php
                                 $getParentUser                 = User::select('name')->where('id', '=', $row->parent_id)->first();
                                 echo (($getParentUser)?$getParentUser->name:'');
                                 ?>
                              </td>
                              <td>
                                 <?=$row->name?>
                              </td>
                              <td><?=$row->user_email?></td>
                              <td><?=$row->user_phone?></td>
                              <td><?=$row->country?></td>
                              <td><?=$row->state_name?></td>
                              <td>
                                 <ul>
                                    <?php
                                    $subjects = json_decode($row->subjects);
                                    if(!empty($subjects)){ for($k=0;$k<count($subjects);$k++){
                                       $getSubject = Subject::select('name')->where('id', $subjects[$k])->first();
                                    ?>
                                       <li><?=(($getSubject)?$getSubject->name:'')?></li>
                                    <?php } } ?>
                                 </ul>
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
                              </td>
                        </tr>
                     <?php } } else {?>
                        <tr>
                           <td colspan="9" style="color: red; text-align: center;">No records found</td>
                        </tr>
                     <?php }?>
                  </tbody>
               </table>
            </div>
         </div>
      </div>
   </div>
</div>