<?php
use App\Models\Module;
use App\Helpers\Helper;
$controllerRoute = $module['controller_route'];
?>
<h4><?=$page_header?></h4>
<h6 class="py-3 breadcrumb-wrapper mb-4">
   <span class="text-muted fw-light"><a href="<?=url('dashboard')?>">Dashboard</a> / <a href="<?=url($controllerRoute . '/list/')?>"><?=$module['title']?> List</a> / </span> <?=$page_header?>
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
            <?php
            if($row){
               $role_name     = $row->role_name;
               $module_id     = (($row->module_id != '')?json_decode($row->module_id):[]);
            } else {
               $role_name     = '';
               $module_id     = [];
            }
            ?>
            <form id="formAccountSettings" action="" method="POST" enctype="multipart/form-data">
               @csrf
               <div class="row">
                  <div class="mb-3 col-md-12">
                     <label for="role_name" class="form-label">Role Name</label>
                     <input class="form-control" type="text" id="role_name" name="role_name" value="<?=$role_name?>" required placeholder="Role Name" autofocus />
                  </div>
               </div>
               <div class="row">
                  <div class="mb-3 col-md-12">
                     <label for="module_id" class="form-label">Modules</label>
                     <div class="row">
                        <?php if($modules){ foreach($modules as $moduleRow){?>
                           <div class="col-md-4 col-lg-4">
                              <div class="form-check form-switch mb-3">
                                 <input class="form-check-input" type="checkbox" name="module_id[]" value="<?=$moduleRow->id?>" id="module<?=$moduleRow->id?>" <?=((in_array($moduleRow->id, $module_id))?'checked':'')?>>
                                 <label class="form-check-label" for="module<?=$moduleRow->id?>"><?=$moduleRow->name?></label>
                              </div>
                           </div>
                        <?php } }?>
                     </div>
                  </div>
               </div>
               <div class="mt-2">
                  <button type="submit" class="btn btn-primary me-2">Save Changes</button>
                  <button type="reset" class="btn btn-label-secondary">Cancel</button>
               </div>
            </form>
         </div>
      </div>
   </div>
</div>