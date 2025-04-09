<?php
use App\Helpers\Helper;
$controllerRoute = $module['controller_route'];
?>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/bbbootstrap/libraries@main/choices.min.css">
<script src="https://cdn.jsdelivr.net/gh/bbbootstrap/libraries@main/choices.min.js"></script>

<style type="text/css">
    .choices__list--multiple .choices__item {
        background-color: #010f16;
        border: 1px solid #010f16;
    }
</style>
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
               $parent_id                       = $row->parent_id;
               $first_name                      = $row2->first_name;
               $last_name                       = $row2->last_name;
               $email                           = $row->email;
               $country_code                    = $row->country_code;
               $country                         = $row2->country;
               $phone                           = $row->phone;
               $profile_image                   = $row->profile_image;
               $city_id                         = $row2->city_id;
               $subjects                        = (($row2->subjects != '')?json_decode($row2->subjects):[]);
               $status                          = $row->status;
            } else {
               $parent_id                       = '';
               $first_name                      = '';
               $last_name                       = '';
               $email                           = '';
               $country_code                    = 229;
               $country                         = 'UAE';
               $phone                           = '';
               $profile_image                   = '';
               $city_id                         = '';
               $subjects                        = [];
               $status                          = '';
            }
            ?>
            <form id="formAccountSettings" action="" method="POST" enctype="multipart/form-data">
               @csrf
               <div class="row">
                  <div class="mb-3 col-md-6">
                     <label for="parent_id" class="form-label">Parent Member</label>
                     <select name="parent_id" class="select2 form-select" id="parent_id" required>
                        <option value="" selected>Select Parent Member</option>
                        <?php if($parentUsers){ foreach($parentUsers as $parentUser){?>
                           <option value="<?=$parentUser->id?>" <?=(($parent_id == $parentUser->id)?'selected':'')?>><?=$parentUser->name?></option>
                        <?php } }?>
                     </select>
                  </div>
                  <div class="mb-3 col-md-6">
                     <label for="first_name" class="form-label">First Name</label>
                     <input class="form-control" type="text" id="first_name" name="first_name" value="<?=$first_name?>" required placeholder="First Name" autofocus />
                  </div>
                  <div class="mb-3 col-md-6">
                     <label for="last_name" class="form-label">Last Name</label>
                     <input class="form-control" type="text" id="last_name" name="last_name" value="<?=$last_name?>" required placeholder="Last Name" />
                  </div>

                  <div class="mb-3 col-md-6">
                     <label for="email" class="form-label">Email</label>
                     <input class="form-control" type="email" id="email" name="email" value="<?=$email?>" required placeholder="Email" />
                  </div>
                  <div class="mb-3 col-md-6">
                     <label for="phone" class="form-label">Phone</label>
                     <input class="form-control" type="text" id="phone" name="phone" value="<?=$phone?>" required placeholder="Phone" />
                  </div>

                  <div class="mb-3 col-md-6">
                     <label for="country_code" class="form-label">Country Code</label>
                     <input class="form-control" type="text" id="country_code" name="country_code" value="<?=$country_code?>" placeholder="Country Code" />
                  </div>
                  <div class="mb-3 col-md-6">
                     <label for="country" class="form-label">Country</label>
                     <input class="form-control" type="text" id="country" name="country" value="<?=$country?>" placeholder="Country" />
                  </div>

                  <div class="mb-3 col-md-6">
                     <label for="city_id" class="form-label">City</label>
                     <select name="city_id" class="select2 form-select" id="city_id" required>
                        <option value="" selected>Select City</option>
                        <?php if($states){ foreach($states as $state){?>
                           <option value="<?=$state->id?>" <?=(($city_id == $state->id)?'selected':'')?>><?=$state->name?></option>
                        <?php } }?>
                     </select>
                  </div>
                  
                  <div class="mb-3 col-md-6">
                     <label for="choices-multiple-remove-button" class="form-label">Subjects</label>
                     <select name="subjects[]" class="form-control" id="choices-multiple-remove-button" multiple required>
                        <?php if($subjectList){ foreach($subjectList as $subjectRow){?>
                           <option value="<?=$subjectRow->id?>" <?=((in_array($subjectRow->id, $subjects))?'selected':'')?>><?=$subjectRow->name?></option>
                        <?php } }?>
                     </select>
                  </div>
                  <div class="mb-3 col-md-6">
                     <label for="status" class="form-label">Status</label>
                     <select name="status" class="select2 form-select" id="status" required>
                        <option value="" selected>Select Status</option>
                        <option value="0" <?=(($status == 0)?'selected':'')?>>Verification pending</option>
                        <option value="1" <?=(($status == 1)?'selected':'')?>>Verified</option>
                        <option value="2" <?=(($status == 2)?'selected':'')?>>Declined by admin</option>
                     </select>
                  </div>

                  <div class="mb-3 col-md-12">
                     <div class="d-flex align-items-start align-items-sm-center gap-4">
                        <img src="<?=(($profile_image != '')?env('UPLOADS_URL').'user/'.$profile_image:env('NO_IMAGE_AVATAR'))?>" alt="<?=$first_name?>" class="d-block" height="100" width="100" id="uploadedAvatar" style="border-radius:50%;" />
                        <div class="button-wrapper">
                           <label for="upload" class="btn btn-primary me-2 mb-4" tabindex="0">
                           <span class="d-none d-sm-block">Upload new photo</span>
                           <i class="bx bx-upload d-block d-sm-none"></i>
                           <input type="file" id="upload" class="account-file-input" name="profile_image" hidden accept="image/png, image/jpeg, image/jpg, image/webp, image/avif, image/gif" />
                           </label>
                           <?php
                           $pageLink = Request::url();
                           ?>
                           <a href="<?=url('common-delete-image/' . Helper::encoded($pageLink) . '/users/profile_image/id/' . (($user)?$user->id:0))?>" class="btn btn-label-secondary account-image-reset mb-4" onclick="return confirm('Do you want to remove this image ?');">
                              <i class="bx bx-reset d-block d-sm-none"></i>
                              <span class="d-none d-sm-block">Reset</span>
                           </a>
                           <p class="mb-0">Allowed JPG, GIF, PNG, JPEG, WEBP, AVIF</p>
                        </div>
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
<script type="text/javascript">
   function isNumber(evt) {
      evt = (evt) ? evt : window.event;
      var charCode = (evt.which) ? evt.which : evt.keyCode;
      if (charCode > 31 && (charCode < 48 || charCode > 57)) {
         return false;
      }
      return true;
   }
</script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
<script type="text/javascript">
    $(document).ready(function(){    
        var multipleCancelButton = new Choices('#choices-multiple-remove-button', {
            removeItemButton: true,
            maxItemCount:30,
            searchResultLimit:30,
            renderChoiceLimit:30
        });     
    });
</script>