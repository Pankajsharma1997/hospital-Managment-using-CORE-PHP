<?php 
session_start();
error_reporting(0);
include('include/config.php');
if(strlen($_SESSION['id']==0)) {
 header('location:logout.php');
  } else{
    $targetDir = "uploads/";
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0755, true);
        }
  
        $uploadedFiles = [];
        $errors = [];

        // Retrieve additional form data
        $vid = $_GET['viewid'] ?? '';
        $bp = $_POST['bp'] ?? '';
        $bs = $_POST['bs'] ?? '';
        $weight = $_POST['weight'] ?? '';
        $temp = $_POST['temp'] ?? '';
        $pres = $_POST['pres'] ?? '';
  
        // Process each file
        foreach ($_FILES['files']['tmp_name'] as $key => $tmpName) {
            $fileName = basename($_FILES['files']['name'][$key]);
            $targetFilePath = $targetDir . $fileName;
  
            // Check for upload errors
            if ($_FILES['files']['error'][$key] === UPLOAD_ERR_OK) {
                if (move_uploaded_file($tmpName, $targetFilePath)) {
                    $uploadedFiles[] = $targetFilePath; // Store full path for DB
                } else {
                    $errors[] = "Error uploading file $fileName.";
                }
            } else {
                $errors[] = "Error with file $fileName.";
            }
        }
  
        // Save all uploaded file paths and additional information to the database
        if (empty($errors) && !empty($uploadedFiles)) {
            $filePathsString = implode(',', $uploadedFiles);
            $stmt = $con->prepare("INSERT INTO tblmedicalhistory (PatientID, BloodPressure, BloodSugar, Weight, Temperature, MedicalPres, UploadedFiles) VALUES (?, ?, ?,?,?,?,?)");
            $stmt->bind_param("sssssss", $vid, $bp, $bs, $weight, $temp, $pres, $filePathsString);
          
            if ($stmt->execute()) {
                echo "Files uploaded successfully: " . implode(', ', $uploadedFiles);
            } else {
                echo "Error saving files to database.";
            }
            $stmt->close();
        } else {
            echo "Errors occurred: " . implode(', ', $errors);
        }
  
        $con->close();
        exit; // Stop further processing after handling the upload
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Receptionist | Manage Patients</title>
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        .file-upload {
            margin: 10px 0;
        }
        .add-file {
            cursor: pointer;
            color: blue;
        }
    </style>
</head>
<body>
<div id="app">		
    <?php include('include/sidebar.php');?>
    <div class="app-content">
        <?php include('include/header.php');?>
        <div class="main-content">
            <div class="wrap-content container" id="container">
                <!-- start: PAGE TITLE -->
                <section id="page-title">
                    <div class="row">
                        <div class="col-sm-8">
                            <h1 class="mainTitle">Doctor | Manage Patients</h1>
                        </div>
                        <ol class="breadcrumb">
                            <li><span>Doctor</span></li>
                            <li class="active"><span>Manage Patients</span></li>
                        </ol>
                    </div>
                </section>

                <div class="container-fluid container-fullw bg-white">
                    <div class="row">
                        <div class="col-md-12">
                            <h5 class="over-title margin-bottom-15">Manage <span class="text-bold">Patients</span></h5>
                            <?php
                                $vid=$_GET['viewid'];
                                $ret=mysqli_query($con,"select * from tblpatient where ID='$vid'");
                                $cnt=1;
                                while ($row=mysqli_fetch_array($ret)) {
                            ?>
                            <table border="1" class="table table-bordered">
                                <tr align="center">
                                    <td colspan="4" style="font-size:20px;color:blue">Patient Details</td>
                                </tr>
                                <tr>
                                    <th scope>Patient Name</th>
                                    <td><?php echo $row['PatientName']; ?></td>
                                    <th scope>Patient Email</th>
                                    <td><?php echo $row['PatientEmail']; ?></td>
                                </tr>
                                <tr>
                                    <th scope>Patient Mobile Number</th>
                                    <td><?php echo $row['PatientContno']; ?></td>
                                    <th>Patient Address</th>
                                    <td><?php echo $row['PatientAdd']; ?></td>
                                </tr>
                                <tr>
                                    <th>Patient Gender</th>
                                    <td><?php echo $row['PatientGender']; ?></td>
                                    <th>Patient Age</th>
                                    <td><?php echo $row['PatientAge']; ?></td>
                                </tr>
                                <tr>
                                    <th>Patient Medical History(if any)</th>
                                    <td><?php echo $row['PatientMedhis']; ?></td>
                                    <th>Patient Reg Date</th>
                                    <td><?php echo $row['CreationDate']; ?></td>
                                </tr>
                            </table>
                            <?php } ?>

                            
                        </div>
                        <div class="col-md-12">
                        <?php  
$ret = mysqli_query($con, "select * from tblmedicalhistory where PatientID='$vid'");

?>
<table id="datatable" class="table table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
  <tr align="center">
    <th colspan="8">Medical History</th> 
  </tr>
  <tr>
    <th>#</th>
    <th>Blood Pressure</th>
    <th>Weight</th>
    <th>Blood Sugar</th>
    <th>Body Temperature</th>
    <th>Medical Prescription</th>
    <th>Visit Date</th>
    <th>Uploaded Files</th>
  </tr>
  <?php  
  while ($row = mysqli_fetch_array($ret)) {
    $uploadedFiles = $row['UploadedFiles'];  // String of file paths or URLs
    $fileArray = explode(',', $uploadedFiles);  // Split string into array using comma as delimiter
  ?>
  <tr>
    <td><?php echo $cnt; ?></td>
    <td><?php echo $row['BloodPressure']; ?></td>
    <td><?php echo $row['Weight']; ?></td>
    <td><?php echo $row['BloodSugar']; ?></td> 
    <td><?php echo $row['Temperature']; ?></td>
    <td><?php echo $row['MedicalPres']; ?></td>
    <td><?php echo $row['nextAppointment']; ?> </td>
    <td><?php echo $row['CreationDate']; ?></td>
    <td>
      <?php
      // Loop through the file array and generate a link for each file
      foreach ($fileArray as $file) {
        // Assuming each file is a URL or path that is valid
        echo '<a href="' . trim($file) . '" target="_blank">' . basename(trim($file)) . '</a><br>';
      }
      ?>
    </td> 
  </tr>
  <?php $cnt = $cnt + 1; } ?>
</table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            $('.add-file').click(function() {
                $('#fileUploadsContainer').append(`
                    <div class="file-upload">
                        <input type="file" name="files[]" accept="*/*" required>
                    </div>
                `);
            });

            $('#uploadForm').on('submit', function(e) {
                e.preventDefault();

                $.ajax({
                    url: '',
                    type: 'POST',
                    data: new FormData(this),
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        alert(response);
                    },
                    error: function() {
                        alert('Error uploading files.');
                    }
                });
            });
        });
    </script>

    <?php include('include/footer.php');?>
    <?php include('include/setting.php');?>
</div>


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
</body>
</html>