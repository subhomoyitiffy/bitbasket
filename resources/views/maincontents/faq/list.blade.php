<?php
use App\Models\FaqCategory;
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
                        <th scope="col">FAQ Category</th>
                        <th scope="col">Question</th>
                        <th scope="col">Answer</th>
                        <th scope="col">Action</th>
                     </tr>
                  </thead>
                  <tbody class="table-border-bottom-0">
                     <?php if($rows){ $sl=1; foreach($rows as $row){?>
                        <tr>
                           <th scope="row"><?=$sl++?></th>
                           <td>
                             <?php
                             $cat                 = FaqCategory::select('name')->where('id', '=', $row->faq_category_id)->first();
                             echo (($cat)?$cat->name:'');
                             ?>
                           </td>
                           <td><?=wordwrap($row->question,50,"<br>\n")?></td>
                           <td><?=wordwrap($row->answer,50,"<br>\n")?></td>
                           <td>
                             <a href="<?=url($controllerRoute . '/edit/'.Helper::encoded($row->id))?>" class="btn btn-outline-primary btn-sm" title="Edit <?=$module['title']?>"><i class="fa fa-edit"></i></a>
                             <a href="<?=url($controllerRoute . '/delete/'.Helper::encoded($row->id))?>" class="btn btn-outline-danger btn-sm" title="Delete <?=$module['title']?>" onclick="return confirm('Do You Want To Delete This <?=$module['title']?>');"><i class="fa fa-trash"></i></a>
                             <?php if($row->status){?>
                               <a href="<?=url($controllerRoute . '/change-status/'.Helper::encoded($row->id))?>" class="btn btn-outline-success btn-sm" title="Activate <?=$module['title']?>"><i class="fa fa-check"></i></a>
                             <?php } else {?>
                               <a href="<?=url($controllerRoute . '/change-status/'.Helper::encoded($row->id))?>" class="btn btn-outline-warning btn-sm" title="Deactivate <?=$module['title']?>"><i class="fa fa-times"></i></a>
                             <?php }?>
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