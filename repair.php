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
  <!-- <link rel="stylesheet" type="text/css" media="screen" href="./dropdown.css"> -->

<style>

</style>

</head>

<body>
<!-- Start your project here-->
<div class="mdl-layout mdl-js-layout mdl-layout--fixed-header mdl-layout--fixed-tabs">
  <header class="mdl-layout__header">
      <div class="mdl-layout__header-row">
      <!-- Title -->
          <span class="mdl-layout-title"> <span class="mdl-badge" id="noti" data-badge="">Pending Repair</span> </span>
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

<main class="mdl-layout__content" id="repairPage"  style="background: #222232;">
      <div class="page-content" >
      <div id="start_point">
      </div>
      </div>   
  </main>  

</div> 
<!-- end project -->

<!-- The Modal -->
<div id="myModal" class="modal">
  <!-- The Close Button -->
  <span style="margin-top:60px" class="close_pic">&times;</span>
  <!-- Modal Content (The Image) -->
  <img class="modal-content" id="img01">
  <!-- Modal Caption (Image Text) -->
  <div id="caption"></div>
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

    <script>
         var repair_data = [];
          $.ajax({
            url: 'repair_db.php',
            dataType: "json",
            data: { "mode":"get_data" },
            type : "POST",
           // async: true, //blocks window close
            success: function(data) {
               // repair_data = data;
              create_element(data);


            },
            error: function (request, status, error) {
                    if(error){
                        alert(error);
                    }
                    console.log(error,status);
            }

        });

    </script>

  <script>
    $(document).ready(function(){


  });//end ready 

  </script>


