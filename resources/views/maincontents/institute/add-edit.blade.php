<?php
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
               $name             = $row->name;
               $status           = $row->status;
            } else {
               $name             = '';
               $status           = '';
            }
            ?>
            <form id="formAccountSettings" action="" method="POST" enctype="multipart/form-data">
               @csrf
               <div class="row">
                  <div class="col-lg-6 col-md-6">
                     <label for="name" class="form-label">Institute Name <small class="text-danger">*</small></label>
                     <input class="form-control" type="text" id="name" name="name" value="<?=$name?>" required placeholder="Institute Name" autofocus />
                  </div>
                  <div class="col-lg-6 col-md-6">
                     <label for="username" class="form-label d-block">Status <small class="text-danger">*</small></label>
                     <div class="form-check form-check-inline mt-3">
                        <input name="status" class="form-check-input" type="radio" value="1" id="status1" <?=(($status == 1)?'checked':'')?> required />
                        <label class="form-check-label" for="status1">
                          Active
                        </label>
                     </div>
                     <div class="form-check form-check-inline mt-3">
                        <input name="status" class="form-check-input" type="radio" value="0" id="status2" <?=(($status == 0)?'checked':'')?> required />
                        <label class="form-check-label" for="status2">
                          Deactive
                        </label>
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