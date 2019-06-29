<?php

include ('include/dbcon.php');



session_start();

require('textlocal.class.php');

require('credential.php');

date_default_timezone_set('Asia/Kolkata');

?>



<!DOCTYPE html>

<html lang="en">

<head>

  <title>Student Login</title>

  <meta charset="utf-8">

  <link rel="icon" href="../image/rahul-fav.png" sizes="16x16" type="image/png">

  <meta name="viewport" content="width=device-width, initial-scale=1">

  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">

  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>

  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>

</head>

<body>



<br>

<br>

<br>

<div class="container">
  <div class="row">


        <div class="col-lg-6 col-md-6 col-sm-12 col-12 d-block m-auto">

          <div class="card shadow p-4 mb-4 bg-white">

            <?php

              $email = $_GET['email'];

              $hash = $_GET['hash'];

              $qry = "SELECT * FROM `graduates` WHERE `email`='$email' AND `hash`='$hash';";

              $res = $mysqli->query($qry) or die(error.__LINE__);

              $cnt = mysqli_num_rows($res);

              $row = mysqli_fetch_assoc($res);

              if($cnt == 0)

              {

                ?>  <br>

                  <div class="container">

                    <div class="alert alert-danger">

                    <strong>Failed!</strong> Invalid Link!!

                    </div>

                  </div>

                <?php

              }

              else

              {

                $qry = "SELECT * FROM `attendance` WHERE `email`='$email' AND `a1` <> '';";

                $res = $mysqli->query($qry) or die(error.__LINE__);

                $cnt = mysqli_num_rows($res);

                if($cnt == 1)

                {

                  ?>  <br>

                    <div class="container">

                      <div class="alert alert-success">

                        Your Response already submitted!!!

                      </div>

                    </div>

                  <?php

                }

                else

                {
		  $qry = "UPDATE graduates SET mobile='', pvt_email='' WHERE email='$email';";
		  $res = $mysqli->query($qry) or die($mysqli->error);
	          $qry = "DELETE FROM `attendance` WHERE `email`='$email';";
                  $res = $mysqli->query($qry) or die(error.__LINE__);
                  ?>

                    <h4 class="text-center text-info">Welcome, <?php echo $row['name']?></h4>

                    <p class="text-center text-info"><b>USN : <?php echo $row['usn']?><br>Dept : <?php echo $row['dept']?></b></p><br>

                    <form action="" method="POST">
		    <div id="mobile_no">
                      <input type="text" name="mobile" class="form-control" placeholder="Enter Mobile No." onkeyup="valid();" id="mobile"><br>

                      <input type="submit" name="submit" value="Send OTP" class="btn btn-info btn-block"><br>
		    </div>
	     	    <div id="otp_valid" style="display: none;">
                      <input type="text" name="otp" class="form-control" placeholder="Enter OTP"><br>

                      <input type="submit" name="otpverify" value="Verify OTP" class="btn btn-success btn-block">
		    </div>
                    </form>

                  <?php

                  if(isset($_POST['submit']))

                  {

                    $mobile = $_POST['mobile'];



                    $match = preg_match('/^[0-9]{10}+$/', $mobile);



                    if($match == 1)

                    {

                      $qry = "SELECT * FROM `graduates` WHERE `mobile`='$mobile';";

                      $res = $mysqli->query($qry) or die(error.__LINE__);

                      $cnt = mysqli_num_rows($res);

                      if($cnt > 0)

                      {

                        ?>  

                          <br><p class="text-danger text-center">Mobile Number already register!!</p>

                        <?php

                      }

                      else

                      {
			echo '<script type="text/javascript">

                                $(document).ready(function(){

                                  $("#otp_valid").show();

                                  $("#mobile_no").hide();

                                });

                              </script>';

                        $textlocal = new Textlocal(false, false, API_KEY);

                        $numbers = array($_POST['mobile']);

                        $sender = 'RVCEGD';

                        $otp = mt_rand(100000, 999999);

                        $message = "Hello ".$row['name']." . This is your OTP: ".$otp;

                        try {

                            $result = $textlocal->sendSms($numbers, $message, $sender);

                            $_SESSION['otp'] = $otp;

                        } catch (Exception $e) {

                            die('Error: ' . $e->getMessage());

                        }

                        ?>  

                          <br><p class="text-success text-center">OTP sent to <?php echo $mobile?></p>

                        <?php                       

                        $_SESSION['mobile'] = $mobile;

                      }

                    }

                    else

                    {

                      ?>  

                        <br><p class="text-danger text-center">Invalid Mobile Number!!</p>

                      <?php

                    }

                  }



                  if(isset($_POST['otpverify'])){

                    $otp = $_POST['otp'];

                    $mobile = $_SESSION['mobile'];



                    if($_SESSION['otp'] == $otp)

                    {

                      $_SESSION['otp'] = '0';

                      $qry = "UPDATE graduates SET mobile='$mobile' WHERE email = '$email'";

                      $res = $mysqli->query($qry) or die(error.__LINE__);
		      echo '<script type="text/javascript">

                                $(document).ready(function(){

                                  $("#otp_valid").show();

                                  $("#mobile_no").hide();

                                });

                              </script>';
                      ?>

                        <br><p class="text-success text-center">OTP verifed successfully , Redirecting ... !!</p>

                        <?php

                            $_SESSION['name'] = $row['name'];

                            $_SESSION['usn'] = $row['usn'];

                            $_SESSION['email'] = $row['email'];

                            $_SESSION['dept'] = $row['dept'];

			    header('location:nad.php');

                    }

                    else

                    {
		      echo '<script type="text/javascript">

                                $(document).ready(function(){

                                  $("#otp_valid").show();

                                  $("#mobile_no").hide();

                                });

                              </script>';

                      ?>  

                        <br><p class="text-danger text-center">Invalid OTP!!</p>

                      <?php

                    }

                  }

                }

              }

            ?>



          </div>

        </div>
</div>
</div>

</body>

</html>
