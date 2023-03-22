<div class="row bg-light border-bottom px-4 py-3">
    <div class="col">
        <h6 class="text-secondary mt-2">Update Terminal Master Details</h6>
    </div>
    <!-- <div class="col"><a href="" class="btn btn-secondary py-1 float-right"><i class="fas fa-pen mr-2 "></i> Edit</a></div> -->
</div>
<div class=" mt-4 p-0">
    <div class="row justify-content-around mx-1">
        <div class="col-md-8 border p-0 pb-3">
            <h6 class="text-secondary bg-light p-3 border-bottom">Change Password</h6>
            <div class="container contaciner-small mt-3">
                <form class="form" id="change_password" action="" method="Post" oninput='password2.setCustomValidity(password2.value != password1.value ? "Passwords do not match." : "")'>
                    <?php include('errors.php'); ?>
                    <div class="row">
                        <div class="col-md-3">
                            <h6 class="text-secondary mt-2">Old Password</h6>
                        </div>
                        <div class="col-md-9">
                            <input type="password" class="form-control" name="oldpassword" required>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-md-3">
                            <h6 class="text-secondary mt-2">New Password</h6>
                        </div>
                        <div class="col-md-9">
                            <input type="password" class="form-control" name="password1" required>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-md-3">
                            <h6 class="text-secondary mt-2">Confirm Password</h6>
                        </div>
                        <div class="col-md-9">
                            <input type="password" class="form-control" name="password2" required>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary mt-2 float-right" name="change_password" form="change_password">Save Changes</button>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="row bg-light border-bottom mt-4 px-4 py-2 rounded" style="border-width: 2px">
</div>