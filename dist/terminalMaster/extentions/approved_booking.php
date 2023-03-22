<table id="bookTable" class="table table-stripeds table-borderless">
  <thead class="bg-light">
    <tr class="">
      <th class="text-secondary py-3 border-bottom">Booking ID</th>
      <th class="text-secondary py-3 border-bottom">Routes<br>
        <small>origin-destination</small>
      </th>
      <th class="text-secondary py-3 border-bottom">Customer</th>
      <th class="text-secondary py-3 border-bottom">Number of Seats</th>
      <th class="text-secondary py-3 border-bottom">Fare Amount</th>
      <th class="text-secondary py-3 border-bottom">Total Amount</th>
      <th class="text-secondary py-3 border-bottom">Date</th>
      <th class="text-secondary py-3 border-bottom">Change Status</th>
      <th class="text-secondary py-3 border-bottom">Action</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($bookingDetails as $detail) :

      $tripID = $detail["tripID"];
      $result5 = $conn->query("SELECT terminalID FROM trips WHERE id = '$tripID'");

      $status = $detail["booking_status"];
      if ($status == '1') {
        if ($terminaldestino == array_values($result5->fetch_assoc()))

    ?>
        <tr>

          <td><?php echo $detail["id"] ?></td>
          <td class="">
            <?php
            $tripid = $detail["tripID"];
            $result4 = $conn->query("SELECT origin FROM trips WHERE id = '$tripid'");
            $result5 = $conn->query("SELECT destination FROM trips WHERE id = '$tripid'");
            $origin = array_values($result4->fetch_assoc());
            $destination = array_values($result5->fetch_assoc());
            echo $origin[0];
            echo "-";
            echo $destination[0];
            ?>
          </td>
          <td class="">
            <?php
            $userid = $detail["customerID"];
            $result3 = $conn->query("SELECT fullname FROM user_customer WHERE id = '$userid'");
            $customer = array_values($result3->fetch_assoc());
            echo $customer[0];
            ?>
          </td>
          <td class=""><?php echo $detail["number_of_seats"]; ?></td>
          <td class=""><?php echo $detail["fare_amount"]; ?></td>
          <td class=""><?php echo $detail["total_amount"]; ?></td>
          <td class=""><?php echo $detail["booking_date"]; ?></td>
          <td class="">
            <?php $status = $detail["booking_status"];
            switch ($status) {
              case '1': ?>
                <div class='dropdown'>
                  <button class='btn btn-link dropdown-toggle' type='button' id='dropdownMenuButton' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>
                    Confirmed
                  </button>
                  <div class='dropdown-menu' aria-labelledby='dropdownMenuButton'>
                    <form method='post' id='canbook'>
                      <button class='dropdown-item ' type='submit' name='canbook' form='canbook' value="<?php echo $detail['id']; ?>">Cancel Booking</button>
                    </form>
                  </div>
                </div><?php
                      break;
                    case '2':
                      ?>
                <div class="dropdown">
                  <button class="btn btn-link dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Cancelled
                  </button>
                  <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                    <form method="post" id="conbook">
                      <button class="dropdown-item" type="submit" name="conbook" form="conbook" value="<?php echo $detail['id']; ?>">Confirm Booking</button>
                    </form>
                  </div>
                </div><?php
                      break;
                    default:
                      ?><div class='dropdown'>
                  <button class='btn btn-link dropdown-toggle' type='button' id='dropdownMenuButton' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>
                    Pending
                  </button>
                  <div class='dropdown-menu' aria-labelledby='dropdownMenuButton'>
                    <form method="post">
                      <button class="dropdown-item" type="submit" name="conbook" value="<?php echo $detail['id']; ?>">Confirm Booking</button>
                      <button class="dropdown-item" type="submit" name="canbook" value="<?php echo $detail['id']; ?>">Cancel Booking</button>
                    </form>
                  </div>
                </div><?php
                      break;
                  }
                      ?>
          </td>
          <td>
            <a href="customer_booking_details.php?i=<?php echo $detail['id']; ?>" class="edit" data-userid="<?php echo $detail['id']; ?>"><i class="fas fa-file-alt text-success"></i></a>
            <a href="home_admin_bookings.php#mymodaldeleteBooking" class="delete" data-bs-toggle="modal" data-userid="<?php echo $detail['id']; ?>"><i class="fas fa-trash text-danger"></i></a>
          </td>
        </tr>
    <?php }
    endforeach ?>
  </tbody>
</table>