<script>
  function create_element(repair_data) {


    let dropdown = $('#start_point');
    dropdown.empty();

    //666
    $("#noti").attr("data-badge",repair_data.length);

      $.each(repair_data, function (key) {

        if(repair_data[key]['DB_CHOOSE'] =1){
            ans= repair_data[key]['DB_SPEC1'];
          }else if(repair_data[key]['DB_CHOOSE'] =2){
            ans= repair_data[key]['DB_SPEC2'];
          }else if(repair_data[key]['DB_CHOOSE'] =3){
            ans= repair_data[key]['DB_SPEC3'];
          }

        pic2='';
        if(repair_data[key]['DB_PIC2'].trim() != ''){
            pic2 ='<div class="col-md-3 col-sm-3"><img class="found_pic" src="'+repair_data[key]['DB_PIC2']+'" alt="Smiley face" height="90" width="90"></div>';

        }else{
            pic2 ='<div class="col-md-3 col-sm-3"><img src="image_detail/noimage.png" alt="Smiley face" height="90" width="90"></div>';
        }

        pic3='';
        if(repair_data[key]['DB_PIC3'].trim() != ''){
            pic3= '<div class="col-md-3 col-sm-3"><img class="found_pic" src="'+repair_data[key]['DB_PIC3']+'"alt="Smiley face" height="90" width="90"></div>';
        }else{
            pic3 ='<div class="col-md-3 col-sm-3"><img src="image_detail/noimage.png" alt="Smiley face" height="90" width="90"></div>';
        }


        dropdown.append(
        '<div class="col-sm-12 removeTr" id="element'+ repair_data[key]['DB_DEFECT']+'" style="margin-top:20px">'+
          '<div class="col-md-12 col-sm-12 pl-0">'+
            '<div class="element-card">'+
              '<div class="front-facing">'+
                '<h1 class="abr">'+ repair_data[key]['RUNN'] + '</h1>'+  
                '<p class="title">Zone 1</p>'+     
                '<span class="atomic-number">คำถามที่</span>'+        
                '<span class="atomic-mass">' + repair_data[key]['DB_QUESTION']+'</span></div>'+  
                '<div class="back-facing" style="font-size:12px">'+  
                '<div class="row closebtn">'+
                  '<button type="button" class="close cancel" aria-label="Close" style="padding-top:0px">'+
                    '<span aria-hidden="true"><i class="far fa-times-circle"></i></span>'+
                  '</button>'+ 
                '</div>'+

                '<form class="col-sm-12" id="form-repair-'+repair_data[key]['RUNN'] + '"  >'+

                  '<div class="row my-2" >'+
                    '<div class="col-md-4 col-sm-4" style="text-align: left">'+
                      '<label for="exampleForm1"><dt>Question :</dt></label>'+ 
                    '</div>'+
                    '<div class="col-md-8 col-sm-8" style="text-align: left">'+
                      '<p id="exampleForm1" class="mb-2">' + repair_data[key]['DB_QUESTION']+'</p>'+
                    '</div>'+
                  '</div>'+

                  '<div class="row my-2">'+ 
                    '<div class="col-md-4 col-sm-4" style="text-align: left">'+
                      '<label for="exampleForm2"><dt>Problem :</dt></label>'+
                    '</div>'+
                    '<div class="col-md-8 col-sm-8" style="text-align: left">'+
                      '<p id="exampleForm2" class="mb-2">' +ans+'</p>'+
                    '</div>'+
                  '</div>'+ 

                  '<div class="row my-2">'+
                    '<div class="col-md-4 col-sm-4" style="text-align: left">'+
                      '<label for="fix-repair-'+repair_data[key]['RUNN'] + '" style="padding-top:5px"><dt>Fix :</dt></label>'+
                    '</div>'+
                    '<div class="col-md-8 col-sm-8" >'+
                      '<select class="form-control " id="fix-repair-'+repair_data[key]['RUNN'] + '">'+
                        '<option disabled selected>--</option>'+
                        '<option>ขันแน่น</option>'+
                        '<option>ซ่อมสี</option>'+
                        '<option>เปลี่ยนชิ้นส่วน</option>'+
                        '<option>ไล่ลมเบรค</option>'+
                      '</select>'+
                    '</div>'+
                  '</div>'+

                  '<div class="row my-4">'+
                    '<div class="col-md-3 col-sm-3">'+
                    '<img class="found_pic" src="'+repair_data[key]['DB_PIC1']+'" alt="Smiley face" height="90" width="90">'+
                    '</div>'+
                    pic2+pic3+
                    '<div class="col-md-3 col-sm-3 align-self-end" >'+
                      '<button type="button" id="'+ repair_data[key]['DB_DEFECT']+'" class="btn btn-primary save" >Save</button>'+
                    '</div>'+
                  '</div>'+
                '</form>'+

              '</div>'+
            '</div>'+
          '</div>'+
        '</div>'
        // '</div>'
          );

      });



  $('.element-card').on('click', function(){
  
  if ( $(this).hasClass('close') ) {
    // console.log('1');

    $(this).removeClass('open');
    $(this).removeClass('close');
    // $(this).css("width", "300px");
    // $(this).css("height", "400px");
  } else{

  if ( !$(this).hasClass('open') ) {
    // console.log('2');

    $(this).addClass('open');

  } 

}

});



$('.cancel').on('click', function(){
      console.log(888);
    var $v = $(this).closest(".element-card").addClass('close');
    console.log($v);
    //console.log($(this));
    // $(v).removeClass('open');
  
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

//555

$('.save').on('click', function(){
  
  var v =this.form.id;
  var w =this.id;
  var id = v.substring(12,14);
console.log(v);

  var fix_reason = $('#fix-repair-'+id).val();
  console.log(fix_reason);
  console.log(w);

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
          // async: false, //blocks window close
          success: function() {
            var noti = $("#noti").attr("data-badge");
            $('#element'+w).remove();
            $("#noti").attr("data-badge", noti-1);
              // location.reload();

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



  }//end function

</script>

  <script>
    //Event click 
    $(document).on('click','input:checkbox',function (e){
      var $box = $(this);
      var $boxID = $box.attr('id');
      var res = $boxID.substring(6);

      if ($box.is(":checked")) {
        var group = "input:checkbox[name='" + $box.attr("name") + "']";
        $(group).prop("checked", false);
        $box.prop("checked", true);
        $(group).parent('label').removeClass("active");
        $box.parent('label').addClass("active");
        
        if($box.attr("cor-value") == "true"){
          console.log("true");
          $('#detail'+res).prop( "disabled", true );
          $('#inputfile'+res).prop( "disabled", true );
          $('#badge'+(res)).attr('data-badge', 0);
        }else{
          console.log("false");
          $('#detail'+res).prop( "disabled", false );
          $('#inputfile'+res).prop( "disabled", false );
        }
        
      }else {
        $box.prop("checked", false);
        $box.parent('label').removeClass("active");
        $('#detail'+res).prop( "disabled", true );
        $('#inputfile'+res).prop( "disabled", true );
        $('#badge'+(res)).attr('data-badge', 0);
        console.log("remove");
      }

      });//end Event 
  </script>

</body>

</html>
