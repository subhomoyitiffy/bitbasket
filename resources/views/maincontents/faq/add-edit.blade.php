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
               $faq_category_id      = $row->faq_category_id;
               $question             = $row->question;
               $answer               = $row->answer;
            } else {
               $faq_category_id      = '';
               $question             = '';
               $answer               = '';
            }
            ?>
            <form id="formAccountSettings" action="" method="POST" enctype="multipart/form-data">
               @csrf
               <div class="row">
                  <div class="mb-3 col-md-12">
                     <label for="faq_category_id" class="form-label">Name</label>
                     <select name="faq_category_id" class="select2 form-select" id="faq_category_id" required>
                       <option value="" selected>Select FAQ Category</option>
                       <?php if($cats){ foreach($cats as $row){?>
                       <option value="<?=$row->id?>" <?=(($row->id == $faq_category_id)?'selected':'')?>><?=$row->name?></option>
                       <?php } }?>
                     </select>
                  </div>
                  <div class="mb-3 col-md-6">
                     <label for="question" class="form-label">Question</label>
                     <textarea name="question" class="form-control" id="question" rows="5"><?=$question?></textarea>
                  </div>
                  <div class="mb-3 col-md-6">
                     <label for="answer" class="form-label">Answer</label>
                     <textarea name="answer" class="form-control" id="answer" rows="5"><?=$answer?></textarea>
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