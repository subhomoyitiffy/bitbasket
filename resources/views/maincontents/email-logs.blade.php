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
            <div class="dt-responsive table-responsive">
               <table id="<?=((count($rows)>0)?'simpletable':'')?>" class="table table-striped table-bordered nowrap">
                  <thead>
                     <tr>
                        <th scope="col">#</th>
                        <th scope="col">Name</th>
                        <th scope="col">Email</th>
                        <th scope="col">Subject</th>
                        <th scope="col">Date</th>
                        <th scope="col">Action</th>
                     </tr>
                  </thead>
                  <tbody class="table-border-bottom-0">
                     <?php if($rows){ $sl=1; foreach($rows as $row){?>
                        <tr>
                           <th scope="row"><?=$sl++?></th>
                           <td><?=$row->name?></td>
                           <td><?=$row->email?></td>
                           <td><?=$row->subject?></td>
                           <td><?=date_format(date_create($row->created_at), "M d, Y h:i A")?></td>
                           <td>
                              <a class="btn btn-info btn-sm" href="<?=url('email-logs/details/'.Helper::encoded($row->id))?>"><i class="fa fa-info-circle"></i></a>
                          </td>
                        </tr>
                     <?php } }?>
                  </tbody>
               </table>
            </div>
         </div>
      </div>
   </div>
</div>