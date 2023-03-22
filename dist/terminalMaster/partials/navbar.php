<nav class="navbar navbar-dark navbar-expand-lg navbar-light bg-dark shadow-lg py-3 px-5">
    <a class="navbar-brand mr-4" href="starting_home.php"><b>Omnibus</b><span class="text-light"> &nbsp; Terminal Master</span></a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse justify-content-end" id="navbarNavAltMarkup">
        <?php if (isset($_SESSION['compTerMasID'])) : ?>
            <a class="btn btn-secondary ml-3" href="starting_home.php?logout='1'">Logout</a>
        <?php endif ?>
    </div>
</nav>