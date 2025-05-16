<?php
use App\Helpers\Helper;
$user_type = session('type');
?>
<h4><?=$page_header?></h4>
<h6 class="py-3 breadcrumb-wrapper mb-4">
   <span class="text-muted fw-light"><a href="<?=url('dashboard')?>">Dashboard</a> /</span> <?=$page_header?>
</h6>
<div class="row">
   <div class="col-lg-12 col-md-12 mb-4">
      <div class="card">
         <div class="card-body pb-2">
            <ul class="nav nav-pills mb-3 nav-fill" role="tablist">
               <li class="nav-item">
                  <button type="button" class="nav-link active" role="tab" data-bs-toggle="tab" data-bs-target="#navs-pills-justified-success" aria-controls="navs-pills-justified-success" aria-selected="true"><i class="tf-icons bx bx-home me-1"></i> Success Login</button>
               </li>
               <li class="nav-item">
                  <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#navs-pills-justified-failed" aria-controls="navs-pills-justified-failed" aria-selected="false"><i class="tf-icons bx bx-user me-1"></i> Failed Login</button>
               </li>
               <li class="nav-item">
                  <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#navs-pills-justified-logout" aria-controls="navs-pills-justified-logout" aria-selected="false"><i class="tf-icons bx bx-lock me-1"></i> Logout</button>
               </li>
            </ul>
            <div class="tab-content">
               <div class="tab-pane fade show active" id="navs-pills-justified-success" role="tabpanel">
                  <div class="dt-responsive table-responsive">
                     <table id="<?=((count($rows2)>0)?'simpletable':'')?>" class="table table-striped table-bordered nowrap">
                        <thead>
                           <tr>
                              <th scope="col">#</th>
                              <th scope="col">User Type</th>
                              <th scope="col">Name</th>
                              <th scope="col">Email</th>
                              <th scope="col">IP Address</th>
                              <th scope="col">Activity Details</th>
                              <th scope="col">Activity Date</th>
                              <th scope="col">Activity Type</th>
                              <th scope="col">Platform</th>
                           </tr>
                        </thead>
                        <tbody class="table-border-bottom-0">
                           <?php if($rows2){ $sl=1; foreach($rows2 as $row){?>
                              <tr>
                                 <th scope="row"><?=$sl++?></th>
                                 <td><?=$row->user_type?></td>
                                 <td><?=$row->user_name?></td>
                                 <td><?=$row->user_email?></td>
                                 <td><?=$row->ip_address?></td>
                                 <td><?=$row->activity_details?></td>
                                 <td><?=date_format(date_create($row->created_at), "M d, Y h:i A")?></td>
                                 <td>
                                   <?php if($row->activity_type == 0) {?>
                                     <span class="badge bg-danger">FAILED</span>
                                   <?php } elseif($row->activity_type == 1) {?>
                                     <span class="badge bg-success">SUCCESS</span>
                                   <?php } elseif($row->activity_type == 2) {?>
                                     <span class="badge bg-primary">Log Out</span>
                                   <?php }?>
                                 </td>
                                 <td><?=$row->platform_type?></td>
                              </tr>
                           <?php } }?>
                        </tbody>
                     </table>
                  </div>
               </div>
               <div class="tab-pane fade show" id="navs-pills-justified-failed" role="tabpanel">
                  <div class="dt-responsive table-responsive">
                     <table id="<?=((count($rows1)>0)?'simpletable2':'')?>" class="table table-striped table-bordered nowrap">
                        <thead>
                           <tr>
                              <th scope="col">#</th>
                              <th scope="col">User Type</th>
                              <th scope="col">Name</th>
                              <th scope="col">Email</th>
                              <th scope="col">IP Address</th>
                              <th scope="col">Activity Details</th>
                              <th scope="col">Activity Date</th>
                              <th scope="col">Activity Type</th>
                              <th scope="col">Platform</th>
                           </tr>
                        </thead>
                        <tbody class="table-border-bottom-0">
                           <?php if($rows1){ $sl=1; foreach($rows1 as $row){?>
                              <tr>
                                 <th scope="row"><?=$sl++?></th>
                                 <td><?=$row->user_type?></td>
                                 <td><?=$row->user_name?></td>
                                 <td><?=$row->user_email?></td>
                                 <td><?=$row->ip_address?></td>
                                 <td><?=$row->activity_details?></td>
                                 <td><?=date_format(date_create($row->created_at), "M d, Y h:i A")?></td>
                                 <td>
                                   <?php if($row->activity_type == 0) {?>
                                     <span class="badge bg-danger">FAILED</span>
                                   <?php } elseif($row->activity_type == 1) {?>
                                     <span class="badge bg-success">SUCCESS</span>
                                   <?php } elseif($row->activity_type == 2) {?>
                                     <span class="badge bg-primary">Log Out</span>
                                   <?php }?>
                                 </td>
                                 <td><?=$row->platform_type?></td>
                              </tr>
                           <?php } }?>
                        </tbody>
                     </table>
                  </div>
               </div>
               <div class="tab-pane fade show" id="navs-pills-justified-logout" role="tabpanel">
                  <div class="dt-responsive table-responsive">
                     <table id="<?=((count($rows3)>0)?'simpletable3':'')?>" class="table table-striped table-bordered nowrap">
                        <thead>
                           <tr>
                              <th scope="col">#</th>
                              <th scope="col">User Type</th>
                              <th scope="col">Name</th>
                              <th scope="col">Email</th>
                              <th scope="col">IP Address</th>
                              <th scope="col">Activity Details</th>
                              <th scope="col">Activity Date</th>
                              <th scope="col">Activity Type</th>
                              <th scope="col">Platform</th>
                           </tr>
                        </thead>
                        <tbody class="table-border-bottom-0">
                           <?php if($rows3){ $sl=1; foreach($rows3 as $row){?>
                              <tr>
                                 <th scope="row"><?=$sl++?></th>
                                 <td><?=$row->user_type?></td>
                                 <td><?=$row->user_name?></td>
                                 <td><?=$row->user_email?></td>
                                 <td><?=$row->ip_address?></td>
                                 <td><?=$row->activity_details?></td>
                                 <td><?=date_format(date_create($row->created_at), "M d, Y h:i A")?></td>
                                 <td>
                                   <?php if($row->activity_type == 0) {?>
                                     <span class="badge bg-danger">FAILED</span>
                                   <?php } elseif($row->activity_type == 1) {?>
                                     <span class="badge bg-success">SUCCESS</span>
                                   <?php } elseif($row->activity_type == 2) {?>
                                     <span class="badge bg-primary">Log Out</span>
                                   <?php }?>
                                 </td>
                                 <td><?=$row->platform_type?></td>
                              </tr>
                           <?php } }?>
                        </tbody>
                     </table>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>