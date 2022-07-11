<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once("mailer/driver_upload_email.php");
//====================================================================================================== CONNECTION
class DbConnect
{
    private $host='localhost';
    private $dbName = 'pyyquokz_abyride_taxi';
    private $user = 'pyyquokz';
    private $pass = 'Ride!#AbyCredentials#!';
    public $conn;
    
    // private $host='localhost';
    // private $dbName = 'abyride_taxi';
    // private $user = 'root';
    // private $pass = '';
    // public $conn;

    public function connect()
    {
        try {
         $conn = new PDO('mysql:host='.$this->host.';dbname='.$this->dbName, $this->user, $this->pass);
         return $conn;
        } catch (PDOException $e) {
            echo "Database Error ".$e->getMessage();
            return null;
        }
    }
}


/**
 * ===================================== MAIN FEATURES
 */
class MainFeatures extends DbConnect
{
    
    function user_name($user_id)            //=============== USERNAME
    {
        $con = parent::connect();
        $sel = $con->prepare("SELECT * FROM users WHERE users.UserId='$user_id'");
        $sel->execute();
        if ($sel->rowCount()>=1) {
            $ft_sel = $sel->fetch(PDO::FETCH_ASSOC);
            $res = $ft_sel['FullName'];
        }else{
            $res = '';
        }
        return $res;
}

    function user_phone($user_id)            //=============== PHONE
    {
        $con = parent::connect();
        $sel = $con->prepare("SELECT * FROM users WHERE users.UserId='$user_id'");
        $sel->execute();
        if ($sel->rowCount()>=1) {
            $ft_sel = $sel->fetch(PDO::FETCH_ASSOC);
            $res = $ft_sel['Phone'];
        }else{
            $res = '';
        }
        return $res;
}

    function user_email($user_id)            //=============== EMAIL
    {
        $con = parent::connect();
        $sel = $con->prepare("SELECT * FROM users WHERE users.UserId='$user_id'");
        $sel->execute();
        if ($sel->rowCount()>=1) {
            $ft_sel = $sel->fetch(PDO::FETCH_ASSOC);
            $res = $ft_sel['Email'];
        }else{
            $res = '';
        }
        return $res;
}

}

// 
 // =======================LOGIN
// 
class Login extends DbConnect
{
    
    function __construct($email,$pass)
    {
        $con = parent::connect();
        $log = $con->prepare("SELECT * FROM users WHERE users.Status=1 AND users.Email=? AND users.Password=? AND users.Status=1");
        $log->bindValue(1,$email);
        $log->bindValue(2,md5($pass));
        $log->execute();
        if ($log->rowCount()>0) {
            $ft_log = $log->fetch(PDO::FETCH_ASSOC);
            //SELECTING WORK Addresses
            $sel = $con->prepare("SELECT * FROM addresses,users WHERE addresses.UserId=users.UserId AND addresses.AddressType=1 AND addresses.UserId=? AND addresses.AddressStatus=1");
            $sel->bindValue(1,$ft_log['UserId']);
            $sel->execute();
            if($sel->rowCount()!=0){
              $ft_sel = $sel->fetch(PDO::FETCH_ASSOC);
              $work_add = $ft_sel['AddressValue'];
            }else{
              $work_add = "null";
            }
            $data = $ft_log; 
            $data['login_status'] = "true";
            $data['user_work_address'] = $work_add;
            $api_login[] = $data;

        }else{
            $data = (array) "null" ;
            $work_add = "null";
            $data['user_work_address'] = $work_add;
            $data['login_status'] = "wrong";
            $api_login[] = $data;
            
        }
        print(json_encode($api_login));
    }
}

/**
 * ======================= RiderSignUp
 */
class RiderSignUp extends DbConnect
{
    
    function __construct($fullname,$email,$phone,$address,$new_pass,$conf_pass)
    {
        $con = parent::connect();
        $sel = $con->prepare("SELECT * FROM users WHERE users.Email=? AND users.Type=0");
        $sel->bindValue(1,$email);
        $sel->execute();
        if ($sel->rowCount()>=1) {
            $data = (array) null;
            $data['rider_signup_status'] = "already";
            $api_rider_signup[] = $data;
        }else{
            if ($new_pass==$conf_pass) {
                    $insert = $con->prepare("INSERT INTO users(FullName,Email,Address,Password,Phone,Type,Status) VALUES(?,?,?,?,?,?,?)");
                    $insert->bindValue(1,$fullname);
                    $insert->bindValue(2,$email);
                    $insert->bindValue(3,$address);
                    $insert->bindValue(4,md5($new_pass));
                    $insert->bindValue(5,$phone);
                    $insert->bindValue(6,0);
                    $insert->bindValue(7,1);
                    $ok_insert = $insert->execute();
                    if ($ok_insert) {
                        $sel_after = $con->prepare("SELECT * FROM users WHERE users.Email=? AND users.Status!=? AND users.Type=0 AND users.Status IS NOT NULL");
                        $sel_after->bindValue(1,$email);
                        $sel_after->bindValue(2,0);
                        $sel_after->execute();
                        if ($sel_after->rowCount()!=0) {
                            $ft_sel_after = $sel_after->fetch(PDO::FETCH_ASSOC);

                            //SELECTING WORK Addresses
                            $sel = $con->prepare("SELECT * FROM addresses,users WHERE addresses.UserId=users.UserId AND addresses.AddressType=1 AND addresses.UserId=? AND addresses.AddressStatus=1");
                            $sel->bindValue(1,$ft_sel_after['UserId']);
                            $sel->execute();
                            if($sel->rowCount()!=0){
                              $ft_sel = $sel->fetch(PDO::FETCH_ASSOC);
                              $work_add = $ft_sel['AddressValue'];
                            }else{
                              $work_add = "null";
                            }
                            $data = $ft_sel_after; 
                            $data['rider_signup_status'] = "true";
                            // $data['user_home_address'] = $home_add;
                            $data['user_work_address'] = $work_add;
                            $api_rider_signup[] = $data;
                        }else{
                            $data  = (array) "null";
                            $data['rider_signup_status'] = "nooo";
                            $api_rider_signup[] = $data;
                        }
                    }else{
                        $data = (array) "null";
                        $data['rider_signup_status'] = "failed";
                        $api_rider_signup[] = $data;
                    }
            }else{
                $data = (array) "null";
                $data['rider_signup_status'] = "not_match";
                $api_rider_signup[] = $data;
            }
        }
            print(json_encode($api_rider_signup));
    }
}

