<div class="row bg-light border-bottom px-4 py-3">
    <div class="col">
        <h6 class="text-secondary mt-2">Update Account Details</h6>
    </div>
    <!-- <div class="col"><a href="" class="btn btn-secondary py-1 float-right"><i class="fas fa-pen mr-2 "></i> Edit</a></div> -->
</div>
<div class=" mt-4 p-0">
    <div class="row justify-content-around mx-1">
        <div class="col-md-11 border p-0 pb-3">
            <h6 class="text-secondary bg-light p-3 border-bottom">Name and Address</h6>
            <div class="container contaciner-small mt-3">
                <form class="form" id="company_details_change" action="" method="Post">
                    <div class="row">
                        <div class="col-md-3">
                            <h6 class="text-secondary mt-2">Terminal Master Name</h6>
                        </div>
                        <div class="col-md-9">
                            <input type="text" class="form-control" name="terName" value="<?php echo $detail['fullname'] ?>" required>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <h6 class="text-secondary mt-2">Terminal Master Address</h6>
                    </div>
                    <div class="row mt-2">
                        <div class="col-md-3">
                            <h6 class="text-secondary mt-2">Street Address</h6>
                        </div>
                        <div class="col-md-9">
                            <input type="text" class="form-control" name="saddress" value="<?php echo $detail['street_address'] ?>" required>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-md-3">
                            <h6 class="text-secondary mt-2">City</h6>
                        </div>
                        <div class="col-md-9">
                            <input type="text" class="form-control" name="city" value="<?php echo $detail['city'] ?>" required>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-md-3">
                            <h6 class="text-secondary mt-2">Province</h6>
                        </div>
                        <div class="col-md-9">
                            <input type="text" class="form-control" name="province" value="<?php echo $detail['province'] ?>" required>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary mt-2 float-right" name="company_details_change" form="company_details_change">Save Changes</button>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="row bg-light border-bottom mt-5 px-4 py-3" style="border-width: 2px">
    <div class="col">
        <h6 class="text-secondary mt-2">Update Contact Details</h6>
    </div>
</div>
<div class=" mt-4 p-0">
    <div class="row justify-content-around mx-1">
        <div class="col-md-5 border p-0 pb-3">
            <h6 class="text-secondary bg-light p-3 border-bottom">Email</h6>
            <div class="container contaciner-small mt-3">
                <!-- <div class="alert alert-warning mt-3" role="alert" >
                                    <small>You will need to log in </small>
                                </div> -->
                <form class="form" id="email_change" action="" method="Post">
                    <input type="text" class="form-control" name="email" value="<?php echo $detail['email'] ?>" required>
                    <button type="submit" class="btn btn-primary mt-2 float-right" name="email_change" form="email_change">Save email</button>
                </form>
            </div>
        </div>
        <div class="col-md-5 border p-0 pb-3">
            <h6 class="text-secondary bg-light p-3 border-bottom">Contact Number</h6>
            <div class="container contaciner-small mt-3">
                <form class="form" id="contact_number_change" action="" method="Post">
                    <input type="text" class="form-control" name="cnumber" value="<?php echo $detail['connumber'] ?>" placeholder="" maxlength="11" size="11" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');" required>
                    <button type="submit" class="btn btn-primary mt-2 float-right" name="contact_number_change" form="contact_number_change">Save Contact Number</button>
                </form>
            </div>
        </div>
    </div>
</div>
<div class="row bg-light border-bottom mt-4 px-4 py-2 rounded" style="border-width: 2px">
</div>