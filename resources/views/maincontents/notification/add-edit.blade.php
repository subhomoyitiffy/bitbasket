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
               $to_user          = $row->to_user;
               $title            = $row->title;
               $description      = $row->description;
            } else {
               $to_user          = '';
               $title            = '';
               $description      = '';
            }
            ?>
            <form id="formAccountSettings" action="" method="POST" enctype="multipart/form-data">
               @csrf
               <div class="row">
                  <div class="mb-3 col-md-12">
                     <label for="to_user" class="form-label">To User</label>
                     <select name="to_user" class="select2 form-select" id="to_user" required>
                       <option value="" selected>Select To User</option>
                       <?php if($users){ foreach($users as $user){?>
                       <option value="<?=$user->id?>" <?=(($user->id == $to_user)?'selected':'')?>><?=$user->name?></option>
                       <?php } }?>
                     </select>
                  </div>
                  <div class="mb-3 col-md-6">
                     <label for="title" class="form-label">Title</label>
                     <input class="form-control" type="text" id="title" name="title" value="<?=$title?>" required placeholder="Title" />
                  </div>
                  <div class="mb-3 col-md-6">
                     <label for="description" class="form-label">Description</label>
                     <textarea class="form-control" id="description" name="description" required placeholder="Description" rows="5"><?=$description?></textarea>
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