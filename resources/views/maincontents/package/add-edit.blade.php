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
               $description      = $row->description;
               $duration         = $row->duration;
               $price            = $row->price;
               $no_of_users      = $row->no_of_users;
            } else {
               $name             = '';
               $description      = '';
               $duration         = '';
               $price            = '';
               $no_of_users      = '';
            }
            ?>
            <form id="formAccountSettings" action="" method="POST" enctype="multipart/form-data">
               @csrf
               <div class="row">
                  <div class="mb-3 col-md-6">
                     <label for="name" class="form-label">Name</label>
                     <input class="form-control" type="text" id="name" name="name" value="<?=$name?>" required placeholder="Name" autofocus />
                  </div>
                  <div class="mb-3 col-md-6">
                     <label for="duration" class="form-label">Duration</label>
                     <select name="duration" class="select2 form-select" id="duration" required>
                        <option value="" selected>Select Duration</option>
                        <option value="6" <?=(($duration == 6)?'selected':'')?>>6 Months</option>
                        <option value="12" <?=(($duration == 12)?'selected':'')?>>12 Months</option>
                        <option value="18" <?=(($duration == 18)?'selected':'')?>>18 Months</option>
                        <option value="24" <?=(($duration == 24)?'selected':'')?>>24 Months</option>
                        <option value="36" <?=(($duration == 36)?'selected':'')?>>36 Months</option>
                        <option value="48" <?=(($duration == 48)?'selected':'')?>>48 Months</option>
                        <option value="60" <?=(($duration == 60)?'selected':'')?>>60 Months</option>
                     </select>
                  </div>
                  <div class="mb-3 col-md-6">
                     <label for="price" class="form-label">Price</label>
                     <input class="form-control" type="text" id="price" name="price" value="<?=$price?>" required placeholder="Price" autofocus />
                  </div>
                  <div class="mb-3 col-md-6">
                     <label for="no_of_users" class="form-label">No. Of Users</label>
                     <input class="form-control" type="text" id="no_of_users" name="no_of_users" value="<?=$no_of_users?>" required placeholder="No. Of Users" onkeypress="return isNumber(event)" autofocus />
                  </div>
                  <div class="mb-3 col-md-12">
                     <label for="description" class="form-label">Description</label>
                     <textarea name="description" class="form-control" id="ckeditor1"><?=$description?></textarea>
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