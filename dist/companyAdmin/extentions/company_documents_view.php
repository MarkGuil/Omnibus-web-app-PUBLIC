<div class="row bg-light border-bottom px-4 py-3">
    <div class="col">
        <h6 class="text-secondary mt-2"><b>Documents</b></h6>
    </div>
    <!--<div class="col"><a href="" class="btn btn-primary py-1 float-right" data-bs-toggle="modal" data-bs-target="#guidelines_modal"><i class="fas fa-pen mr-2 "></i> Add Guideline</a></div>-->
</div>
<div class="mx-5 mt-4">
    <table class="table table-stripeds table-borderless">
        <thead class="bg-light">
            <tr class="">
                <th class="text-secondary py-3 border-bottom">File Name</th>
                <th class="w-75 text-secondary py-3 border-bottom">Verification</th>
                <th class="text-secondary py-3 border-bottom">Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($compfiles as $compfile) : ?>
                <tr>
                    <td class="" id="filename<?php echo $compfile['id'] ?>"><?php echo $compfile['file_Name'] ?></td>
                    <td class="" id="">
                        <?php
                        $fileV = $compfile["verified"];
                        if ($fileV == 0) {
                            echo "<p class='text-muted '>Pending</p>";
                        } elseif ($fileV != 0) {
                            echo "<p class='text-muted '>Verified</p>";
                        }
                        ?>
                    </td>
                    <td style="width:100%">
                        <div class="row">
                            <?php
                            $file = $compfile['file_Name'];
                            $fileV = $compfile["verified"];
                            $extention = pathinfo($file, PATHINFO_EXTENSION);
                            if (in_array($extention, ['jpeg', 'jpg', 'png'])) { ?>
                                <div class="col-4">
                                    <a href="#imagemodal" class="btn btn-link py-1" data-id="uploads/<?php echo $compfile['file_Name']; ?>" data-bs-toggle="modal" data-bs-target="#imagemodal"><i class="fas fa-eye text-primary"></i></a>
                                </div>
                            <?php } else if (in_array($extention, ['pdf'])) { ?>
                                <div class="col-4">
                                    <form action="" method="POST">
                                        <button class="btn btn-link py-1" name="viewFilePDF" value="<?php echo $compfile['file_Name']; ?>"><i class="fas fa-eye text-primary"></i></button>
                                    </form>
                                </div>
                            <?php } ?>
                        </div>
                    </td>
                </tr>
            <?php endforeach ?>
        </tbody>
    </table>
</div>