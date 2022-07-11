<?php
if (!isset($_GET['nw'])) {
    echo "<script>window.location='https://abyride.com'</script>";
}
require("view.php");
$main_view = new MainView();
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>AbyRide-TAXI</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/simple-line-icons/2.4.1/css/simple-line-icons.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
<!-- <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet"> -->
<script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <style type="text/css">
        label{
            font-weight: bolder;
        }
    </style>
</head>
<body>
    <div class="registration-form" style="background-color: #37517E;">
        <form>
            <div class="form-icon">
                <span><img src="assets/imgs/logo.jpeg" style="width: 120px;height: 120px"></span>
            </div>
            <div class="form-group">
                <label for="driving_licence">Upload Driving Licence</label><label style="font-weight: lighter;font-size: 14px;" class="text-warning">&nbsp;&nbsp;  Only <b>JPG</b>, <b>JPEG</b>, <b>PNG</b> & <b>GIF</b> files are allowed</label>
                <input type="file" name="driving_licence" id="driving_licence" class="form-control" required>
                <span id="uploaded_driving"></span>
            </div>
            <div class="form-group">
                <label for="vehicle_licence">Upload Vehicle Licence</label><label style="font-weight: lighter;font-size: 14px;" class="text-warning">&nbsp;&nbsp; Only <b>JPG</b>, <b>JPEG</b>, <b>PNG</b> & <b>GIF</b> files are allowed</label>
                <input type="file" name="vehicle_licence" id="vehicle_licence" class="form-control" required>
                <span id="uploaded_vehicle"></span>
            </div>
            <div class="form-group">
                <label for="insurance_paper">Upload Insurance Papers</label><label style="font-weight: lighter;font-size: 14px;" class="text-warning">&nbsp;&nbsp;  Only <b>JPG</b>, <b>JPEG</b>, <b>PNG</b> & <b>GIF</b> files are allowed</label>
                <input type="file" name="insurance_paper" id="insurance_paper" class="form-control" required>
                <span id="uploaded_insurance"></span>
            </div>
            <div class="form-group">
                <label for="cartype">Car Model Type</label>
                <select class="form-control" id="cartype">
                    <?=$main_view->listOfCarModels();?>
                </select>
                <!-- <input type="text" placeholder="Phone Number"> -->
            </div>
            <div class="form-group">
                <label for="carseats">Car Seats Number</label>
                <input type="number" class="form-control item" id="carseats" placeholder="Car Seats Number">
            </div>
            <div class="form-group">
                <span id="uploaded_save"></span>
                <button type="button" name="submit" id="save" class="btn btn-block create-account">Save</button>
            </div>
        </form>
        <div class="social-media">
            <h5>Follow us on social media</h5>
            <div class="social-icons">
                <a href="#"><i class="icon-social-facebook" title="Facebook"></i></a>
                <a href="#"><i class="icon-social-google" title="Google"></i></a>
                <a href="#"><i class="icon-social-twitter" title="Twitter"></i></a>
            </div>
        </div>
        <input type="hidden" value="<?=$_GET['nw']?>" id='hdid' name="hdid">
    </div>
    <script type="text/javascript" src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.15/jquery.mask.min.js"></script>
<script src="web.js"></script>
</body>
</html>
