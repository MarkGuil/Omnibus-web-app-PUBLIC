<div class="row bg-light border-bottom px-4 py-3">
    <div class="col">
        <h6 class="text-secondary mt-2">Payment Details</h6>
    </div>
    <!-- <div class="col"><a href="" class="btn btn-secondary py-1 float-right"><i class="fas fa-pen mr-2 "></i> Edit</a></div> -->
</div>
<?php if (isset($_SESSION['compadminsuccess'])) { ?>
    <div class="alert alert-success mx-3 mt-3" role="alert">
        <form action="" method="get" class="d-flex justify-content-between" id="unsetSuccessAlert">
            <?php echo $_SESSION['compadminsuccess']  ?>
            <button type="submit" class="close" name="unsetSuccessAlert" form="unsetSuccessAlert" class="btn-close" aria-label="Close"></button>
        </form>
    </div>
<?php } ?>
<div class="mx-5 mt-4">
    <div class="alert alert-info" role="alert">
        Supported Online Payments:
        <li>PayPal</li>
    </div>
    <?php if (!empty($payments)) {
        foreach ($payments as $payment) { ?>
            <form class="form" id="company_payment_change" action="" method="Post">
                <div class="row bg-light p-2 ">
                    <div class="col-md-3">
                        <h6 class="text-secondary mt-2">PayPal Email</h6>
                    </div>
                    <div class="col-md-9 d-flex flex-row">
                        <input type="hidden" class="form-control" name="paypalid" value="<?php echo $payment['id'] ?>" required>
                        <input type="text" class="form-control" name="paypalEmail" placeholder="Please enter your PayPal email to accept payments" value="<?php echo $payment['paypal_email'] ?>" required>
                        <button type="submit" class="btn btn-primary " name="company_payment_change" form="company_payment_change">Save Changes</button>
                    </div>
                </div>
            </form>
        <?php }
    } else { ?>
        <form class="form" id="company_payment_new" action="" method="Post">
            <div class="row bg-light p-2 ">
                <div class="col-md-3">
                    <h6 class="text-secondary mt-2">PayPal Email</h6>
                </div>
                <div class="col-md-9 d-flex flex-row">
                    <input type="text" class="form-control" name="newpaypalEmail" placeholder="Please enter your PayPal email to accept payments" required>
                    <button type="submit" class="btn btn-primary " name="company_payment_new" form="company_payment_new">Save Changes</button>
                </div>
            </div>
        </form>
    <?php } ?>
</div>