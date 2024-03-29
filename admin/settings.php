<?php include '../include/dbcon.php';?>
<?php
session_start();

  if(!isset($_SESSION['hash']))
  {
    header('location:index.php');
  }
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>Settings</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="icon" href="../image/rahul-fav.png" sizes="16x16" type="image/png">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>
</head>
<body>


<nav class="navbar navbar-expand-md mymenu navbar-dark bg-dark">
      <div class="container-fluid">
        <a href="index.php" class="navbar-brand">Admin Portal</a>

        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#mymenu">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse text-center" id="mymenu">
          <ul class="navbar-nav ml-auto">
            <li class="nav-item">
              <a class="nav-link" href="dashboard.php">Dashboard</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="attendee.php">Attendee</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="feedback.php">View Feedback</a>
            </li>
            <li class="nav-item">
              <a class="nav-link active" href="settings.php">Change Password</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="logout.php">Logout</a>
            </li>
          </ul>
        </div>
      </div>
</nav>

<br>
  
<div class="container">
      <div class="row">
        <div class="col-lg-6 col-md-6 col-sm-12 col-12 d-block m-auto">
          <div class="card shadow p-4 mb-4 bg-white">
              <form action="settings.php" method="POST">
                Current Password:<input type="password" name="cpass" class="form-control" value="<?php echo $_POST['cpass'] ?>" required><br>
                New Password:<input type="password" name="npass" class="form-control" value="<?php echo $_POST['npass'] ?>" required><br>
                Confirm New Password:<input type="password" name="cnpass" class="form-control" value="<?php echo $_POST['cnpass'] ?>" required><br> 
                <input type="submit" name="submit" value="Update" class = "btn btn-info btn-block">
              </form>
        <br>
        <?php

            if(isset($_POST['submit']))
            {

              $ses_hash = $_SESSION['hash'];
              $qry = "SELECT * FROM admin WHERE hash = '$ses_hash';";
              $res = $mysqli->query($qry) or die(error.__LINE__);
              $row = mysqli_fetch_assoc($res);

              $admin_pass = $row['password'];


              $current_pass = $_POST['cpass'];
              $nwpass = $_POST['npass'];
              $cnwpass = $_POST['cnpass'];

              if($nwpass == '' || $cnwpass == '' || $current_pass == ''){
                echo "<p class='text-center text-danger'>Please fill all the fields!!</p>";
              }
              else{

                if($nwpass != $cnwpass){
                   echo "<p class='text-center text-danger'>Both password should match!!</p>";
                }
                else
                {
                  if(md5($current_pass) == $admin_pass)
                  {

                    $upass = md5($nwpass);

                    $qry = "UPDATE `admin` SET `password` = '$upass' WHERE `hash` = '$ses_hash';";
                    $result = $mysqli->query($qry) or die(error.__LINE__);
                    if($result == true)
                    {
                      echo "<p class='text-center text-success'>Password changed successfully</p>";
                      header( "refresh:0.5;url=settings.php" );
                    }
                  }
                  else
                  {
                    echo "<p class='text-center text-danger'>Current password doesn't matched!!</p>";
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
