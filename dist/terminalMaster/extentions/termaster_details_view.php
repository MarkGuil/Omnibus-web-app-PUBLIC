<div class="row bg-light border-bottom px-4 py-3">
     <div class="col">
          <h6 class="text-secondary mt-2">Terminal Master Details</h6>
     </div>
     <!-- <div class="col"><a href="" class="btn btn-secondary py-1 float-right"><i class="fas fa-pen mr-2 "></i> Edit</a></div> -->
</div>
<?php if (isset($_SESSION['compTerMassuccess'])) { ?>
     <div class="alert alert-success mx-3 mt-3" role="alert">
          <form action="" method="get" id="unsetSuccessAlert">
               <button type="submit" class="close" name="unsetSuccessAlert" form="unsetSuccessAlert">&times;</button>
          </form>
          <?php echo $_SESSION['compTerMassuccess']  ?>
     </div>
<?php } ?>
<div class="mx-5 mt-4">
     <div class="row bg-light p-2 ">
          <div class="col-md-3">
               <h6 class="text-secondary mt-2">Terminal Master Name</h6>
          </div>
          <div class="col-md-9">
               <h6 class="text-secondary mt-2"><?php echo $detail['fullname'] ?></h6>
          </div>
     </div>
     <div class="row bg-white p-2 ">
          <h6 class="text-secondary mt-2">Terminal Master Address</h6>
     </div>
     <div class="row bg-white p-2 ">
          <div class="col-md-3">
               <h6 class="text-secondary mt-2"> &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp Street</h6>
          </div>
          <div class="col-md-9">
               <h6 class="text-secondary mt-2"><?php echo $detail['street_address'] ?></h6>
          </div>
     </div>
     <div class="row bg-white p-2 ">
          <div class="col-md-3">
               <h6 class="text-secondary mt-2"> &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp City</h6>
          </div>
          <div class="col-md-9">
               <h6 class="text-secondary mt-2"><?php echo $detail['city'] ?></h6>
          </div>
     </div>
     <div class="row bg-white p-2 ">
          <div class="col-md-3">
               <h6 class="text-secondary mt-2"> &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp Province</h6>
          </div>
          <div class="col-md-9">
               <h6 class="text-secondary mt-2"><?php echo $detail['province'] ?></h6>
          </div>
     </div>
     <div class="row bg-light p-2 ">
          <div class="col-md-3">
               <h6 class="text-secondary mt-2">Contact Number</h6>
          </div>
          <div class="col-md-9">
               <h6 class="text-secondary mt-2"><?php echo $detail['connumber'] ?></h6>
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
     <div class="row bg-white p-2 border-bottom border-light">
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
<div class="row bg-light border-bottom mt-4 px-4 py-2 rounded" style="border-width: 2px">
</div>