/**
 * ======================= DriverSignUp
 */
class DriverSignUp extends DbConnect
{
    
    function __construct($fullname,$email,$phone,$address,$new_pass,$conf_pass)
    {
        $con = parent::connect();
        $sel = $con->prepare("SELECT * FROM users WHERE users.Email=? AND users.Type=1");
        $sel->bindValue(1,$email);
        $sel->execute();
        if ($sel->rowCount()>=1) {
            $data = (array) null;
            $data['rider_signup_status'] = "already";
            $api_rider_signup[] = $data;
        }else{
            if ($new_pass==$conf_pass) {
                    $insert = $con->prepare("INSERT INTO users(FullName,Email,Address,Password,Phone,Type,Status) VALUES(?,?,?,?,?,?,?)");
                    $insert->bindValue(1,$fullname);
                    $insert->bindValue(2,$email);
                    $insert->bindValue(3,$address);
                    $insert->bindValue(4,md5($new_pass));
                    $insert->bindValue(5,$phone);
                    $insert->bindValue(6,1);
                    $insert->bindValue(7,0);
                    $ok_insert = $insert->execute();
                    if ($ok_insert) {
                        $sel_after = $con->prepare("SELECT * FROM users WHERE users.Email=? AND users.Status!=? AND users.Type=1 AND users.Status IS NOT NULL");
                        $sel_after->bindValue(1,$email);
                        $sel_after->bindValue(2,0);
                        $sel_after->execute();
                        if ($sel_after->rowCount()!=0) {
                            $ft_sel_after = $sel_after->fetch(PDO::FETCH_ASSOC);
                            $data = $ft_sel_after; 

                            //SELECTING WORK Addresses
                            $sel = $con->prepare("SELECT * FROM addresses,users WHERE addresses.UserId=users.UserId AND addresses.AddressType=1 AND addresses.UserId=? AND addresses.AddressStatus=1");
                            $sel->bindValue(1,$ft_sel_after['UserId']);
                            $sel->execute();
                            if($sel->rowCount()!=0){
                              $ft_sel = $sel->fetch(PDO::FETCH_ASSOC);
                              $work_add = $ft_sel['AddressValue'];
                            }else{
                              $work_add = "null";
                            }
                            $data['rider_signup_status'] = "true";
                            // $data['user_home_address'] = $home_add;
                            $data['user_work_address'] = $work_add;
                            $api_rider_signup[] = $data;
                            emailDriverForUploadRegistrationDocs($data['UserId'],$data['Email'],$data['FullName']);
                        }else{
                            $data  = (array) "null";
                            $data['rider_signup_status'] = "nooo";
                            $api_rider_signup[] = $data;
                        }
                    }else{
                        $data = (array) "null";
                        $data['rider_signup_status'] = "failed";
                        $api_rider_signup[] = $data;
                    }
            }else{
                $data = (array) "null";
                $data['rider_signup_status'] = "not_match";
                $api_rider_signup[] = $data;
            }
        }
            print(json_encode($api_rider_signup));
    }
}

/**
 * =========== DRIVER UPLOAD DRIVING LICENCE
 */
class uploadDriverLicence extends DbConnect
{
    
    function __construct($name,$temp,$size,$driver)
    {
        $con = parent::connect();
        if($name != '')
        {
         $test = explode('.', $name);
         $ext = end($test);
         // $nnmm = $name;
         $name = $randoms['random_bytes'] = bin2hex(random_bytes(50)) . '.' . $ext;
         // $location = 'uploads/d_licence/' . $name;  
         // move_uploaded_file($_FILES["driving_licence"]["tmp_name"], $location);
         // echo '<img src="'.$location.'" height="150" width="225" class="img-thumbnail" />';
        $respp = " - ";



        $target_dir = "uploads/d_licence/";
        $target_file = $target_dir . basename($name);
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

        // Check if image file is a actual image or fake image
          $check = getimagesize($temp);
          if($check !== false) {
            $respp = "File is an image - " . $check["mime"] . ".";
            $uploadOk = 1;
          } else {
            $respp = "File is not an image.";
            $uploadOk = 0;
          }

        // Check if file already exists
        if (file_exists($target_file)) {
          $respp = "Sorry, file already exists.";
          $uploadOk = 0;
        }

        // Check file size
        if ($size > 500000) {
          $respp = "Sorry, your file is too large.";
          $uploadOk = 0;
        }

        // Allow certain file formats
        if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
        && $imageFileType != "gif" ) {
          $respp = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
          $uploadOk = 0;
        }

        // Check if $uploadOk is set to 0 by an error
        if ($uploadOk == 0) {
          $respp = "Sorry, your file was not uploaded.";
        // if everything is ok, try to upload file
        } else {
          if (move_uploaded_file($temp, $target_file)) {
            $con = parent::connect();
            $ins = $con->prepare("INSERT INTO drivers(UserId,File,DocumentId,Status) VALUES(?,?,?,1)");
            $ins->bindValue(1,$driver);
            $ins->bindValue(2,$target_file);
            $ins->bindValue(3,1);
            $ok_ins = $ins->execute();
            if ($ok_ins) {
                $respp = "success";
            }else{
                $respp = "Driving License not uploaded ";
                // echo "$driver  -  $target_file  ";
            }
          } else {
            $respp = "Sorry, there was an error uploading your file.";
          }
        }

        }
        echo $respp;
    }
}


