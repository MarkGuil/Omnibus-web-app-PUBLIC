<div class="row bg-light border-bottom px-4 py-3">
     <div class="col">
          <h6 class="text-secondary mt-2">Company Details</h6>
     </div>
     <!-- <div class="col"><a href="" class="btn btn-secondary py-1 float-right"><i class="fas fa-pen mr-2 "></i> Edit</a></div> -->
</div>
<?php if (isset($_SESSION['compadminsuccess'])) { ?>
     <div class="alert alert-success mx-3 mt-3 " role="alert">
          <form action="" method="get" class="d-flex justify-content-between" id="unsetSuccessAlert">
               <?php echo $_SESSION['compadminsuccess']  ?>
               <button type="submit" name="unsetSuccessAlert" form="unsetSuccessAlert" class="btn-close" aria-label="Close"></button>
          </form>
     </div>
<?php } ?>
<div class="mx-5 mt-4">
     <div class="row bg-light p-2 ">
          <div class="col-md-3">
               <h6 class="text-secondary mt-2">Company Name</h6>
          </div>
          <div class="col-md-9">
               <h6 class="text-secondary mt-2"><?php echo $detail['companyName'] ?></h6>
          </div>
     </div>
     <div class="row bg-white p-2 ">
          <div class="col-md-3">
               <h6 class="text-secondary mt-2">Company Address</h6>
          </div>
          <div class="col-md-9">
               <h6 class="text-secondary mt-2"><?php echo $detail['companyAddress'] ?></h6>
          </div>
     </div>
     <div class="row bg-light p-2 ">
          <div class="col-md-3">
               <h6 class="text-secondary mt-2">Contact Number</h6>
          </div>
          <div class="col-md-9">
               <h6 class="text-secondary mt-2"><?php echo $detail['contactNumber'] ?></h6>
          </div>
     </div>
     <div class="row bg-white p-2 border-bottom border-light">
          <div class="col-md-3">
               <h6 class="text-secondary mt-2">Email</h6>
          </div>
          <div class="col-md-9">
               <h6 class="text-secondary mt-2"><?php echo $detail['email'] ?></h6>
          </div>
     </div>
     <div class="row bg-light p-2 border-bottom border-light">
          <div class="col-md-3">
               <h6 class="text-secondary mt-2">Email Verification</h6>
          </div>
          <div class="col-md-9">
               <?php
               if ($detail['email_verified_at'] == NULL) {
                    echo "<h6 class='text-danger mt-2'>Not Verified <i class='fas fa-times-circle'></i><a href='#' role='button' type='button' class='text-primary px-3 py-0' data-toggle='modal' data-target='#verifyEmailModal'><small>Verify now</small></a></h6> ";
               } else echo "<h6 class='text-success mt-2'>Verified <i class='fas fa-check-circle'></i></h6>";
               ?>
          </div>
     </div>
</div>
<div class="row bg-light border-bottom mt-5 px-4 py-3" style="border-width: 2px">
     <div class="col">
          <h6 class="text-secondary mt-2">Contact Person/ Account Manager</h6>
     </div>
     <div class="col text-end"><button type="button" class="btn btn-primary py-1" data-bs-toggle="modal" data-bs-target="#editContactPersonModal"><i class="fas fa-pen mr-2 "></i> Edit</button></div>
</div>
<div class="mx-5 mt-4">
     <div class="row bg-light p-2 ">
          <div class="col-md-3">
               <h6 class="text-secondary mt-2">Fullname</h6>
          </div>
          <div class="col-md-9">
               <h6 id="cpFullname<?php echo $id ?>" class="text-secondary mt-2"><?php echo $detail['fullname'] ?></h6>
          </div>
     </div>
     <div class="row bg-white p-2 border-bottom border-light">
          <div class="col-md-3">
               <h6 class="text-secondary mt-2">Position</h6>
          </div>
          <div class="col-md-9">
               <h6 id="cpPosition<?php echo $id ?>" class="text-secondary mt-2"><?php echo $detail['position'] ?></h6>
          </div>
     </div>
</div>
<div class="row bg-light border-bottom mt-4 px-4 py-2 rounded" style="border-width: 2px">
</div>