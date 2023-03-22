<?php
$result = $conn->query("SELECT * FROM trip WHERE companyID = $id");
$trips = $result->fetch_all(MYSQLI_ASSOC);
$result1 = $conn->query("SELECT * FROM bus_trip WHERE companyID = $id");
$btrips = $result1->fetch_all(MYSQLI_ASSOC);
$result2 = $conn->query("SELECT * FROM routes WHERE companyID = $id");
$routes = $result2->fetch_all(MYSQLI_ASSOC);
$result3 = $conn->query("SELECT * FROM buses WHERE companyID = $id");
$buses = $result3->fetch_all(MYSQLI_ASSOC);

$ftime = "11:00 PM";
?>
<div class="row">
    <div class="col-2 mb-4">
        <label class="h6 text-muted">Filter Terminal</label>
        <select class="select form-select text-muted" name="filter_terminal_weekly" id="filter_terminal_weekly" aria-label="Default select example">
            <option value="" class="text-muted" selected>Select terminal</option>
            <?php foreach ($tripTermIDS as $tripTermID) : ?>
                <option value="<?php echo $tripTermID['terminalID']; ?>">
                    <?php
                    $termidss = $tripTermID['terminalID'];
                    $ress = $conn->query("SELECT terminal_name FROM terminal WHERE id='$termidss' AND companyID='$id'");
                    $trpress = array_values($ress->fetch_assoc());
                    echo $trpress[0];
                    ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="col-2 mb-4">
        <label class="h6 text-muted">Filter bus</label>
        <select class="select form-select text-muted" name="filter_bus_weekly" id="filter_bus_weekly" aria-label="Default select example">
            <option value="" class="text-muted" selected>Select bus</option>
            <?php foreach ($buses as $bus) : ?>
                <option value="<?php echo $bus['id']; ?>"><?php echo $bus['busNumber'] . " (" . $bus['bus_model'] . ") | " . $bus['seat_type'] . " seat type | " . $bus['total_seat'] . " total seat)"   ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="col-5 mb-4">
        <label class="h6 text-muted">Filter Time</label>
        <div class="row">
            <div class="col d-flex flex-row">
                <span class="mt-2 mr-2 text-muted">Start</span>
                <select class="select form-select text-muted" name="filter_bus_start" id="filter_bus_start" aria-label="Default select example">
                    <option value="" class="text-muted" selected>Select Start Time</option>
                    <?php for ($t = 0; $t <= 23; $t++) {
                        $ftimestamp = strtotime($ftime) + 60 * 60;
                        $ftime = date('h:i A', $ftimestamp);
                        echo "<option value=" . $t . ">" . $ftime . "</option>";
                    } ?>
                </select>
            </div>
            <div class="col d-flex flex-row">
                <span class="mt-2 mr-2 text-muted">End</span>
                <select class="select form-select text-muted" name="filter_bus_end" id="filter_bus_end" aria-label="Default select example">
                    <option value="" class="text-muted" selected>Select End Time</option>
                    <?php for ($t = 0; $t <= 23; $t++) {
                        $ftimestamp = strtotime($ftime) + 60 * 60;
                        $ftime = date('h:i A', $ftimestamp);
                        echo "<option value=" . $t . ">" . $ftime . "</option>";
                    } ?>
                </select>
            </div>
        </div>
    </div>
    <div class="col">
        <br>
        <button type="button" name="search_weekly" id="search_weekly" class="btn btn-info mt-1 px-5">Search</button>
    </div>
</div>
<table class="table table-light table-bordered">
    <thead>
        <tr>
            <th style="width:10%;"></th>
            <?php
            $dt_min = new DateTime("last saturday");
            $dt_min->modify('+1 day');
            $dt_max = clone ($dt_min);
            for ($x = 0; $x <= 6; $x++) {
            ?>
                <th style="width:13%;">
                    <?php
                    echo "<h4>" . $dt_max->format('d') . "</h4>";
                    echo "<small class='text-muted'>" . $dt_max->format('l') . "</small>";
                    ?>
                </th>
            <?php $dt_max->modify('+1 days');
            } ?>
        </tr>
    </thead>
    <tbody id="fetchbusdata">

    </tbody>
</table>

<script>
    $('#search_weekly').click(function() {
        var termid = $("#filter_terminal_weekly :selected").val();
        var busid = $("#filter_bus_weekly :selected").val();
        var startv = $("#filter_bus_start :selected").val();
        var endv = $("#filter_bus_end :selected").val();
        var start = $("#filter_bus_start :selected").text();
        var end = $("#filter_bus_end :selected").text();
        if (termid != '' && busid == '' && startv == '' && endv == '') {
            $.ajax({
                type: 'post',
                url: 'extentions/fetchweeklyview.php',
                data: {
                    termid: termid,
                    busid: busid,
                    starttime: startv,
                    endtime: endv
                },
                success: function(data) {
                    $('#fetchbusdata').html(data);
                }
            })
        } else if (termid == '' && busid != '' && startv == '' && endv == '') {
            $.ajax({
                type: 'post',
                url: 'extentions/fetchweeklyview.php',
                data: {
                    termid: termid,
                    busid: busid,
                    starttime: startv,
                    endtime: endv
                },
                success: function(data) {
                    $('#fetchbusdata').html(data);
                }
            })
        } else if (termid != '' && busid != '' && startv == '' && endv == '') {
            $.ajax({
                type: 'post',
                url: 'extentions/fetchweeklyview.php',
                data: {
                    termid: termid,
                    busid: busid,
                    starttime: startv,
                    endtime: endv
                },
                success: function(data) {
                    $('#fetchbusdata').html(data);
                }
            })
        } else if (termid != '' && busid == '' && startv != '0' && endv != '0') {
            $.ajax({
                type: 'post',
                url: 'extentions/fetchweeklyview.php',
                data: {
                    termid: termid,
                    busid: busid,
                    starttime: start,
                    endtime: end
                },
                success: function(data) {
                    $('#fetchbusdata').html(data);
                }
            })
        } else if (termid == '' && busid != '' && startv != '0' && endv != '0') {
            $.ajax({
                type: 'post',
                url: 'extentions/fetchweeklyview.php',
                data: {
                    termid: termid,
                    busid: busid,
                    starttime: start,
                    endtime: end
                },
                success: function(data) {
                    $('#fetchbusdata').html(data);
                }
            })
        } else if (termid != '' && busid != '' && startv != '0' && endv != '0') {
            $.ajax({
                type: 'post',
                url: 'extentions/fetchweeklyview.php',
                data: {
                    termid: termid,
                    busid: busid,
                    starttime: start,
                    endtime: end
                },
                success: function(data) {
                    $('#fetchbusdata').html(data);
                }
            })
        } else if (termid == '' && busid == '' && startv != '0' && endv != '0') {
            $('#fetchbusdata').html('');
        } else if (termid == '' && busid == '' && startv == '0' && endv == '0') {
            $('#fetchbusdata').html('');
        }


    });

    $(function() {
        $('input[name="datetimes"]').daterangepicker({
            timePicker: true,
            startDate: moment().startOf('hour'),
            endDate: moment().startOf('hour').add(32, 'hour'),
            locale: {
                format: 'hh:mm A'
            }
        });
    });
</script>