<div class="row bg-light border-bottom px-4 py-3">
    <div class="col">
        <h6 class="text-secondary mt-2"><b>Guidelines</b></h6>
    </div>
    <div class="col text-end">
        <a href="" class="btn btn-primary py-1" data-bs-toggle="modal" data-bs-target="#guidelines_modal"><i class="fas fa-pen mr-2 "></i>
            Add Guideline
        </a>
    </div>
</div>
<div class="mx-5 mt-4">
    <table class="table table-stripeds table-borderless">
        <thead class="bg-light">
            <tr class="">
                <th class="text-secondary py-3 border-bottom">ID</th>
                <th class="w-75 text-secondary py-3 border-bottom">Guides</th>
                <th class="text-secondary py-3 border-bottom">Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($guides as $guide) : ?>
                <tr>
                    <td><?php echo $x++; ?></td>
                    <td class="" id="guidetext<?php echo $guide['id']; ?>"><?php echo $guide["guideline"]; ?></td>
                    <td>
                        <a href="home_admin_details.php#mymodaleditguide" class="mr-2" data-bs-toggle="modal" data-userid="<?php echo $guide['id']; ?>"><i class="material-icons text-warning" data-toggle="tooltip" title="Edit">&#xE254;</i></a>
                        <a href="home_admin_details.php#mymodaldeleteguide" data-bs-toggle="modal" data-userid="<?php echo $guide['id']; ?>"><i class="material-icons text-danger" data-toggle="tooltip" title="Delete">&#xE872;</i></a>
                    </td>
                </tr>
            <?php endforeach ?>
        </tbody>
    </table>
</div>