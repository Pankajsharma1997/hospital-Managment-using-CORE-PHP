<?php
session_start();
error_reporting(0);
include('include/config.php');

if (strlen($_SESSION['id'] == 0)) {
    header('location:logout.php');
} else {

    if (isset($_POST['submit'])) {
        // Collecting the staff details from the form
        $staffspecilization = $_POST['staffspecilization'];
        $staffname = $_POST['staffname'];
        $staffaddress = $_POST['staffaddress'];
        $staffcontactno = $_POST['staffcontact'];
        $staffemail = $_POST['staffemail'];
        $password = md5($_POST['npass']);  // Encrypt password

        // Insert query for adding staff details
        $sql = mysqli_query($con, "INSERT INTO staff (specilization, staffName, address, contactno, staffEmail, password) 
                                  VALUES ('$staffspecilization', '$staffname', '$staffaddress', '$staffcontactno', '$staffemail', '$password')");

        if ($sql) {
            echo "<script>alert('Staff info added Successfully');</script>";
            echo "<script>window.location.href ='dashboard.php'</script>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Admin | Add Staff</title>
    <!-- Include stylesheets -->
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

    <script type="text/javascript">
    function valid() {
        // Password and confirm password validation
        if (document.addstaff.npass.value != document.addstaff.cfpass.value) {
            alert("Password and Confirm Password Field do not match !!");
            document.addstaff.cfpass.focus();
            return false;
        }
        return true;
    }
    </script>
</head>
<body>
    <div id="app">
        <?php include('include/sidebar.php');?>

        <div class="app-content">
            <?php include('include/header.php');?>

            <div class="main-content">
                <div class="wrap-content container" id="container">
                    <!-- Page Title -->
                    <section id="page-title">
                        <div class="row">
                            <div class="col-sm-8">
                                <h1 class="mainTitle">Admin | Add Staff</h1>
                            </div>
                            <ol class="breadcrumb">
                                <li><span>Admin</span></li>
                                <li class="active"><span>Add Staff</span></li>
                            </ol>
                        </div>
                    </section>

                    <!-- Staff Form -->
                    <div class="container-fluid container-fullw bg-white">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="row margin-top-30">
                                    <div class="col-lg-8 col-md-12">
                                        <div class="panel panel-white">
                                            <div class="panel-heading">
                                                <h5 class="panel-title">Add Staff</h5>
                                            </div>
                                            <div class="panel-body">
                                                <form role="form" name="addstaff" method="post" onSubmit="return valid();">
                                                    
                                                    <!-- Staff Specialization -->
                                                    <div class="form-group">
                                                        <label for="staffspecilization">Staff Specialization</label>
                                                        <select name="staffspecilization" class="form-control" required="true">
                                                            <option value="">Select Specialization</option>
                                                            <?php 
                                                            $ret = mysqli_query($con, "SELECT * FROM staffspecilization");
                                                            while ($row = mysqli_fetch_array($ret)) {
                                                            ?>
                                                            <option value="<?php echo htmlentities($row['specilization']);?>">
                                                                <?php echo htmlentities($row['specilization']);?>
                                                            </option>
                                                            <?php } ?>
                                                        </select>
                                                    </div>

                                                    <!-- Staff Name -->
                                                    <div class="form-group">
                                                        <label for="staffname">Staff Name</label>
                                                        <input type="text" name="staffname" class="form-control" placeholder="Enter Staff Name" required="true">
                                                    </div>

                                                    <!-- Staff Address -->
                                                    <div class="form-group">
                                                        <label for="staffaddress">Staff Address</label>
                                                        <textarea name="staffaddress" class="form-control" placeholder="Enter Staff Address" required="true"></textarea>
                                                    </div>

                                                    <!-- Staff Contact -->
                                                    <div class="form-group">
                                                        <label for="staffcontact">Staff Contact No</label>
                                                        <input type="text" name="staffcontact" class="form-control" placeholder="Enter Staff Contact No" minlength="10" maxlength="10" required="true">
                                                    </div>

                                                    <!-- Staff Email -->
                                                    <div class="form-group">
                                                        <label for="staffemail">Staff Email</label>
                                                        <input type="email" name="staffemail" class="form-control" placeholder="Enter Staff Email" required="true">
                                                    </div>

                                                    <!-- Staff Password -->
                                                    <div class="form-group">
                                                        <label for="npass">Password</label>
                                                        <input type="password" name="npass" class="form-control" placeholder="New Password" required="true">
                                                    </div>

                                                    <!-- Confirm Password -->
                                                    <div class="form-group">
                                                        <label for="cfpass">Confirm Password</label>
                                                        <input type="password" name="cfpass" class="form-control" placeholder="Confirm Password" required="true">
                                                    </div>

                                                    <!-- Submit Button -->
                                                    <button type="submit" name="submit" id="submit" class="btn btn-o btn-primary">Submit</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Footer -->
        <?php include('include/footer.php');?>

        <!-- Settings -->
        <?php include('include/setting.php');?>

    </div>

    <!-- Main Scripts -->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.min.js"></script>
    <script src="vendor/modernizr/modernizr.js"></script>
    <script src="vendor/jquery-cookie/jquery.cookie.js"></script>
    <script src="vendor/perfect-scrollbar/perfect-scrollbar.min.js"></script>
    <script src="vendor/switchery/switchery.min.js"></script>

    <!-- Page-specific Scripts -->
    <script src="assets/js/main.js"></script>
    <script src="assets/js/form-elements.js"></script>

    <script>
    jQuery(document).ready(function() {
        Main.init();
        FormElements.init();
    });
    </script>
</body>
</html>