/**
 * =========== DRIVER UPLOAD VEHICLE LICENCE
 */
class uploadVehicleLicence extends DbConnect
{
    
    function __construct($name,$temp,$size,$driver)
    {
        $con = parent::connect();
        if($name != '')
        {
         $test = explode('.', $name);
         $ext = end($test);
         // $nnmm = $name;
         $name = $randoms['random_bytes'] = bin2hex(random_bytes(50)) . '.' . $ext;
         // $location = 'uploads/d_licence/' . $name;  
         // move_uploaded_file($_FILES["driving_licence"]["tmp_name"], $location);
         // echo '<img src="'.$location.'" height="150" width="225" class="img-thumbnail" />';
        $respp = " - ";



        $target_dir = "uploads/v_licence/";
        $target_file = $target_dir . basename($name);
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

        // Check if image file is a actual image or fake image
          $check = getimagesize($temp);
          if($check !== false) {
            $respp = "File is an image - " . $check["mime"] . ".";
            $uploadOk = 1;
          } else {
            $respp = "File is not an image.";
            $uploadOk = 0;
          }

        // Check if file already exists
        if (file_exists($target_file)) {
          $respp = "Sorry, file already exists.";
          $uploadOk = 0;
        }

        // Check file size
        if ($size > 500000) {
          $respp = "Sorry, your file is too large.";
          $uploadOk = 0;
        }

        // Allow certain file formats
        if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
        && $imageFileType != "gif" ) {
          $respp = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
          $uploadOk = 0;
        }

        // Check if $uploadOk is set to 0 by an error
        if ($uploadOk == 0) {
          $respp = "Sorry, your file was not uploaded.";
        // if everything is ok, try to upload file
        } else {
          if (move_uploaded_file($temp, $target_file)) {
            $con = parent::connect();
            $ins = $con->prepare("INSERT INTO drivers(UserId,File,DocumentId,Status) VALUES(?,?,?,1)");
            $ins->bindValue(1,$driver);
            $ins->bindValue(2,$target_file);
            $ins->bindValue(3,2);
            $ok_ins = $ins->execute();
            if ($ok_ins) {
                $respp = "success";
            }else{
                $respp = "Vehicle License not uploaded ";
            }
          } else {
            $respp = "Sorry, there was an error uploading your file.";
          }
        }

        }
        echo $respp;
    }
}

/**
 * =========== DRIVER UPLOAD INSURANCE PAPERS
 */
class uploadInsurancePapers extends DbConnect
{
    
    function __construct($name,$temp,$size,$driver)
    {
        $con = parent::connect();
        if($name != '')
        {
         $test = explode('.', $name);
         $ext = end($test);
         // $nnmm = $name;
         $name = $randoms['random_bytes'] = bin2hex(random_bytes(50)) . '.' . $ext;
         // $location = 'uploads/d_licence/' . $name;  
         // move_uploaded_file($_FILES["driving_licence"]["tmp_name"], $location);
         // echo '<img src="'.$location.'" height="150" width="225" class="img-thumbnail" />';
        $respp = " - ";



        $target_dir = "uploads/ins_papers/";
        $target_file = $target_dir . basename($name);
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

        // Check if image file is a actual image or fake image
          $check = getimagesize($temp);
          if($check !== false) {
            $respp = "File is an image - " . $check["mime"] . ".";
            $uploadOk = 1;
          } else {
            $respp = "File is not an image.";
            $uploadOk = 0;
          }

        // Check if file already exists
        if (file_exists($target_file)) {
          $respp = "Sorry, file already exists.";
          $uploadOk = 0;
        }

        // Check file size
        if ($size > 500000) {
          $respp = "Sorry, your file is too large.";
          $uploadOk = 0;
        }

        // Allow certain file formats
        if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
        && $imageFileType != "gif" ) {
          $respp = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
          $uploadOk = 0;
        }

        // Check if $uploadOk is set to 0 by an error
        if ($uploadOk == 0) {
          $respp = "Sorry, your file was not uploaded.";
        // if everything is ok, try to upload file
        } else {
          if (move_uploaded_file($temp, $target_file)) {
            $con = parent::connect();
            $ins = $con->prepare("INSERT INTO drivers(UserId,File,DocumentId,Status) VALUES(?,?,?,1)");
            $ins->bindValue(1,$driver);
            $ins->bindValue(2,$target_file);
            $ins->bindValue(3,3);
            $ok_ins = $ins->execute();
            if ($ok_ins) {
                $respp = "success";
            }else{
                $respp = "Insurance papers not uploaded ";
            }
          } else {
            $respp = "Sorry, there was an error uploading your file.";
          }
        }

        }
        echo $respp;
    }
}

/**
 * ========================================== SAVE DRIVER CAR SEATS AND MODEL
 */
class saveCarSeatsModel extends DbConnect
{
    
    function __construct($type,$seats,$driver)
    {
        $con = parent::connect();
        $ins = $con->prepare("INSERT INTO cars(UserId,Seats,ModuleId,Status) VALUES(?,?,?,?)");
        $ins->bindValue(1,$driver);
        $ins->bindValue(2,$seats);
        $ins->bindValue(3,$type);
        $ins->bindValue(4,1);
        $ok_ins = $ins->execute();
        if ($ok_ins) {
            $res = "success";
            $seel = $con->prepare("SELECT * FROM cars WHERE cars.UserId='$driver'");
            $seel->execute();
            if ($seel->rowCount()>=1) {
                $upd = $con->prepare("UPDATE users SET users.Status=1 WHERE users.UserId='$driver_id'");
                $upd->execute();
            }
        }else{
            $res = "failed";
        }
        echo $res;
    }
}

