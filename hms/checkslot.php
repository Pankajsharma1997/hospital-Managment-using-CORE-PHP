<?php
include('include/config.php');
if (isset($_POST['doctor']) && isset($_POST['date'])) {
    $doctor_id = $_POST['doctor'];
    $selected_date = $_POST['date'];
    
    // Fetch existing appointments for the selected date
    $query = mysqli_query($con, "SELECT appointmentTime FROM appointment WHERE doctorId='$doctor_id' AND appointmentDate='$selected_date'");
    $booked_times = [];
    
    while ($row = mysqli_fetch_array($query)) {
        $booked_times[] = $row['appointmentTime'];
    }
    
    // Fetch available time slots for the selected doctor (example: from 10 AM to 5 PM)
    // Modify this based on your actual doctors' time slot availability.
    $available_slots = [
        '10:00 AM','10:30 AM', '11:00 AM', '11:30 AM','12:00 PM', '12:30 PM', '01:00 PM','01:30 PM','02:00 PM', '03:00 PM', '04:00 PM', '05:00 PM'
    ];

    // Filter out the booked times from available slots
    $available_slots = array_diff($available_slots, $booked_times);
    
    // Return the available slots as a JSON response
    echo json_encode(array_values($available_slots));
}
?>