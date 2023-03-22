<nav class="position-relative navbar navbar-dark navbar-expand-lg navbar-light bg-dark shadow-lg py-3 px-4 ">
    <a class="navbar-brand mr-4" href="home_admin_details.php"><b>Omnibus</b><span class="text-primary"> Company Admin</span></a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse justify-content-end" id="navbarNavAltMarkup">
        <?php if (isset($_SESSION['compadminName']) && $_SESSION['compadminName'] == "Demo User") { ?>
            <a class="btn btn-secondary ml-3" href="../validation/logina.php">Logout</a>
        <?php } else { ?>
            <a class="btn btn-secondary ml-3" href="starting_home.php?logout='1'">Logout</a>
        <?php } ?>
    </div>
</nav>