/**
 * ========================================== SAVE ADDRESS RIDER -- HOME
 */
class SaveRiderAddressHome extends DbConnect
{
    
    function __construct($user_id,$address)
    {
      if($address=='' OR $address==null){
        $data['save_rider_home_address'] = "no_address";
      }else{
        $con = parent::connect();

        $upd = $con->prepare("UPDATE users SET users.Address=? WHERE users.UserId=?");
        $upd->bindValue(1,$address);
        $upd->bindValue(2,$user_id);
        $upd_ok = $upd->execute();
        if($upd_ok){
          $data['save_rider_home_address'] = "updated";
        }else{
          $data['save_rider_home_address'] = "not_updated";
        }
      }
      $save_rider_home_address[] = $data;
      print(json_encode($save_rider_home_address));
    }
}
/**
 * ========================================== SAVE ADDRESS RIDER -- WORK
 */
class SaveRiderAddressWork extends DbConnect
{
    
    function __construct($user_id,$address)
    {
      if($address=='' OR $address==null){
        $data['save_rider_work_address'] = "no_address";
      }else{
        $con = parent::connect();
        $sel = $con->prepare("SELECT * FROM addresses,users WHERE addresses.UserId=users.UserId AND addresses.AddressType=1 AND addresses.UserId=? AND addresses.AddressStatus=1");
        $sel->bindValue(1,$user_id);
        $sel->execute();
        if($sel->rowCount()>=1){
          $upd = $con->prepare("UPDATE addresses SET addresses.AddressValue=? WHERE addresses.UserId=? AND addresses.AddressType=1 AND addresses.AddressStatus=1");
          $upd->bindValue(1,$address);
          $upd->bindValue(2,$user_id);
          $upd_ok = $upd->execute();
          if($upd_ok){
            $data['save_rider_work_address'] = "updated";
          }else{
            $data['save_rider_work_address'] = "not_updated";
          }
        }else{
          $ins = $con->prepare("INSERT INTO addresses(AddressType,AddressValue,UserId,AddressStatus) VALUES(?,?,?,?)");
          $ins->bindValue(1,1);
          $ins->bindValue(2,$address);
          $ins->bindValue(3,$user_id);
          $ins->bindValue(4,1);
          $ins_ok = $ins->execute();
          if($ins_ok){
            $data['save_rider_work_address']  = "inserted";
          }else{
            $data['save_rider_work_address'] = "not_inserted";
          }
        }
      }
      $save_rider_work_address[] = $data;
      print(json_encode($save_rider_work_address));
    }
}
//====================================== UPDATE ONKINE DRIVERS
class UpdateOnlineDriver extends DbConnect{
 function __construct($user,$lat,$long){
  $con = parent::connect();
  $record = $con->prepare("INSERT INTO online_drivers(Latitude,Longitude,UserId,OnlineStatus,OnlineTime) VALUES(?,?,?,?,NOW())");
  $record->bindValue(1,$lat);
  $record->bindValue(2,$long);
  $record->bindValue(3,$user);
  $record->bindValue(4,1);
  $ok_record = $record->execute();
  if($ok_record){
    $data['online_update_status'] = "recorded";
  }else{
    $data['online_update_status'] = "not_recorded";
  }
  $online_update_status[] = $data;
  print(json_encode($online_update_status));
 }
}

//====================================== UPDATE ONLINE DRIVERS
class OnlineDrivers extends DbConnect{
  function __construct(){
   $con = parent::connect();
   $sel = $con->prepare("SELECT users.UserId AS driver_id,online_drivers.Latitude AS driver_latitude,online_drivers.Longitude AS driver_longitude,
   users.FullName AS driver_names,users.Phone AS driver_phone,users.Picture AS driver_picture,carmodels.Model AS car_type,cars.Seats AS car_seats, true AS online_drivers_is_found
    FROM online_drivers,users,cars,carmodels WHERE online_drivers.UserId=users.UserId AND cars.UserId=users.UserId AND cars.ModuleId=carmodels.ModelId AND NOW()<=TIMESTAMPADD(SECOND,100,online_drivers.OnlineTime) GROUP BY online_drivers.UserId");
   $sel->execute();
   if($sel->rowCount()>=1){
    $cntt=0;
    while($ft_sel = $sel->fetch(PDO::FETCH_ASSOC)){
      $data['online_drivers'][$cntt] = ($mani_array[]=$ft_sel);
      $cntt++;
    }
    print(json_encode($data));
   }else{
    $data['online_drivers_is_found'] = false;
    $online_drivers[] = $data;
    print(json_encode($online_drivers));
   }
  }
 }

 //====================================== Rider Search Drivers
class RiderSearchDrivers extends DbConnect{
  function __construct($user_id,$latitude,$longitude){
   $con = parent::connect();
   $ins = $con->prepare("INSERT INTO riders_requests(RequestRider,RequestLatitude,RequestLongitude,RequestStatus) VALUES(?,?,?,?)");
   $ins->bindValue(1,$user_id);
   $ins->bindValue(2,$latitude);
   $ins->bindValue(3,$longitude);
   $ins->bindValue(4,1);
   $ok_ins = $ins->execute();
   if($ok_ins){
     $sel = $con->prepare("SELECT * FROM users WHERE users.UserId=?");
     $sel->bindValue(1,$user_id);
     $sel->execute();
     if($sel->rowCount()>=1){
        $ft_sel = $sel->fetch(PDO::FETCH_ASSOC);
        $data = $ft_sel;
        $data['rider_latitude'] = $latitude;
        $data['rider_longitude'] = $longitude;
        $data['ride_requested'] = "yes";
     }
   }else{
    $data['ride_requested'] = "no";     // print_r($ins->errorInfo());
   }
    $ridersearchlocation[] = $data;
    print(json_encode($ridersearchlocation));
  }
 }

