<?php

session_start();

    if (isset($_SESSION['loggedINW'])) {

        $conn = mysqli_connect('localhost', 'root', '', 'spec_demo');
        if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
        }

        header('Location: inspector.php');
        exit();
    }

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta http-equiv="x-ua-compatible" content="ie=edge">
  <title>QC Inspection By tablet Project</title>
  <link rel="manifest" href="manifest.json">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.0/css/all.css">
  <link rel="stylesheet" href="./plugins/sweetaleart2/dist/sweetalert2.min.css">
  <!-- Bootstrap core CSS -->
  <link href="css/bootstrap.min.css" rel="stylesheet">
  <!-- Material Design Bootstrap -->
  <link href="css/mdb.min.css" rel="stylesheet">
  <!-- Your custom styles (optional) -->
  <link href="css/style.css" rel="stylesheet">
  <link href="css/login.css" rel="stylesheet">
</head>

<body>              
  <form class="form-login text-center" method="POST" id="form-login">
    <!-- <img class="mb-3" src="img/logo.png" alt="" width="100" height="72"> -->
    <h1 class="mb-3 font-weight-normal text-center">Demo</h1>
    <h5 class="mb-3 font-weight-normal text-center">QC Inspection By tablet Project</h5>
    <label for="inputUsername" class="sr-only">Username</label>
    <input type="text" id="inputUsername" class="form-control" placeholder="Username" autofocus maxlength="10" spellcheck="false" autocomplete="off">
    <label for="inputPassword" class="sr-only">Password</label>
    <input type="password" id="inputPassword" class="form-control" placeholder="Password" maxlength="10" spellcheck="false" autocomplete="off">
    <button class="btn btn-lg btn-primary btn-block" id="login-btn">login <i class="fas fa-sign-in-alt"></i></button>
    <footer id="footer"><p class="mt-5 mb-3 text-muted text-center">by Metrosystems Corporation Public Company Limited</p></footer>
  </form>


  <!-- SCRIPTS -->
  <!-- JQuery -->
  <script type="text/javascript" src="js/jquery-3.3.1.min.js"></script>
  <!-- Bootstrap tooltips -->
  <script type="text/javascript" src="js/popper.min.js"></script>
  <script src="./plugins/sweetaleart2/dist/sweetalert2.min.js"></script>
  <script src="./plugins/moment/moment.js"></script>
  <!-- Bootstrap core JavaScript -->
  <script type="text/javascript" src="js/bootstrap.min.js"></script>
  <!-- MDB core JavaScript -->
  <script type="text/javascript" src="js/mdb.js"></script>

  <script>

    function login (){

      var userName = $("#inputUsername").val();
      var passWord = $("#inputPassword").val();

      if (userName == "" || passWord == ""){

          
          swal({
                  title: 'ไม่สามารถเข้าสู่ระบบได้',
                  text: 'กรุณาใส่ Username หรือ Password',
                  type: 'error',
                  heightAuto: false,
                  confirmButtonText: 'ตกลง'
              });

      }else {

          $.ajax({
              url: 'login_data.php',
              data:{
                  "mode":"login",
                  'username':userName,
                  'password':passWord
              },
              type : "POST",
              dataType : "json",
              success: function(data) {
                  //console.log(data);
                  if(data[0].ERROR == 'success'){

                      console.log('success');
                      
                      swal({
                          position: 'center',
                          heightAuto: false,
                          type: 'success',
                          title: 'Login Success',
                          showConfirmButton: false,
                          timer: 1000
                      }).then((result) => {

                        if(data[0].ROLE == 'inspection'){
                          window.location.href = "inspector.php";
                        }else{
                          if (data[0].ROLE == 'qc') {
                            window.location.href = "qc.php";
                          }else{
                            window.location.href = "repair_v2.php";
                          }
                        }
                        
                          });
                        
                  }
                  else {
                          console.log('failed');
                          swal({
                              title: 'ไม่สามารถเข้าสู่ระบบได้',
                              text: 'Username หรือ Password ไม่ถูกต้อง',
                              type: 'error',
                              heightAuto: false,
                              confirmButtonText: 'ตกลง'
                          });
                      }
                      
              },
              error: function (request, status, error) {
                  console.log(error,status);
              }
          });//end ajax


      }

          
          
    }

       $(document).ready(function(){
            
            $("#form-login").submit(function(e){
                return false;
            });
    
            $('#login-btn').click(function(){
                login();
            });
    
        });

  </script>
</body>

</html>
