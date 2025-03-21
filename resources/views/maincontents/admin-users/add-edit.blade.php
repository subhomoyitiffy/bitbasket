<?php
use App\Models\Role;
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
               $user_id          = $row->id;
               $role_id          = $row->role_id;
               $name             = $row->name;
               $email            = $row->email;
               $country_code     = $row->country_code;
               $phone            = $row->phone;
               $profile_image    = $row->profile_image;
               $status           = $row->status;
            } else {
               $user_id          = 0;
               $role_id          = '';
               $name             = '';
               $email            = '';
               $country_code     = '';
               $phone            = '';
               $profile_image    = '';
               $status           = '';
            }
            ?>
            <form id="formAccountSettings" action="" method="POST" enctype="multipart/form-data">
               @csrf
               <div class="mb-3 col-md-12">
                  <label for="role_id" class="form-label">Role</label>
                  <select name="role_id" class="select2 form-select" id="role_id" required>
                    <option value="" selected>Select Role</option>
                    <?php if($roles){ foreach($roles as $role){?>
                    <option value="<?=$role->id?>" <?=(($role->id == $role_id)?'selected':'')?>><?=$role->role_name?></option>
                    <?php } }?>
                  </select>
               </div>
               <div class="row">
                  <div class="mb-3 col-md-12">
                     <label for="name" class="form-label">Name</label>
                     <input class="form-control" type="text" id="name" name="name" value="<?=$name?>" required placeholder="Name" />
                  </div>
               </div>
               <div class="row">
                  <div class="mb-3 col-md-12">
                     <label for="email" class="form-label">Email</label>
                     <input class="form-control" type="email" id="email" name="email" value="<?=$email?>" required placeholder="Email" />
                  </div>
               </div>
               <div class="row">
                  <div class="mb-3 col-md-12">
                     <label for="country_code" class="form-label">Country Code</label>
                     <input class="form-control" type="text" id="country_code" name="country_code" value="<?=$country_code?>" required placeholder="Country Code" />
                  </div>
               </div>
               <div class="row">
                  <div class="mb-3 col-md-12">
                     <label for="phone" class="form-label">Phone</label>
                     <input class="form-control" type="text" id="phone" name="phone" value="<?=$phone?>" required placeholder="Phone" />
                  </div>
               </div>
               <div class="row">
                  <div class="mb-3 col-md-12">
                     <label for="password" class="form-label">Password</label>
                     <input class="form-control" type="password" id="password" name="password" <?=((empty($row))?'required':'')?> placeholder="Password" />
                     <p class="mb-0 text-primary">Leave blank if you do not want to updte password</p>
                  </div>
               </div>
               <div class="mb-3 col-md-12">
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
               <div class="mt-3 mb-3 col-md-12">
                  <div class="d-flex align-items-start align-items-sm-center gap-4">
                     <img src="<?=(($profile_image != '')?env('UPLOADS_URL').$profile_image:env('NO_IMAGE_AVATAR'))?>" alt="<?=$name?>" class="d-block" style="height: 100px; width: 100px; border-radius: 50%;" id="uploadedAvatar" />
                     <div class="button-wrapper">
                        <label for="upload" class="btn btn-primary me-2 mb-4" tabindex="0">
                        <span class="d-none d-sm-block">Upload new photo</span>
                        <i class="bx bx-upload d-block d-sm-none"></i>
                        <input type="file" id="upload" class="account-file-input" name="profile_image" hidden accept="image/png, image/jpeg, image/jpg, image/webp, image/avif, image/gif" />
                        </label>
                        <?php if(!empty($row)){?>
                           <?php $pageLink = Request::url(); ?>
                           <a href="<?=url('common-delete-image/' . Helper::encoded($pageLink) . '/users/profile_image/id/' . $user_id)?>" class="btn btn-label-secondary account-image-reset mb-4" onclick="return confirm('Do you want to remove this image ?');">
                              <i class="bx bx-reset d-block d-sm-none"></i>
                              <span class="d-none d-sm-block">Reset</span>
                           </a>
                        <?php }?>
                        <p class="mb-0 text-primary">Allowed JPG, GIF, PNG, JPEG, WEBP, AVIF</p>
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