  //====================================== GET DRIVER LOCATION UPDATE
class GetDriverUpdateLocation extends DbConnect{
  function __construct($user_id){
   $con = parent::connect();
   $sel = $con->prepare("SELECT * FROM online_drivers,users WHERE users.UserId=online_drivers.UserId AND online_drivers.UserId=? ORDER BY online_drivers.OnlineTime DESC");
   $sel->bindValue(1,$user_id);
   $sel->execute();
   if($sel->rowCount()>=1){
    $ft_sel = $sel->fetch(PDO::FETCH_ASSOC);
    $data = $ft_sel;
    $data['driver_id'] = $ft_sel['UserId'];
    $data['driver_name'] = $ft_sel['FullName'];
    $data['driver_latitude'] = $ft_sel['Latitude'];
    $data['driver_longitude'] = $ft_sel['Longitude'];
    $data['driver_phone'] = $ft_sel['Phone'];
    $data['driver_email'] = $ft_sel['Email'];

    $data['is_driver_location_found'] = "yes";
   }else{
    $data['is_driver_location_found'] = "no";
   }
    $getdriverlocation[] = $data;
    print(json_encode($getdriverlocation));
  }
 }

   //====================================== Driver accept ride request Form Rider
class DriverAcceptRequestFromRider extends DbConnect{
  function __construct($driver_id,$rider_id,$driver_latitude,$driver_longitude,$rider_latitude,$rider_longitude){
   $con = parent::connect();
   $status_driver = $status_rider = false;
    if($driver_id!=$rider_id){
      $sel_rdr = $con->prepare("SELECT * FROM users WHERE UserId=? AND users.Type=1");
      $sel_rdr->bindValue(1,$driver_id);
      $sel_rdr->execute();
      if($sel_rdr->rowCount()>=1){
        $ft_sel_rdr = $sel_rdr->fetch(PDO::FETCH_ASSOC);
        $status_driver = true;
      }

      $sel_drvr = $con->prepare("SELECT * FROM users WHERE UserId=? AND users.Type=0");
      $sel_drvr->bindValue(1,$rider_id);
      $sel_drvr->execute();
      if($sel_drvr->rowCount()>=1){
        $ft_sel_drvr = $sel_drvr->fetch(PDO::FETCH_ASSOC);
        $status_rider = true;
      }
      if ($status_rider && $status_driver) {
        $upd = $con->prepare("UPDATE rides_requests SET rides_requests.RequestStatus=0 WHERE rides_requests.DriverId='$driver_id' AND rides_requests.RiderId='$rider_id' AND rides_requests.RequestStatus=1");
        $ok_upd = $upd->execute();
        if ($ok_upd) {
            $data['is_request_accepted'] = "yes";
            $data['driver_name'] = $ft_sel_rdr['FullName'];
            $data['rider_name'] = $ft_sel_drvr['FullName'];
            $data['driver_phone'] = $ft_sel_rdr['Phone'];
            $data['rider_phone'] = $ft_sel_drvr['Phone'];
            $data['driver_latitude'] = $driver_latitude;
            $data['driver_longitude'] = $driver_longitude;
            $data['rider_latitude'] = $rider_latitude;
            $data['rider_longitude'] = $rider_longitude;
         }else{
            $data['is_request_accepted'] = "no";
         } 
      }else{
        $data['is_request_accepted'] = "no";
      }
    }else{
      $data['is_request_accepted'] = "no";
    }
    $getdriverlocation[] = $data;
    print(json_encode($getdriverlocation));
  }
 }



   //====================================== request a ride from chosen driver
class requestRideFromGivenDriver extends DbConnect{
  function __construct($driver_id,$rider_id,$driver_latitude,$driver_longitude,$rider_latitude,$rider_longitude,$destination_latitude,$destination_longitude){
    $con = parent::connect();
    $status_driver = $status_rider = false;
     if($driver_id!=$rider_id){
       $sel_rdr = $con->prepare("SELECT * FROM users WHERE UserId=? AND users.Type=1");
       $sel_rdr->bindValue(1,$driver_id);
       $sel_rdr->execute();
       if($sel_rdr->rowCount()>=1){
         $ft_sel_rdr = $sel_rdr->fetch(PDO::FETCH_ASSOC);
         $status_driver = true;
       }
 
       $sel_drvr = $con->prepare("SELECT * FROM users WHERE UserId=? AND users.Type=0");
       $sel_drvr->bindValue(1,$rider_id);
       $sel_drvr->execute();
       if($sel_drvr->rowCount()>=1){
         $ft_sel_drvr = $sel_drvr->fetch(PDO::FETCH_ASSOC);
         $status_rider = true;
       }
       if ($status_rider && $status_driver) {
        $ins = $con->prepare("INSERT INTO rides_requests(RiderId,DriverId,RiderLatitude,RiderLongitude,DriverLatitude,DriverLongitude,DestinationLatitude,DestinationLongitude) VALUES(?,?,?,?,?,?,?,?)");
        $ins->bindValue(1,$rider_id);
        $ins->bindValue(2,$driver_id);
        $ins->bindValue(3,$rider_latitude);
        $ins->bindValue(4,$rider_longitude);
        $ins->bindValue(5,$driver_latitude);
        $ins->bindValue(6,$driver_longitude);
        $ins->bindValue(7,$destination_latitude);
        $ins->bindValue(8,$destination_longitude);
        $ok_ins = $ins->execute();
        if ($ok_ins) {
         $data['isRequesSent'] = "yes";
         $data['driver_id'] = $driver_id;
         $data['rider_id'] = $rider_id;
         $data['driver_name'] = $ft_sel_rdr['FullName'];
         $data['rider_name'] = $ft_sel_drvr['FullName'];
         $data['driver_phone'] = $ft_sel_rdr['Phone'];
         $data['rider_phone'] = $ft_sel_drvr['Phone'];
         $data['driver_latitude'] = $driver_latitude;
         $data['driver_longitude'] = $driver_longitude;
         $data['rider_latitude'] = $rider_latitude;
         $data['rider_longitude'] = $rider_longitude;
         $data['destination_latitude'] = $destination_latitude;
         $data['destination_longitude'] = $destination_longitude;
        }else{
            $data['isRequesSent'] = "no";
        }
       }else{
         $data['isRequesSent'] = "no";
       }
     }else{
       $data['isRequesSent'] = "no";
     }
     $sendrequesttogivendriver[] = $data;
     print(json_encode($sendrequesttogivendriver));
  }
 }

