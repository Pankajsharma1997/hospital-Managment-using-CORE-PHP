<?php
session_start();
error_reporting(0);
include('include/config.php');
if(strlen($_SESSION['id']==0)) {
 header('location:logout.php');
  } else{

if(isset($_GET['cancel']))
		  {
mysqli_query($con,"update appointment set doctorStatus='0' where id ='".$_GET['id']."'");
                  $_SESSION['msg']="Appointment canceled !!";
		  }
// Assuming you have the necessary database connection and session started
if(isset($_GET['complete']) && $_GET['complete'] == 'update') {
    $appointmentId = $_GET['id'];
    
    // Update the appointment status to 'Completed' (doctorStatus = 2)
    $update_sql = mysqli_query($con, "UPDATE appointment SET doctorStatus = 2 WHERE id = '$appointmentId'");
    
    if($update_sql) {
        $_SESSION['msg'] = "Appointment marked as completed successfully!";
    } else {
        $_SESSION['msg'] = "Error in updating appointment status.";
    }
    
    header('location: appointment-history.php');
}
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<title>Doctor | Appointment History</title>
		
		<link href="http://fonts.googleapis.com/css?family=Lato:300,400,400italic,600,700|Raleway:300,400,500,600,700|Crete+Round:400italic" rel="stylesheet" type="text/css" />
		<link rel="stylesheet" href="vendor/bootstrap/css/bootstrap.min.css">
		<link rel="stylesheet" href="vendor/fontawesome/css/font-awesome.min.css">
		<link rel="stylesheet" href="vendor/themify-icons/themify-icons.min.css">
		<link href="vendor/animate.css/animate.min.css" rel="stylesheet" media="screen">
		<link href="vendor/perfect-scrollbar/perfect-scrollbar.min.css" rel="stylesheet" media="screen">
		<link href="vendor/switchery/switchery.min.css" rel="stylesheet" media="screen">
		<link href="vendor/bootstrap-touchspin/jquery.bootstrap-touchspin.min.css" rel="stylesheet" media="screen">
		<link href="vendor/select2/select2.min.css" rel="stylesheet" media="screen">
		<link href="vendor/bootstrap-datepicker/bootstrap-datepicker3.standalone.min.css" rel="stylesheet" media="screen">
		<link href="vendor/bootstrap-timepicker/bootstrap-timepicker.min.css" rel="stylesheet" media="screen">
		<link rel="stylesheet" href="assets/css/styles.css">
		<link rel="stylesheet" href="assets/css/plugins.css">
		<link rel="stylesheet" href="assets/css/themes/theme-1.css" id="skin_color" />
	</head>
	<body>
		<div id="app">		
<?php include('include/sidebar.php');?>
			<div class="app-content">
				

					<?php include('include/header.php');?>
				<!-- end: TOP NAVBAR -->
				<div class="main-content" >
					<div class="wrap-content container" id="container">
						<!-- start: PAGE TITLE -->
						<section id="page-title">
							<div class="row">
								<div class="col-sm-8">
									<h1 class="mainTitle">Doctor  | Appointment History</h1>
																	</div>
								<ol class="breadcrumb">
									<li>
										<span>Doctor </span>
									</li>
									<li class="active">
										<span>Appointment History</span>
									</li>
								</ol>
							</div>
						</section>
						<!-- end: PAGE TITLE -->
<!-- start: DAILY APPOINTMENTS -->
<div class="container-fluid container-fullw bg-white">
    <div class="row">
        <div class="col-md-12">
            <h4>Daily Appointments</h4>
            <p style="color:red;"><?php echo htmlentities($_SESSION['msg']);?>
            <?php echo htmlentities($_SESSION['msg']="");?></p>
            
            <table class="table table-hover" id="daily-appointments-table">
                <thead>
                    <tr>
                        <th class="center">#</th>
                        <th class="hidden-xs">Patient Name</th>
                        <th>Specialization</th>
                        <th>Consultancy Fee</th>
                        <th>Appointment Date / Time</th>
                        <th>Appointment Creation Date</th>
                        <th>Current Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Get today's date
                    $today = date('Y-m-d');
                    
                    // Query for today's appointments
                    $sql_daily = mysqli_query($con, "SELECT users.fullName AS fname, appointment.* 
                                                      FROM appointment 
                                                      JOIN users ON users.id = appointment.userId 
                                                      WHERE appointment.doctorId = '".$_SESSION['id']."' 
                                                      AND appointment.appointmentDate = '$today'");
                    $cnt = 1;
                    while($row = mysqli_fetch_array($sql_daily)) {
                    ?>
                    <tr>
                        <td class="center"><?php echo $cnt;?>.</td>
                        <td class="hidden-xs"><?php echo $row['fname'];?></td>
                        <td><?php echo $row['doctorSpecialization'];?></td>
                        <td><?php echo $row['consultancyFees'];?></td>
                        <td><?php echo $row['appointmentDate'];?> / <?php echo $row['appointmentTime'];?></td>
                        <td><?php echo $row['postingDate'];?></td>
                        <td>
                            <?php if(($row['userStatus'] == 1) && ($row['doctorStatus'] == 1)) {
                                echo "Active";
                            }
                            if(($row['userStatus'] == 0) && ($row['doctorStatus'] == 1)) {
                                echo "Cancel by Patient";
                            }
                            if(($row['userStatus'] == 1) && ($row['doctorStatus'] == 0)) {
                                echo "Cancel by you";
                            }
                            if($row['doctorStatus'] == 2) {
                                echo "Completed";
                            }
                            ?>
                        </td>
                        <td>
                        <div class="visible-md visible-lg hidden-sm hidden-xs">
                                <?php 
                                // Check if appointment is active and not yet completed
                                if(($row['userStatus'] == 1) && ($row['doctorStatus'] == 1)) { ?>
                                    <a href="appointment-history.php?id=<?php echo $row['id']?>&cancel=update" 
                                       onClick="return confirm('Are you sure you want to cancel this appointment?')" 
                                       class="btn btn-info btn-xs" 
                                       title="Cancel Appointment" 
                                       tooltip-placement="top" 
                                       tooltip="Remove">Cancel</a>
                                    <a href="appointment-history.php?id=<?php echo $row['id']?>&complete=update" 
                                       onClick="return confirm('Are you sure you want to mark this appointment as complete?')" 
                                       class="btn btn-success btn-xs" 
                                       title="Complete Appointment" 
                                       tooltip-placement="top" 
                                       tooltip="Complete"> Mark Completed</a>
                                <?php } elseif($row['doctorStatus'] == 2) {
                                    echo "Completed";
                                } else {
                                    echo "Canceled";
                                } ?>
                            </div>
                        </td>
                    </tr>
                    <?php 
                    $cnt++;
                    } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<!-- end: DAILY APPOINTMENTS -->



<!-- start: ALL APPOINTMENTS -->
<div class="container-fluid container-fullw bg-white">
    <div class="row">
        <div class="col-md-12">
            <h4>All Appointments</h4>
            <p style="color:red;"><?php echo htmlentities($_SESSION['msg']);?>
            <?php echo htmlentities($_SESSION['msg']="");?></p>
            
            <table class="table table-hover" id="all-appointments-table">
                <thead>
                    <tr>
                        <th class="center">#</th>
                        <th class="hidden-xs">Patient Name</th>
                        <th>Specialization</th>
                        <th>Consultancy Fee</th>
                        <th>Appointment Date / Time</th>
                        <th>Appointment Creation Date</th>
                        <th>Current Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Query for all appointments
                    $sql_all = mysqli_query($con, "SELECT users.fullName AS fname, appointment.* 
                                                   FROM appointment 
                                                   JOIN users ON users.id = appointment.userId 
                                                   WHERE appointment.doctorId = '".$_SESSION['id']."'");
                    $cnt = 1;
                    while($row = mysqli_fetch_array($sql_all)) {
                    ?>
                    <tr>
                        <td class="center"><?php echo $cnt;?>.</td>
                        <td class="hidden-xs"><?php echo $row['fname'];?></td>
                        <td><?php echo $row['doctorSpecialization'];?></td>
                        <td><?php echo $row['consultancyFees'];?></td>
                        <td><?php echo $row['appointmentDate'];?> / <?php echo $row['appointmentTime'];?></td>
                        <td><?php echo $row['postingDate'];?></td>
                        <td>
                            <?php if(($row['userStatus'] == 1) && ($row['doctorStatus'] == 1)) {
                                echo "Active";
                            }
                            if(($row['userStatus'] == 0) && ($row['doctorStatus'] == 1)) {
                                echo "Cancel by Patient";
                            }
                            if(($row['userStatus'] == 1) && ($row['doctorStatus'] == 0)) {
                                echo "Cancel by you";
                            }
                            ?>
                        </td>
                        <td>
                            <div class="visible-md visible-lg hidden-sm hidden-xs">
                                <?php if(($row['userStatus'] == 1) && ($row['doctorStatus'] == 1)) { ?>
                                    <a href="appointment-history.php?id=<?php echo $row['id']?>&cancel=update" 
                                       onClick="return confirm('Are you sure you want to cancel this appointment?')" 
                                       class="btn btn-info btn-xs" 
                                       title="Cancel Appointment" 
                                       tooltip-placement="top" 
                                       tooltip="Remove">Cancel</a>
                                <?php } else {
                                    echo "Canceled";
                                } ?>
                            </div>
                        </td>
                    </tr>
                    <?php 
                    $cnt++;
                    } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<!-- end: ALL APPOINTMENTS -->
						<!-- end: SELECT BOXES -->
						
					</div>
				</div>
			</div>
			<!-- start: FOOTER -->
	<?php include('include/footer.php');?>
			<!-- end: FOOTER -->
		
			<!-- start: SETTINGS -->
	<?php include('include/setting.php');?>
			
			<!-- end: SETTINGS -->
		</div>
		<!-- start: MAIN JAVASCRIPTS -->
		<script src="vendor/jquery/jquery.min.js"></script>
		<script src="vendor/bootstrap/js/bootstrap.min.js"></script>
		<script src="vendor/modernizr/modernizr.js"></script>
		<script src="vendor/jquery-cookie/jquery.cookie.js"></script>
		<script src="vendor/perfect-scrollbar/perfect-scrollbar.min.js"></script>
		<script src="vendor/switchery/switchery.min.js"></script>
		<!-- end: MAIN JAVASCRIPTS -->
		<!-- start: JAVASCRIPTS REQUIRED FOR THIS PAGE ONLY -->
		<script src="vendor/maskedinput/jquery.maskedinput.min.js"></script>
		<script src="vendor/bootstrap-touchspin/jquery.bootstrap-touchspin.min.js"></script>
		<script src="vendor/autosize/autosize.min.js"></script>
		<script src="vendor/selectFx/classie.js"></script>
		<script src="vendor/selectFx/selectFx.js"></script>
		<script src="vendor/select2/select2.min.js"></script>
		<script src="vendor/bootstrap-datepicker/bootstrap-datepicker.min.js"></script>
		<script src="vendor/bootstrap-timepicker/bootstrap-timepicker.min.js"></script>
		<!-- end: JAVASCRIPTS REQUIRED FOR THIS PAGE ONLY -->
		<!-- start: CLIP-TWO JAVASCRIPTS -->
		<script src="assets/js/main.js"></script>
		<!-- start: JavaScript Event Handlers for this page -->
		<script src="assets/js/form-elements.js"></script>
		<script>
			jQuery(document).ready(function() {
				Main.init();
				FormElements.init();
			});
		</script>
		<!-- end: JavaScript Event Handlers for this page -->
		<!-- end: CLIP-TWO JAVASCRIPTS -->
	</body>
</html>
<?php } ?>
