<?php
include('include/config.php');
if(!empty($_POST["specilizationid"])) 
{

 $sql=mysqli_query($con,"select doctorName,id from doctors where specilization='".$_POST['specilizationid']."'");?>
 <option selected="selected">Select Doctor </option>
 <?php
 while($row=mysqli_fetch_array($sql))
 	{?>
  <option value="<?php echo htmlentities($row['id']); ?>"><?php echo htmlentities($row['doctorName']); ?></option>
  <?php
}
}


//for display the doctor fees 
if(!empty($_POST["doctor"])) 
{

 $sql=mysqli_query($con,"select docFees from doctors where id='".$_POST['doctor']."'");
 while($row=mysqli_fetch_array($sql))
 	{?>
 <option value="<?php echo htmlentities($row['docFees']); ?>"><?php echo htmlentities($row['docFees']); ?></option>
  <?php
}

}


// Fetch doctor_id and appdate from AJAX request
$doctor_id = isset($_POST['doctor_id']) ? $_POST['doctor_id'] : '';
$appdate = isset($_POST['appdate']) ? $_POST['appdate'] : '';

if ($doctor_id && $appdate) {
    // Query to fetch the doctor timeslots based on the doctor_id from the doctors table
    $result = mysqli_query($con, "SELECT timeslots FROM doctors WHERE id = '$doctor_id'");

    // Check if the result is not empty and fetch the timeslot string
    if ($row = mysqli_fetch_array($result)) {
        // Explode the timeslot string into an array
        $availableSlots = explode(',', $row['timeslots']);
    } else {
        $availableSlots = []; // No timeslots found for this doctor
    }

    // Query to fetch booked appointment times for the selected doctor and date
    $sql = mysqli_query($con, "SELECT appointmentTime FROM appointment WHERE doctorId = '$doctor_id' AND appointmentDate = '$appdate'");

    // Store booked times in an array
    $bookedTimes = [];
    while ($row = mysqli_fetch_array($sql)) {
        $bookedTimes[] = $row['appointmentTime'];
    }

    // Loop through available slots and display those that are not booked
    foreach ($availableSlots as $slot) {
        // If the slot is not booked, display it in the dropdown
        if (!in_array($slot, $bookedTimes)) {
            echo '<option value="' . htmlentities($slot) . '">' . htmlentities($slot) . '</option>';
        }
    }
 } 
 //else {
//     echo '<option value="">No time slots available</option>';
// }


// Fetch doctor_id and appdate from AJAX request
// $doctor_id = isset($_POST['doctor_id']) ? $_POST['doctor_id'] : '';
// $appdate = isset($_POST['appdate']) ? $_POST['appdate'] : '';

// if ($doctor_id && $appdate) {
//     // Define available time slots
//      $availableSlots = array("10:00 AM", "10:30 AM", "11:00 AM", "12:00 PM", "1:00 PM", "1:30 PM", "2:00 PM", "2:30 PM", "3:00 PM");

//    // Query to fetch booked appointment times for the selected doctor and date
//     $sql = mysqli_query($con, "SELECT appointmentTime FROM appointment WHERE 	doctorId = '$doctor_id' AND appointmentDate = '$appdate'");

//     // Store booked times in an array
//     $bookedTimes = [];
//     while ($row = mysqli_fetch_array($sql)) {
//         $bookedTimes[] = $row['appointmentTime'];
//     }

//     // Loop through available slots and display those that are not booked
//     foreach ($availableSlots as $slot) {
//         // If the slot is not booked, display it in the dropdown
//         if (!in_array($slot, $bookedTimes)) {
//             echo '<option value="' . htmlentities($slot) . '">' . htmlentities($slot) . '</option>';
//         }
//     }
// } else {
//     echo '<option value="">No time slots available</option>';
// }


 
?>