  //====================================== DRIVER GO ONLINE
class DriverGoOnline extends DbConnect{
  function __construct($driver_id,$latitude,$longitude){
  $con = parent::connect();
  $record = $con->prepare("INSERT INTO online_drivers(Latitude,Longitude,UserId,OnlineStatus,OnlineTime) VALUES(?,?,?,?,NOW())");
  $record->bindValue(1,$latitude);
  $record->bindValue(2,$longitude);
  $record->bindValue(3,$driver_id);
  $record->bindValue(4,1);
  $ok_record = $record->execute();
  if($ok_record){
    $upd = $con->prepare("UPDATE users SET users.IfDriverIsDriverOnline=1 WHERE users.UserId='$driver_id' AND users.Type=1");
    $ok_upd = $upd->execute();
    if ($ok_upd) {
        $data['driver_online_status'] = "recorded";
    }else{
        $data['driver_online_status'] = "not_recorded";
    }
  }else{
    $data['driver_online_status'] = "not_recorded2";
  }
    $driver_online_status[] = $data;
    print(json_encode($driver_online_status));
  }
 }
   //====================================== DRIVER GO OFFLINE
class DriverGoOffline extends DbConnect{
  function __construct($driver_id){
  $con = parent::connect();
  $upd1 = $con->prepare("UPDATE users SET users.IfDriverIsDriverOnline=0 WHERE users.UserId='$driver_id' AND users.Type=1");
  $ok_upd1 = $upd1->execute();
  if ($ok_upd1) {
      $upd2 = $con->prepare("UPDATE online_drivers SET online_drivers.OnlineStatus=0 WHERE online_drivers.UserId='$driver_id' AND online_drivers.OnlineStatus=1");
      $ok_upd2 = $upd2->execute();
      if ($ok_upd2) {
          $data['driver_offline_status'] = "recorded";
      }else{
        $data['driver_offline_status'] = "not_recorded";
      }
  }else{
    $data['driver_offline_status'] = "not_recorded";
  }
    $driver_offline_status[] = $data;
    print(json_encode($driver_offline_status));
  }
 }

    //====================================== Request to the Driver Side
class RideRequestsOnDriverSide extends DbConnect{
  function __construct($driver_id){
    $MainFeatures = new MainFeatures();
  $con = parent::connect();
  $sel = $con->prepare("SELECT * FROM rides_requests,users WHERE rides_requests.DriverId=users.UserId AND rides_requests.DriverId='$driver_id' AND users.Type=1 AND rides_requests.RequestStatus=1 ORDER BY rides_requests.RequestId DESC LIMIT 1");
  $sel->execute();
  if ($sel->rowCount()>=1) {
      $ft_sel = $sel->fetch(PDO::FETCH_ASSOC);
         $data['isRequesFound'] = "yes";
         $data['rider_id'] = $ft_sel['RiderId'];
         $data['driver_id'] = $driver_id;
         $data['rider_name'] = $MainFeatures->user_name($ft_sel['RiderId']);
         $data['rider_phone'] = $MainFeatures->user_phone($ft_sel['RiderId']);
         $data['rider_email'] = $MainFeatures->user_email($ft_sel['RiderId']);
         $data['rider_latitude'] = $ft_sel['RiderLatitude'];
         $data['rider_longitude'] = $ft_sel['RiderLongitude'];
         $data['destination_latitude'] = $ft_sel['DestinationLatitude'];
         $data['destination_longitude'] = $ft_sel['DestinationLongitude'];
  }else{
    $data['isRequesFound'] = "no";
  }
     $requesttodriverside[] = $data;
     print(json_encode($requesttodriverside));
  }
 }
   //====================================== Driver accept ride REJECT Form Rider
class DriverRejectRequestFromRider extends DbConnect{
  function __construct($driver_id,$rider_id,$driver_latitude,$driver_longitude,$rider_latitude,$rider_longitude){
   $con = parent::connect();
   $status_driver = $status_rider = false;
    if($driver_id!=$rider_id){
      $sel_rdr = $con->prepare("SELECT * FROM users WHERE UserId=? AND users.Type=1");
      $sel_rdr->bindValue(1,$driver_id);
      $sel_rdr->execute();
      if($sel_rdr->rowCount()>=1){
        $ft_sel_rdr = $sel_rdr->fetch(PDO::FETCH_ASSOC);
        $status_driver = true;
      }

      $sel_drvr = $con->prepare("SELECT * FROM users WHERE UserId=? AND users.Type=0");
      $sel_drvr->bindValue(1,$rider_id);
      $sel_drvr->execute();
      if($sel_drvr->rowCount()>=1){
        $ft_sel_drvr = $sel_drvr->fetch(PDO::FETCH_ASSOC);
        $status_rider = true;
      }
      if ($status_rider && $status_driver) {
        $upd = $con->prepare("UPDATE rides_requests SET rides_requests.RequestStatus=2 WHERE rides_requests.DriverId='$driver_id' AND rides_requests.RiderId='$rider_id' AND rides_requests.RequestStatus IN(1)");
        $ok_upd = $upd->execute();
        if ($ok_upd) {
            $cntt = $upd->rowCount();
            if ($cntt>=1) {
                $data['is_request_rejected'] = "yes";
                $data['is_this_requested_found_pending'] = "yes";
                $data['driver_name'] = $ft_sel_rdr['FullName'];
                $data['rider_name'] = $ft_sel_drvr['FullName'];
                $data['driver_phone'] = $ft_sel_rdr['Phone'];
                $data['rider_phone'] = $ft_sel_drvr['Phone'];
                $data['driver_latitude'] = $driver_latitude;
                $data['driver_longitude'] = $driver_longitude;
                $data['rider_latitude'] = $rider_latitude;
                $data['rider_longitude'] = $rider_longitude;
            }else{
                $data['is_this_requested_found_pending'] = "no";
            $data['is_request_rejected'] = "no";
            }
         }else{
            $data['is_request_rejected'] = "no";
            $data['is_this_requested_found_pending'] = "no";
         } 
      }else{
        $data['is_request_rejected'] = "no";
        $data['is_this_requested_found_pending'] = "no";
      }
    }else{
      $data['is_request_rejected'] = "no";
      $data['is_this_requested_found_pending'] = "no";
    }
    $getdriverlocation[] = $data;
    print(json_encode($getdriverlocation));
  }
 }

