<?php
	session_start();

	if(!isset($_SESSION['loggedINW'])){
        header('Location: index.php');
        exit();
    }
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta http-equiv="x-ua-compatible" content="ie=edge">
  <title>Material Design Bootstrap</title>
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.0/css/all.css">
  <link rel="stylesheet" href="./plugins/sweetaleart2/dist/sweetalert2.min.css">
  <!-- Bootstrap core CSS -->
  <link href="css/bootstrap.min.css" rel="stylesheet">
  <!-- Material Design Bootstrap -->
  <link href="css/mdb.min.css" rel="stylesheet">
  <link rel="stylesheet" href="./css/material.min.css">
  <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
  <!-- Your custom styles (optional) -->
  <link href="css/style.css" rel="stylesheet">
  <link rel="stylesheet" type="text/css" media="screen" href="./css/main.css">
  <link rel="stylesheet" type="text/css" media="screen" href="./style.css">

  <style>
  .carousel-inner{
    height: 130px;
    border-radius:.25rem .25rem 0 0;
  }

  .loading {
    position: absolute;
    top: 50%;
    left: 50%;
    margin: -50px 0px 0px -50px;
    }

    .mdl-badge[data-badge]:after{
      right: -15px !important;
    }
  </style>

</head>

<body>
<!-- Start your project here-->
<div class="mdl-layout mdl-js-layout mdl-layout--fixed-header mdl-layout--fixed-tabs">
  <header class="mdl-layout__header">
      <div class="mdl-layout__header-row">
        <!-- Title -->
        <span class="mdl-layout-title">Pending Repair</span>
        <div class="mdl-layout-spacer"></div>
      </div>

  </header>
      
  <div class="mdl-layout__drawer">
      <span class="mdl-layout-title">
          <img src="./img/profile-pic.jpg" alt="profilepic" id="profile-pic">
          <span id="profile-info">
              <p><?php echo $_SESSION['ename_w']; ?> : <?php echo $_SESSION['role']; ?></p>
          </span>
        </span>   
        <!-- Navigation -->
        <nav class="mdl-navigation">
          <a class="mdl-navigation__link" id="logout" href="logout.php">Log out</a>
          <a class="mdl-navigation__link" id="reset" href="reset.php">Reset</a>
        </nav>
  </div>

  <main class="mdl-layout__content" id="ispPage">
      <div class="page-content">

      <div class="d-flex justify-content-center loading">
          <div class="spinner-border" id="loader" style="width: 5rem; height: 5rem;display:none;" role="status">
            <span class="sr-only">Loading...</span>
          </div>
        </div>

        <div id="group1">
          <h4 class="m-0 mt-3 pl-3 font-weight-bold"><span class="mdl-badge" id="notiG1" data-badge="">Group 1</span></h4>
          <div class="row m-0 scrolling-wrapper-flexbox" id="rg1">
          </div> <!--end row-->
        </div> <!--Group1-->

        <div id="group2">
        <h4 class="m-0 mt-3 pl-3 font-weight-bold"><span class="mdl-badge" id="notiG2" data-badge="">Group 2</span></h4>
          <div class="row m-0 scrolling-wrapper-flexbox" id="rg2">
          </div>
        </div>
        
        
        <!-- The Modal -->
        <div id="myModal" class="modal">
          <!-- The Close Button -->
          <span style="margin-top:60px" class="close_pic">&times;</span>
          <!-- Modal Content (The Image) -->
          <img class="modal-content" id="img01">
          <!-- Modal Caption (Image Text) -->
          <div id="caption"></div>
        </div>
      </div>   
  </main>  

