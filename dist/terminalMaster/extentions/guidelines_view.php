<div class="row bg-light border-bottom px-4 py-3">
    <div class="col">
        <h6 class="text-secondary mt-2"><b>Guidelines</b></h6>
    </div>
</div>

<div class="mx-5 mt-4">
    <table class="table table-stripeds table-borderless">
        <thead class="bg-light">
            <tr class="">
                <th class="text-secondary py-3 border-bottom">#</th>
                <th class="w-75 text-secondary py-3 border-bottom">Guides</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($guides as $guide) : ?>
                <tr>
                    <td><?php echo $x++; ?></td>
                    <td class="" id="guidetext<?php echo $guide['id']; ?>"><?php echo $guide["guideline"]; ?></td>
                </tr>
            <?php endforeach ?>
        </tbody>
    </table>
</div>