 /**
  * ======================================= CHECK DRIVER RECENT STATUS
  */
 class MyRecentStatus extends DbConnect
 {
     
     function __construct($driver_id)
     {
         $con = parent::connect();
         $sel = $con->prepare("SELECT * FROM users WHERE users.UserId='$driver_id' AND users.Type=1");
         $sel->execute();
         if ($sel->rowCount()==1) {
             $ft_sel = $sel->fetch(PDO::FETCH_ASSOC);
             $data['am_i_online'] = $ft_sel['IfDriverIsDriverOnline'];
             $data['is_driver_found'] = "yes";
         }else{
            $data['is_driver_found'] = "no";
         }
        $myrecentstatus[] = $data;
        print(json_encode($myrecentstatus));
     }
 }

  /**
  * ======================================= RIDER CANCEL Ride
  */
 class RiderCancelRide extends DbConnect
 {
     
     function __construct($rider_id,$driver_id)
     {
         $con = parent::connect();
         $sel = $con->prepare("SELECT * FROM rides_requests WHERE rides_requests.RiderId=? AND rides_requests.DriverId=? ORDER BY rides_requests.RequestId DESC LIMIT 1");
         $sel->bindValue(1,$rider_id);
         $sel->bindValue(2,$driver_id);
         $sel->execute();
         if ($sel->rowCount()==1) {
             $ft_sel = $sel->fetch(PDO::FETCH_ASSOC);
             $request_id = $ft_sel['RequestId'];
             $upd = $con->prepare("UPDATE rides_requests SET rides_requests.RequestStatus=3 WHERE rides_requests.RequestId='$request_id'");
             $ok_upd = $upd->execute();
             if ($ok_upd) {
                 
             $data['is_request_cancelled_by_rider'] = "yes";
             $data['is_request_found'] = "yes";

             }else{
             $data['is_request_cancelled_by_rider'] = "no";
             $data['is_request_found'] = "no";
             }
         }else{
             $data['is_request_cancelled_by_rider'] = "no";
             $data['is_request_found'] = "no";
         }
        $dataa[] = $data;
        print(json_encode($dataa));
     }
 }

  /**
  * ======================================= RIDER CANCEL Driver
  */
 class DriverCancelRide extends DbConnect
 {
     
     function __construct($rider_id,$driver_id)
     {
         $con = parent::connect();
         $sel = $con->prepare("SELECT * FROM rides_requests WHERE rides_requests.RiderId=? AND rides_requests.DriverId=? ORDER BY rides_requests.RequestId DESC LIMIT 1");
         $sel->bindValue(1,$rider_id);
         $sel->bindValue(2,$driver_id);
         $sel->execute();
         if ($sel->rowCount()==1) {
             $ft_sel = $sel->fetch(PDO::FETCH_ASSOC);
             $request_id = $ft_sel['RequestId'];
             $upd = $con->prepare("UPDATE rides_requests SET rides_requests.RequestStatus=4 WHERE rides_requests.RequestId='$request_id'");
             $ok_upd = $upd->execute();
             if ($ok_upd) {
                 
             $data['is_request_cancelled_by_driver'] = "yes";
             $data['is_request_found'] = "yes";

             }else{
             $data['is_request_cancelled_by_driver'] = "no";
             $data['is_request_found'] = "no";
             }
         }else{
             $data['is_request_cancelled_by_driver'] = "no";
             $data['is_request_found'] = "no";
         }
        $dataa[] = $data;
        print(json_encode($dataa));
     }
 }

 /**
  * ======================================= CHECK IF RIDER REQUEST HAS STATUS
  */
 class CheckRiderRequestatus extends DbConnect
 {
     