</div> 

  <!-- /Start your project here-->

  <!-- SCRIPTS -->
  <!-- JQuery -->
  <script type="text/javascript" src="js/jquery-3.3.1.min.js"></script>
  <!-- Bootstrap tooltips -->
  <script type="text/javascript" src="js/popper.min.js"></script>
  <script src="./plugins/sweetaleart2/dist/sweetalert2.min.js"></script>
  <!-- Bootstrap core JavaScript -->
  <script type="text/javascript" src="js/bootstrap.min.js"></script>
  <!-- MDB core JavaScript -->
  <script type="text/javascript" src="js/mdb.js"></script>
  <script src="./js/material.min.js"></script>

  <script type="text/javascript">
   

  </script>

  <script>
    $(document).ready(function(){

        var repair_data = [];
          $.ajax({
            url: 'repair_db.php',
            dataType: "json",
            data: { "mode":"get_data" },
            type : "POST",
            beforeSend: function() {
              $('#loader').show();
            },
           // async: true, //blocks window close
            success: function(result) {
              setTimeout(() => {
                $('#loader').hide();
                // repair_data = data;
                create_element(result);
              }, 1500);
              
              $('.carousel').carousel({
              touch: true // default
              });

            },
            error: function (request, status, error) {
                    if(error){
                        alert(error);
                    }
                    console.log(error,status);
            }

        });

      });
  </script>

  <script>

  function create_element(data) {

    $.each(data, function(value){

      if(data[value]['DB_CHOOSE'] =1){
            ans= data[value]['DB_SPEC1'];
          }else if(data[value]['DB_CHOOSE'] =2){
            ans= data[value]['DB_SPEC2'];
          }else if(data[value]['DB_CHOOSE'] =3){
            ans= data[value]['DB_SPEC3'];
          }

          pic2='';
          if(data[value]['DB_PIC2'].trim() != ''){
              pic2 ='<div class="carousel-item"><img class="w-100 d-block found_pic" src="'+data[value]['DB_PIC2']+'"></div>';
          }else{
            pic2 = '';
          }

          pic3='';
          if(data[value]['DB_PIC3'].trim() != ''){
              pic3 ='<div class="carousel-item"><img class="w-100 d-block found_pic" src="'+data[value]['DB_PIC3']+'"></div>';
          }else{
            pic3 = '';
          }

        $("#group"+data[value]['DB_GROUP']).find( ".row" ).append(
          `<div class="col-sm-5 p-3" id="element-${data[value]['DB_DEFECT']}">
              <div class="card" id="repairCard">
                <div id="carouselExampleSlidesOnly${data[value]['DB_DEFECT']}" class="carousel slide" data-ride="carousel" data-interval="false">
                  <ol class="carousel-indicators" id="${data[value]['DB_DEFECT']}">
                    <li data-target="#carouselExampleSlidesOnly${data[value]['DB_DEFECT']}" data-slide-to="0" class="active"></li>
                  </ol>
                  <div class="carousel-inner" id="${data[value]['DB_DEFECT']}">
                    <div class="carousel-item active">
                      <img class="w-100 d-block found_pic" src="${data[value]['DB_PIC1']}">
                    </div>
                  </div>
                </div>

                <div class="card-body">
                  <h4 class="card-title m-0 pb-1"><a>Q. </a>${data[value]['DB_QUESTION']}</h4>
                  <h5 class="m-0"><a>P. </a>${data[value]['DB_PROBLEM']}</h5>
                  <hr>
                  <!-- <p class="card-text"></p> -->
                  <label for="fix-repair">Fix</label>
                  <select class="form-control " id="fix-repair-${data[value]['RUNN']}">
                    <option disabled selected>--</option>
                    <option>ขันแน่น</option>
                    <option>ซ่อมสี</option>
                    <option>เปลี่ยนชิ้นส่วน</option>
                    <option>ไล่ลมเบรค</option>
                  </select>
                  <div class="text-center mt-3">
                    <a href="#" class="btn aqua-gradient b-radius save" data-elid="form-repair-${data[value]['RUNN']}" id="${data[value]['DB_DEFECT']}">Save</a>
                  </div>
                </div>
              </div>
            </div>`
        
        );

        if (pic2 != '') {
          $(".carousel-indicators"+ "#"+data[value]['DB_DEFECT']).append(
            `<li data-target="#carouselExampleSlidesOnly${data[value]['DB_DEFECT']}" data-slide-to="1"></li>`            
          );

          $(".carousel-inner"+ "#"+data[value]['DB_DEFECT']).append(
            pic2
          );

        }

        if (pic3 != '') {
          $(".carousel-indicators"+ "#"+data[value]['DB_DEFECT']).append(
            `<li data-target="#carouselExampleSlidesOnly${data[value]['DB_DEFECT']}" data-slide-to="2"></li>`            
          );
          $(".carousel-inner"+ "#"+data[value]['DB_DEFECT']).append(
            pic3
          );
        }
                           
    });

    var notig1 = $("#rg1").children().length;
    var notig2 = $("#rg2").children().length;
    
    $("#notiG1").attr("data-badge",notig1);
    $("#notiG2").attr("data-badge",notig2);

    $('.save').on('click', function(){
    
      var v = $(this).data('elid');
      var w = this.id;
      var id = v.substring(12,14);
     

      var fix_reason = $('#fix-repair-'+id).val(); 
      console.log(v);
      console.log(id);
      console.log(fix_reason);
      

      if (fix_reason == null){

            swal({
                heightAuto: false,
                title: 'กรุณาเลือกวิธีการซ่อม!',
                type: 'warning',
                confirmButtonText: 'ตกลง'
            });
            return false;

      }else{

            $.ajax({
              url: 'repair_db.php',
              // dataType: "json",
              data: { "mode":"save_note", "rid":w,"note":fix_reason},
              type : "POST",
              beforeSend: function(){
                  swal({
                      position: 'center',
                      heightAuto: false,
                      title: 'Loading...',
                      showConfirmButton: false,
                      onBeforeOpen: () => {
                          Swal.showLoading()
                      }
                  });
              },
              success: function() {

                swal({
                    position: 'center',
                    heightAuto: false,
                    type: 'success',
                    title: 'บันทึกสำเร็จ',
                    showConfirmButton: false,
                    timer: 1000,
                    onBeforeOpen: () => {
                      Swal.hideLoading()
                    }
                }).then((result) => {

                  $('#element-'+w).remove();
                  var notig1 = $("#rg1").children().length;
                  var notig2 = $("#rg2").children().length;
                  $("#notiG1").attr("data-badge",notig1);
                  $("#notiG2").attr("data-badge",notig2);
                  
                });
              },
              error: function (request, status, error) {
                      if(error){
                          alert(error);
                      }
                      console.log(error,status);
              }

          });

        }


    });

    $('.found_pic').on('click', function(){
      var $v = $(this).closest("img");

      // console.log($v[0].alt);

      var modal = document.getElementById('myModal');
      var modalImg = document.getElementById("img01");
      //var captionText = document.getElementById("caption");

      modal.style.display = "block";
      modalImg.src = this.src;
      // captionText.innerHTML = this.alt;

    });


    // Get the <span> element that closes the modal
    var span = document.getElementsByClassName("close_pic")[0];

    // When the user clicks on <span> (x), close the modal
    span.onclick = function() { 
    var modal = document.getElementById('myModal');
    modal.style.display = "none";
    }

    
  }

  </script>
 
</body>

</html>
