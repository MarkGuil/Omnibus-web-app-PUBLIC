<?php

$result223 = $conn->query("SELECT a.id,a.companyID,a.terminalID,a.bustripID,a.customerID,a.origin,a.destination,a.duration,a.departure_time,a.number_of_seats,a.fare_amount,a.total_amount,a.payment_status,a.booked_at,a.booking_status,a.reference_id,b.trip_date,b.taken_seat,b.tripID,b.busID FROM booking_tbl a RIGHT OUTER JOIN bus_trip b ON(a.bustripID = b.id) WHERE a.companyID = '$compID' AND a.terminalID = '" . $terminaldestino[0] . "' ");
$details = $result223->fetch_all(MYSQLI_ASSOC);
?>
<table id="bookTable" class="table table-stripeds table-borderless">
    <thead class="bg-light">
        <tr class="">
            <th class="text-secondary py-3 border-bottom">#</th>
            <th class="text-secondary py-3 border-bottom">Reference no<br>
            <th class="text-secondary py-3 border-bottom">Routes<br>
                <small>origin-destination</small>
            </th>
            <th class="text-secondary py-3 border-bottom">Customer</th>
            <th class="text-secondary py-3 border-bottom">Number of Seats</th>
            <th class="text-secondary py-3 border-bottom">Total Amount</th>
            <th class="text-secondary py-3 border-bottom">Date</th>
            <th class="text-secondary py-3 border-bottom">Departure Time</th>
            <th class="text-secondary py-3 border-bottom">Booking Status</th>
            <th class="text-secondary py-3 border-bottom">Action</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($details as $detail) :
            if (date("Y/m/d", strtotime($detail["trip_date"])) == date("Y/m/d")  && $detail["booking_status"] == 'confirmed') { ?>
                <tr>
                    <td><?php echo $x++; ?></td>
                    <td class=""><?php echo $detail["reference_id"]; ?></td>
                    <td class=""><?php echo $tripid = $detail["origin"] . ' - ' . $detail["destination"]; ?></td>
                    <td class="">
                        <?php
                        $userid = $detail["customerID"];
                        $result3 = $conn->query("SELECT fullname FROM user_customer WHERE id = '$userid'");
                        $customer = array_values($result3->fetch_assoc());
                        echo $customer[0];
                        ?>
                    </td>
                    <td class=""><?php echo $detail["number_of_seats"]; ?></td>
                    <td class=""><?php echo 'Php' . $detail["total_amount"]; ?></td>
                    <td class=""><?php echo $detail["trip_date"]; ?></td>
                    <td class=""><?php echo date('h:i A', strtotime($detail['departure_time'])) ?></td>
                    <td class="">
                        <?php $status = $detail["booking_status"];
                        switch ($status) {
                            case 'confirmed': ?>
                                <div class="dropdown px-0">
                                    <button class="btn btn-link dropdown-toggle px-0" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        Confirmed
                                    </button>
                                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                        <form method="post">
                                            <input type="hidden" name="bustripID" value="<?php echo $detail["bustripID"]; ?>">
                                            <input type="hidden" name="taken_seat" value="<?php echo $detail["taken_seat"]; ?>">
                                            <input type="hidden" name="totalSeats" value="<?php echo $detail["number_of_seats"]; ?>">
                                            <button class="dropdown-item" type="submit" name="canbook" value="<?php echo $detail['id']; ?>">Cancel Booking</button>
                                            <button class="dropdown-item" type="submit" name="travelled" value="<?php echo $detail['id']; ?>">Travelled</button>
                                        </form>
                                    </div>
                                </div><?php
                                        break;
                                    case 'requested for cancellation':
                                        ?>
                                <div class="dropdown px-0">
                                    <button class="btn btn-link dropdown-toggle px-0" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        Requested for cancellation
                                    </button>
                                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                        <form method="post">
                                            <input type="hidden" name="bustripID" value="<?php echo $detail["bustripID"]; ?>">
                                            <input type="hidden" name="taken_seat" value="<?php echo $detail["taken_seat"]; ?>">
                                            <input type="hidden" name="totalSeats" value="<?php echo $detail["number_of_seats"]; ?>">
                                            <button class="dropdown-item" type="submit" name="canbook" value="<?php echo $detail['id']; ?>">Cancel Booking</button>
                                        </form>
                                    </div>
                                </div><?php
                                        break;
                                    case 'travelled':
                                        ?>
                                <div class="">
                                    <span>Travelled</span>
                                </div><?php
                                        break;
                                    case 'cancelled':
                                        ?>
                                <div class="">
                                    <span>Cancelled</span>
                                </div><?php
                                        break;
                                        // default:
                                        ?>
                                <!-- <div class='dropdown'>
                    <button class='btn btn-link dropdown-toggle' type='button' id='dropdownMenuButton' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>
                        Pending
                    </button>
                    <div class='dropdown-menu' aria-labelledby='dropdownMenuButton'>
                        <form method="post" >
                            <button class="dropdown-item" type="submit" name="conbook" value="<?php echo $detail['id']; ?>">Confirm Booking</button>
                            <button class="dropdown-item" type="submit" name="canbook" value="<?php echo $detail['id']; ?>">Cancel Booking</button>
                        </form>
                    </div>
                    </div> -->
                        <?php
                                        // break;
                                }
                        ?>
                    </td>
                    <td>
                        <a href="customer_booking_details.php?i=<?php echo $detail['id']; ?>" class="edit" data-userid="<?php echo $detail['id']; ?>"><i class="fas fa-file-alt text-success"></i></a>
                    </td>
                </tr>
        <?php }
        endforeach ?>
    </tbody>
</table>