     function __construct($rider_id,$driver_id)
     {
         $con = parent::connect();
         $sel = $con->prepare("SELECT * FROM rides_requests WHERE rides_requests.RiderId=? AND rides_requests.DriverId=? ORDER BY rides_requests.RequestId DESC LIMIT 1");
         $sel->bindValue(1,$rider_id);
         $sel->bindValue(2,$driver_id);
         $sel->execute();
         if ($sel->rowCount()>=1) {
             $ft_sel = $sel->fetch(PDO::FETCH_ASSOC);
             $res_status = $ft_sel['RequestStatus'];
             switch ($res_status) {
                 case 0:
                     $status = 'approved';
                     break;
                 
                 case 2:
                     $status = 'rejected';
                     break;
                case 3:
                     $status = 'cancelled by rider';
                     break;
                case 4:
                     $status = 'cancelled by driver';
                     break;

                 default:
                     $status = 'pending';
                     break;
             }
             $MainFeatures = new MainFeatures();
             $data['is_request_found'] = "yes";
             $data['is_request_status'] = $status;

             $data['rider_id'] = $ft_sel['RiderId'];
             $data['driver_id'] = $ft_sel['DriverId'];

             $data['rider_name'] = $MainFeatures->user_name($ft_sel['RiderId']);
             $data['rider_phone'] = $MainFeatures->user_phone($ft_sel['RiderId']);
             $data['rider_email'] = $MainFeatures->user_email($ft_sel['RiderId']);

             $data['driver_name'] = $MainFeatures->user_name($ft_sel['DriverId']);
             $data['driver_phone'] = $MainFeatures->user_phone($ft_sel['DriverId']);
             $data['driver_email'] = $MainFeatures->user_email($ft_sel['DriverId']);

             $data['rider_latitude'] = $ft_sel['RiderLatitude'];
             $data['rider_longitude'] = $ft_sel['RiderLongitude'];

             $data['driver_latitude'] = $ft_sel['DriverLatitude'];
             $data['driver_longitude'] = $ft_sel['DriverLongitude'];

             $data['destination_latitude'] = $ft_sel['DestinationLatitude'];
             $data['destination_longitude'] = $ft_sel['DestinationLongitude'];
         }else{
             $data['is_request_found'] = "no";
         }
         $ddatta[] = $data;
         print(json_encode($ddatta));
     }
 }



if (isset($_POST['Login'])) {
    new Login($_POST['email'],$_POST['pass']);
}elseif (isset($_POST['RiderSignUp'])) {
    new RiderSignUp($_POST['fullname'],$_POST['email'],$_POST['phone'],$_POST['address'],$_POST['new_pass'],$_POST['conf_pass']);
}elseif (isset($_POST['DriverSignUp'])) {
    new DriverSignUp($_POST['fullname'],$_POST['email'],$_POST['phone'],$_POST['address'],$_POST['new_pass'],$_POST['conf_pass']);
}elseif (isset($_FILES['driving_licence'])) {
    new uploadDriverLicence($_FILES["driving_licence"]["name"],$_FILES["driving_licence"]["tmp_name"],$_FILES["driving_licence"]["size"],$_POST['driver']);
}elseif (isset($_FILES['vehicle_licence'])) {
    new uploadVehicleLicence($_FILES["vehicle_licence"]["name"],$_FILES["vehicle_licence"]["tmp_name"],$_FILES["vehicle_licence"]["size"],$_POST['driver']);
}elseif (isset($_FILES['insurance_paper'])) {
    new uploadInsurancePapers($_FILES["insurance_paper"]["name"],$_FILES["insurance_paper"]["tmp_name"],$_FILES["insurance_paper"]["size"],$_POST['driver']);
}elseif (isset($_GET['saveCarSeatsModel'])) {
    new saveCarSeatsModel($_GET['carType'],$_GET['carSeats'],$_GET['driver']);
}elseif (isset($_POST['SaveRiderAddressHome'])) {
  new SaveRiderAddressHome($_POST['user_id'],$_POST['address']);
}elseif (isset($_POST['SaveRiderAddressWork'])) {
  new SaveRiderAddressWork($_POST['user_id'],$_POST['address']);
}elseif (isset($_POST['UpdateOnlineDriver'])) {
  new UpdateOnlineDriver($_POST['user_id'],$_POST['latitude'],$_POST['longitude']);
}elseif (isset($_POST['OnlineDrivers'])) {
  new OnlineDrivers();
}elseif (isset($_POST['RiderSearchDrivers'])) {
  new RiderSearchDrivers($_POST['user_id'],$_POST['latitude'],$_POST['longitude']);
}elseif (isset($_POST['GetDriverUpdateLocation'])) {
  new GetDriverUpdateLocation($_POST['driver_id']);
}elseif (isset($_POST['DriverAcceptRequestFromRider'])) {
  new DriverAcceptRequestFromRider($_POST['driver_id'],$_POST['rider_id'],$_POST['driver_latitude'],$_POST['driver_longitude'],$_POST['rider_latitude'],$_POST['rider_longitude']);
}elseif (isset($_POST['requestRideFromGivenDriver'])) {
  new requestRideFromGivenDriver($_POST['driver_id'],$_POST['rider_id'],$_POST['driver_latitude'],$_POST['driver_longitude'],$_POST['rider_latitude'],$_POST['rider_longitude'],$_POST['destination_latitude'],$_POST['destination_longitude']);
}elseif (isset($_POST['DriverGoOnline'])) {
  new DriverGoOnline($_POST['driver_id'],$_POST['driver_latitude'],$_POST['driver_longitude']);
}elseif (isset($_POST['DriverGoOffline'])) {
  new DriverGoOffline($_POST['driver_id']);
}elseif (isset($_POST['RideRequestsOnDriverSide'])) {
  new RideRequestsOnDriverSide($_POST['driver_id']);
}elseif (isset($_POST['DriverRejectRequestFromRider'])) {
  new DriverRejectRequestFromRider($_POST['driver_id'],$_POST['rider_id'],$_POST['driver_latitude'],$_POST['driver_longitude'],$_POST['rider_latitude'],$_POST['rider_longitude']);
}elseif (isset($_POST['MyRecentStatus'])) {
  new MyRecentStatus($_POST['driver_id']);
}elseif (isset($_POST['CheckRiderRequestatus'])) {
  new CheckRiderRequestatus($_POST['rider_id'],$_POST['driver_id']);
}elseif (isset($_POST['RiderCancelRide'])) {
  new RiderCancelRide($_POST['rider_id'],$_POST['driver_id']);
}elseif (isset($_POST['DriverCancelRide'])) {
  new DriverCancelRide($_POST['rider_id'],$_POST['driver_id']);
}



?>