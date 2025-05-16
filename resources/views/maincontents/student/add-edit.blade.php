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
               $institute_id        = $row->institute_id;
               $first_name          = $row->first_name;
               $last_name           = $row->last_name;
               $work_email          = $row->work_email;
               $phone               = $row->phone;
               $status              = $row->status;
            } else {
               $institute_id        = '';
               $first_name          = '';
               $last_name           = '';
               $work_email          = '';
               $phone               = '';
               $status              = '';
            }
            ?>
            <form id="formAccountSettings" action="" method="POST" enctype="multipart/form-data">
               @csrf
               <div class="row">
                  <div class="col-lg-4 col-md-42">
                     <label for="institute_id" class="form-label">Institute <small class="text-danger">*</small></label>
                     <select name="institute_id" class="select2 form-select" id="institute_id" required>
                       <option value="" selected>Select Institute</option>
                       <?php if($institutes){ foreach($institutes as $inst){?>
                       <option value="<?=$inst->id?>" <?=(($inst->id == $institute_id)?'selected':'')?>><?=$inst->name?></option>
                       <?php } }?>
                     </select>
                  </div>
                  <div class="col-lg-4 col-md-4">
                     <label for="first_name" class="form-label">First Name <small class="text-danger">*</small></label>
                     <input class="form-control" type="text" id="first_name" name="first_name" value="<?=$first_name?>" required placeholder="First Name" />
                  </div>
                  <div class="col-lg-4 col-md-4">
                     <label for="last_name" class="form-label">Last name <small class="text-danger">*</small></label>
                     <input class="form-control" type="text" id="last_name" name="last_name" value="<?=$last_name?>" required placeholder="Last name" />
                  </div>

                  <div class="col-lg-4 col-md-4">
                     <label for="work_email" class="form-label">Email <small class="text-danger">*</small></label>
                     <input class="form-control" type="text" id="work_email" name="work_email" value="<?=$work_email?>" required placeholder="Email" />
                  </div>
                  <div class="col-lg-4 col-md-4">
                     <label for="phone" class="form-label">API KEY <small class="text-danger">*</small></label>
                     <input class="form-control" type="text" id="phone" name="phone" value="<?=$phone?>" required placeholder="API KEY" />
                  </div>
                  <div class="col-lg-4 col-md-4">
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