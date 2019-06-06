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
  <title>Demo QC Inspection By Tablet Project</title>
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

</head>

<body>
<!-- Start your project here-->
<div class="mdl-layout mdl-js-layout mdl-layout--fixed-header mdl-layout--fixed-tabs">
  <header class="mdl-layout__header">
      <div class="mdl-layout__header-row">
      <!-- Title -->
          <span class="mdl-layout-title">QC Confirm</span>
          <div class="mdl-layout-spacer"></div>
          <button class="mdl-button mdl-js-button mdl-js-ripple-effect text-white" id="save_btn">
            save
          </button>
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
          
      </div>   
  </main>  

  <!-- <button class="btn btn-primary btn-modal"data-toggle="modal" data-target=".edit-photo">View Fullscreen Modal</button>
  <button class="btn btn-primary btn-modal"data-toggle="modal" data-target="#viewImage">preview</button> -->


</div> 

<div class="modal fade edit-photo" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md">
    <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title mt-0" id="exampleModalCenterTitle">Edit Image</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <canvas id="canvasDiv"></canvas>
          <div class="top-bar justify-content-center">
            <button id="undo-btn" type="button" class="btn btn-sm btn-primary btn-rounded waves-effect"><i class="fas fa-undo-alt"></i> Undo</button>
            <button id="clear-btn" type="button" class="btn btn-sm btn-primary btn-rounded waves-effect"><i class="far fa-times-circle"></i> Clear</button>
            <input type="color" id="color-picker" value="#ff0000" style="align-self:center;">
          </div>
          <div>
            <label for="brush-size">Brush size</label>
            <input type="range" class="custom-range" id="brush-size" min="1" max="50" value="7">
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-outline-primary" data-dismiss="modal">Close</button>
          <button type="button" class="btn btn-primary" id="saveEdit">Save changes</button>
        </div>
      </div>
    </div>
  </div>  

  <!-- Central Modal Medium Info -->
  <div class="modal fade" id="viewImage" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-notify modal-info" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <p class="heading lead">Image</p>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true" class="white-text">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="col-sm-12 align-self-center">
            <div class="row" id="rowImage">
            
            </div> <!--end row-->
          </div> <!--end col-8-->
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-outline-primary" data-dismiss="modal">Close</button>
            <!-- <button type="button" class="btn btn-primary">Save changes</button> -->
        </div>
      </div>
    </div>
  </div>

    <!-- Central Modal Medium Info -->
    <div class="modal fade" id="detailModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title mt-0" id="titleModal" >Problem Detail</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div style="background-color: #f5f5f5;">
          <div class="modal-body mx-1 py-1">
            <div class="row">
              <div class="col-sm-6">
                <div class="mb-3">
                  <label data-error="wrong" data-success="right" for="problemForm-prom" class="font-weight-bold">Problem</label>
                  <input type="text" id="problemForm-prom" class="form-control" disabled>
                </div>
                <div class="mb-3">
                  <label data-error="wrong" data-success="right" for="problemForm-zone" class="font-weight-bold">Zone</label>
                  <input type="text" id="problemForm-zone" class="form-control" disabled>
                </div>
              </div> <!--end col-4-->
              <div class="col-sm-12">
                <p class="font-small grey-text mb-0" id="infoProm">XXXXXXX</p>
              </div>
            </div> <!--end row-->
          </div> <!--end mode-body-->
        </div>
          <!-- new line -->
          <hr class="my-0">
          <div style="background-color: #f5f5f5;">
            <div class="modal-body mx-1 py-1">
            <div class="row">
              <div class="col-sm-6">
                <div class="mb-2">
                  <!-- <i class="fas fa-wrench prefix indigo-text"></i> -->
                  <label data-error="wrong" data-success="right" for="problemForm-prom" class="font-weight-bold">Repair Man Note</label>
                  <input type="text" id="problemForm-repair" class="form-control" disabled>
                </div>
              </div> <!--end col-4-->
              <div class="col-sm-12">
                <p class="font-small grey-text mb-0" id="infoRepair">XXXXXX</p>
              </div>
            </div> <!--end row-->
            </div> <!--end mode-body-->
          </div>
          
          <div class="row" id="rowPreview">
              <div class="col-sm-4 mt-1 p-0 text-center">
                <div class="row align-items-center">
                  <label for="part-img-4" class="col-sm-12">
                    <div class="col-sm-12 p-0">
                      <div class="ng-image-background ng-image-background-4 mx-auto mb-0" data-index="4">
                          <img id="preview4" class="image-thumbnail img-thumbnail mg-0 bg-transparent border-0 viewPhoto" data-index="4" src="">
                      </div>
                    </div>
                  </label>
                </div>
              </div>
              <div class="col-sm-4 mt-1 p-0 text-center">
                  <div class="row align-items-center">
                    <label for="part-img-5" class="col-sm-12">
                      <div class="col-sm-12 p-0">
                        <div class="ng-image-background ng-image-background-4 mx-auto mb-0" data-index="5">
                            <img id="preview5" class="image-thumbnail img-thumbnail mg-0 bg-transparent border-0 viewPhoto" data-index="5" src="">
                        </div>
                      </div>
                    </label>
                  </div>
                </div>
                <div class="col-sm-4 mt-1 p-0 text-center">
                    <div class="row align-items-center">
                      <label for="part-img-6" class="col-sm-12">
                        <div class="col-sm-12 p-0">
                          <div class="ng-image-background ng-image-background-4 mx-auto mb-0" data-index="6">
                              <img id="preview6" class="image-thumbnail img-thumbnail mg-0 bg-transparent border-0 viewPhoto" data-index="6" src="">
                          </div>
                        </div>
                      </label>
                    </div>
                  </div>
          </div> <!--end row-->

          <div class="modal-footer">
              <button type="button" class="btn btn-outline-primary" data-dismiss="modal">Close</button>
              <!-- <button type="button" class="btn btn-primary" id="saveProm" >Save changes</button> -->
          </div>
        </div>
      </div>
    </div>

    <div id="myModal" class="modal fade viewImageProm" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog" >
          <div class="modal-content" >
              <div class="modal-body" >
                  <img src="" id="imagepreview" class="img-fluid" >
              </div>
          </div>
        </div>
      </div>
  <!-- /Start your project here-->

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
  <script src="./js/material.min.js"></script>

  <script>
      var repair_data = [];
       $.ajax({
         url: 'qc_db.php',
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

      $('.edit-photo').modal({
            keyboard: true,
            backdrop: "static",
            show:false,
        }).on('show.bs.modal', function(){
            // $("body").addClass("modal-open");

        });

        $('.edit-photo').on('hidden.bs.modal', function (e) {
            strokes = [];
            redraw();
            console.log("close");
            
        });

        $('#detailModal').modal({
            keyboard: true,
            backdrop: "static",
            show:false,
        }).on('show.bs.modal', function(){
 
        });

        $('#detailModal').on('hidden.bs.modal', function (e) {
            console.log("close");
            var preview4 = $('#preview4');  
            var preview5 = $('#preview5');  
            var preview6 = $('#preview6'); 
            preview4.attr('src', '');
            preview5.attr('src', '');    
            preview6.attr('src', '');
            $('#rowImage').empty();
        });
        
        $('#saveProm').on('click', function(event) {
            let id = $(this).val();
            saveProm(id);
        });

        $('#viewImage').on('hidden.bs.modal', function (e) {
          var preview1 = $('#preview1');  
          var preview2 = $('#preview2');  
          var preview3 = $('#preview3'); 
          preview1.attr('src', '');
          preview2.attr('src', '');    
          preview3.attr('src', '');
          $('#rowImage').empty();
        });

        $(document).on('click ','.viewPhoto',function (e){

          var imgsrc=$(this).attr('src'); 
          $('#imagepreview').attr('src', imgsrc); // here asign the image to the modal when the user click the enlarge link

          $('.viewImageProm').on('show.bs.modal', centerModal);
          // $(window).on("resize", function () {
          //     $('.modal:visible').each(centerModal);
          // });
          $('#myModal').modal('show');

        });

        function centerModal() {
            $(this).css('display', 'block');
            var $dialog = $(this).find(".modal-dialog");
            // var offset = ($(window).height() - $dialog.height()) / 2;
            var offset = ($(window).height() - 400) / 2;
            // Center modal vertically in window
            $dialog.css("margin-top", offset);
        }

    });

    function previewImage(index) {
      var cardId = index;
      console.log(cardId);
      $('#viewImage').attr('data-index', cardId);
      if ($('#badge'+index).attr("data-badge") > "0") {
        console.log("grgrwfw");
        
        recordImage(cardId);
      }
    }

    function detail(index) {
      var mid = index;
      console.log(mid);
      $('#detailModal').attr('data-index', (mid));
      $('#saveProm').val(mid);
      recordProm(mid);
      $('#detailModal').modal();
      
    }

    function recordImage(id) {

      let card_id = id;
      $.ajax({
                url: 'qc_db.php',
                data:{
                    "mode":"get_detail",
                    'lid': card_id,
                },
                type : "POST",
                dataType : "json",
                success: function(data) {

                    if(data[0].ERROR == 'success'){
                      var dt = new Date();
                        console.log('success');
                        if((data[0].PIC1).trim().length > 0){
                          appendImage(1);
                          var preview = $('#preview1');  
                          preview.attr('src', data[0].PIC1+"?"+dt);
                          
                        }
                        if((data[0].PIC2).trim().length > 0){
                          appendImage(2);
                          var preview = $('#preview2');  
                          preview.attr('src', data[0].PIC2+"?"+dt);
                        }
                        if((data[0].PIC3).trim().length > 0){
                          appendImage(3);
                          var preview = $('#preview3');  
                          preview.attr('src', data[0].PIC3+"?"+dt);
                        }
                        $('#viewImage').modal();           
                        
                    }
                        
                },
                error: function (request, status, error) {
                    console.log(error,status);
                }
        });//end ajax 
    }

    function appendImage(id) {
      let pid = id;
      $("#rowImage").append(
        '<div class="col-sm-4 mt-5 p-0 text-center" id="preId'+pid+'">'+
          '<div class="row align-items-center">'+
            '<label for="part-img-'+pid+'" class="col-sm-12">'+
              '<div class="col-sm-12 p-0">'+
                '<div class="ng-image-background ng-image-background-'+pid+' mx-auto mb-0" data-index="'+pid+'">'+
                    '<img id="preview'+pid+'" class="image-thumbnail img-thumbnail mg-0 bg-transparent border-0 imgedit" data-index="'+pid+'" src="">'+
                '</div>'+
              '</div>'+
            '</label>'+
          '</div>'+
          '<label class="ng-clear-img" data-index="'+pid+'"><h4 class="m-0"><i class="fa fa-trash" aria-hidden="true"></i></h4><span></span></label>'+
        '</div>'
      );
    }


    
    function uploadImage(input, index) {
      if (input.files && input.files[0]) {
        console.log("true");
        var reader = new FileReader();
        reader.readAsDataURL(input.files[0]);
        saveImage(index);
      }else{
        console.log("false");
      }

    }

    function saveImage(rowID) {
    let id = rowID;
    console.log(id);
    
    // declare valiable
    var ngImage =[];
    var formData = new FormData();
    
   
      if($('#inputfile'+id).prop('files')[0] != null){
          ngImage.push($('#inputfile'+id).prop('files')[0]);
      }
    

    formData.append('ngImage1',ngImage[0]);
    formData.append('mode','save_image');
    formData.append('row_id', id);
    
    $.ajax({
              url: 'qc_db.php',
              data: formData,
              type : "POST",
              dataType : "json",
              processData: false, // Don't process the files
              contentType: false, // Set content type to false as jQuery will tell the server its a query string request
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
              success: function(data) {
                  //console.log(data);
                  if(data[0].ERROR == 'success'){
                      let count_pic = data[0].COUNT_PIC ;                    
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

                        $('#badge'+(id)).attr('data-badge', count_pic);

                          });  
                  }

                  if(data[0].ERROR == 'fullpic'){
                    swal({
                          heightAuto: false,
                          title: 'พบข้อผิดพลาด!',
                          text: 'รูปภาพอัพโหลดเต็ม 3 รูปแล้ว',
                          type: 'warning',
                          confirmButtonText: 'ตกลง'
                      });
                  }
                      
              },
              error: function (request, status, error) {
                  console.log(error,status);
              }
          });//end ajax
  }

  function saveProm(rowID) {
    let id = rowID.trim();
    var formData = new FormData();
    let in_prom = $('#form-problem option:selected').val();
    let in_zone = $('#form-zone option:selected').val();
    let in_inc = $('#form-incharge option:selected').val();
    in_prom = in_prom.trim();
    in_zone = in_zone.trim();
    in_inc = in_inc.trim();
      // console.log(scanner.length);
    
    if (in_prom.length == 0){
        swal({
            heightAuto: false,
            title: 'Warning',
            text: 'กรุณาเลือก Problem',
            type: 'warning',
            confirmButtonText: 'ตกลง'
        });
        return -1;
    }

    if (in_zone.length == 0){
        swal({
            heightAuto: false,
            title: 'Warning',
            text: 'กรุณาเลือก Zone',
            type: 'warning',
            confirmButtonText: 'ตกลง'
        });
        return -1;
    }

    if (in_inc.length == 0){
        swal({
            heightAuto: false,
            title: 'Warning',
            text: 'กรุณาเลือก Incharge',
            type: 'warning',
            confirmButtonText: 'ตกลง'
        });
        return -1;
    }

    formData.append('mode','save_prom');
    formData.append('problem', in_prom);
    formData.append('zone', in_zone);
    formData.append('inc', in_inc);
    formData.append('row_id', id);
    
    console.log(in_prom);
    console.log(in_zone);
    console.log(in_inc);

    $.ajax({
              url: 'inspector_db.php',
              data: formData,
              type : "POST",
              dataType : "json",
              processData: false, // Don't process the files
              contentType: false, // Set content type to false as jQuery will tell the server its a query string request
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
              success: function(data) {
                  //console.log(data);
                  if(data[0].ERROR == 'success'){
                      
                      let id = data[0].id; 
                      console.log('success');
                      
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
                          $('#detail'+(id-1)).append("<i class='pl-2 fas fa-paperclip' style='color:#ffffff'></i>");
                          $("#detailModal").modal("hide");

                          });
                        
                  }
                      
              },
              error: function (request, status, error) {
                  console.log(error,status);
              }
          });//end ajax
  }

  function recordProm(id) {
  // previewImage('',1);
  // previewImage('',2); 
  // previewImage('',3); 
  let list_id = id;
      $.ajax({
            url: 'qc_db.php',
            data:{
                  "mode":"get_data2",
                  'lid': list_id,
            },
            type : "POST",
            dataType : "json",
            success: function(data) {

                  if(data[0].ERROR == 'success'){

                    let date = moment(data[0].DATE,"YYYY-MM-DD").format("DD-MM-YYYY");
                    $("#infoProm").text('Report by : '+ data[0].WORKER + ' Date : ' + date + ' Time : ' + data[0].TIME );
                    $("#infoRepair").text('Report by : '+ data[0].WORKER  + ' Date : ' + date + ' Time : ' + data[0].TIME );


                    // console.log('success');
                    $("#problemForm-prom").val(data[0].PROBLEM);
                    $("#problemForm-zone").val(data[0].ZONE);
                    $("#problemForm-repair").val(data[0].REPAIR);
                    $("#cameraModalS").find( "i" ).addClass('active');
                    $("#cameraModalS").find("label").addClass('active');

                    var dt = new Date();
                    $('#rowPreview').show(); 

                    if((data[0].PIC1).trim().length > 0){
                      var preview = $('#preview4');  
                      preview.attr('src', data[0].PIC1+"?"+dt);
                    }else{        
                      $('#rowPreview').hide();              
                    }
                    if((data[0].PIC2).trim().length > 0){
                      var preview = $('#preview5');  
                      preview.attr('src', data[0].PIC2+"?"+dt);
                    }
                    if((data[0].PIC3).trim().length > 0){
                      var preview = $('#preview6');  
                      preview.attr('src', data[0].PIC3+"?"+dt);
                    }

                  }else{
                    console.log("not found");  
                    $('#rowPreview').hide(); 
                  }
                // $('#cameraModalS').modal();           
                    
            },
            error: function (request, status, error) {
                console.log(error,status);
            }
        });//end ajax 
  }

    $(document).on('click ','.imgedit',function (e){

      // var dt = new Date();
      var imgsrc = $(this).attr('src'); 
      var imgid = $(this).parents().eq(10).attr('data-index'); 
      console.log(imgid);
      
      outlineImage.src = imgsrc;
      outlineImage.onload = function(){
          // drawingAreaWidth = outlineImage.naturalWidth;
          // drawingAreaHeight = outlineImage.naturalHeight;
          ctx.drawImage(outlineImage, drawingAreaX, drawingAreaY, drawingAreaWidth, drawingAreaHeight);  
      }
      $('#saveEdit').val(imgsrc); 
      $('.edit-photo').attr('data-index', imgid);

      $('.edit-photo').modal();

      });

      //save whole page
      $('#save_btn').on('click', function(event) {
        event.preventDefault(); // To prevent following the link (optional) 
        if ($('.page-content').children().length == 0) {
          return false;
        }

        for (let i = 1; i <= 10 ; i++) {
          
          let approve = $("#app"+i).val();
          console.log(approve);
          if (approve != undefined) {
            if (approve != 'ON'){
                swal({
                    heightAuto: false,
                    title: 'Warning',
                    text: 'กรุณา approve สถานะการซ่อม',
                    type: 'warning',
                    confirmButtonText: 'ตกลง'
                });
                return -1;
            }
          }

        }

        var ans_loop = true;
        for(let x = 1; x <= 10 ; x++){  
            var takePhoto = $('#badge'+x).attr('data-badge');
          
              if(takePhoto == 0){
                swal({
                      heightAuto: false,
                      title: 'พบข้อผิดพลาด!',
                      text: 'กรุณาถ่ายรูปอย่างน้อย 1 รูป',
                      type: 'warning',
                      confirmButtonText: 'ตกลง'
                  });
                ans_loop = false; 
                return false;
                
              }else{
                console.log("photo ok");
              }

        } 

        if(ans_loop == true){

          Swal({
              title: 'คุณต้องการบันทึกข้อมูลจริงหรือไม่?',
              text: "กดตกลงเพื่อทำการบันทึกข้อมูล!",
              type: 'info',
              heightAuto : false,
              showCancelButton: true,
              confirmButtonColor: '#3085d6',
              cancelButtonColor: '#d33',
              confirmButtonText: 'ตกลง',
              cancelButtonText: 'ยกเลิก',
          }).then((result) => {

              if (result.value) {
             
                var rowData =[];
                var formRow = new FormData();

                  $('.page-content > #ispCard').each(function() {
                    
                    let id = $(this).attr('data-index');
                    let approves = $("input.cb-value").val();
                    
                    rowData.push({'id':id,'app':approves});
                  });
                
// console.log(rowData);
                
                formRow.append('rowData', JSON.stringify(rowData));
                formRow.append('mode','save_data');


                $.ajax({
                    url: 'qc_db.php',
                    data: formRow,
                    type : "POST",
                    dataType : "json",
                    processData: false, // Don't process the files
                    contentType: false, // Set content type to false as jQuery will tell the server its a query string request
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
                    success: function(data) {
                        //console.log(data);
                        if(data[0].ERROR == 'success'){
                            
                            let id = data[0].id; 
                            console.log('success');
                            
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
                                
                                location.reload();

                                });
                              
                        }
                            
                    },
                    error: function (request, status, error) {
                        console.log(error,status);
                    }
                });//end ajax
              
              }
          })

        }else{
          console.log("book");
        }


      });

      
  </script>

  <script>
  function create_element(qc_data) {

    $.each(qc_data, function(i, value){
        $(".page-content").append(
        '<div class="demo-card-wide mdl-card" id="ispCard" data-index="'+value.RUNN+'">'+
            '<div class="mdl-card__title">'+
              '<h5>'+
                '<div class="mdl-card__subtitle-text">Q. '+(value.RUNN)+' '+value.DB_QUESTION+'</div>'+
                '<div class="mdl-card__title-text pt-1">Problem : '+value.DB_PROM+'</div>'+   
                '<div class="mdl-card__title-text pt-1">Zone    : '+value.DB_ZONE+'</div>'+   
              '</h5>'+
            '</div>'+
            '<div class="mdl-card__supporting-text p-0">'+      
            '</div>'+
            '<div class="mdl-card__actions mdl-card--border">'+
                '<div class="row mx-0" id="choice'+value.RUNN+'">'+
                '</div>'+
            '</div>'+
            '<div class="mdl-card__menu">'+
                '<div class="menuIcon">'+
                    '<label for="inputfile'+value.RUNN+'">'+
                      '<div class="material-icons">photo_camera</div>'+
                    '</label>'+   
                    '<input disabled type="file" accept="image/*" id="inputfile'+value.RUNN+'" onchange="uploadImage(this, '+value.RUNN+')" capture="camera" class="inputfile">'+ 
                    '<div class="material-icons mdl-badge mdl-badge--overlap" data-badge="0" id="badge'+value.RUNN+'" onclick="previewImage('+value.RUNN+')">image</div>'+
                '</div>'+
                '<div class="center-align">'+
                    '<button class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-button--accent" id="detail'+value.RUNN+'" onclick="detail('+value.RUNN+')">Detail</button>'+
                '</div>'+
            '</div>'+
        '</div>'
        );

            $("#choice"+value.RUNN).append(
              '<div class="col-sm-4 pl-2">'+
                '<div class="mb-0">'+
                  '<p class="mdl-card__subtitle-text mb-1">Approve fix status</p>'+
                  '<div class="toggle-btn" data-index="'+value.RUNN+'">'+
                    '<input type="checkbox" value="OFF" id="app'+value.RUNN+'" class="cb-value"/>'+
                    '<span class="round-btn"></span>'+
                  '</div>'+
                '</div>'+
              '</div>'
            )
      
                            
    });

    }
  </script>

  <script>

      function clearTempImage(index) {
        var rid = parseInt(index)+1;
        $.ajax({
              url: 'inspector_db.php',
              data:{
                    "mode":"clear_tempimg",
                    'lid': rid,
                },
              type : "POST",
              dataType : "json",
              success: function(data) {

                  if(data[0].ERROR == 'success'){
                      console.log("success");     
                  }else{
                      console.log("not found"); 
                  }
                      
              },
              error: function (request, status, error) {
                  console.log(error,status);
              }
          });//end ajax
      }

      $(document).on('click','.cb-value',function (e){
        var mainParent = $(this).parent('.toggle-btn');
        var res = $(this).parent('.toggle-btn').attr('data-index');
        if($(mainParent).find('input.cb-value').is(':checked')) {
          $(mainParent).addClass('active');
          $(mainParent).find('input.cb-value').val('ON');
          $('#inputfile'+res).prop( "disabled", false );
        } else {
          $(mainParent).removeClass('active');
          $(mainParent).find('input.cb-value').val('OFF');
          $('#inputfile'+res).prop( "disabled", true );
        }
      });

      $(document).on('click','.ng-clear-img',function (e){
          // var modalId = $(this).find('#viewImage').attr('data-index');
          var modalId = $(this).parents().eq(6).attr('data-index');
          var clearIndex = $(this).attr('data-index');

          $.ajax({
              url: 'inspector_db.php',
              data:{
                    "mode":"clear_image",
                    'lid': modalId,
                    'picId': clearIndex,
                },
              type : "POST",
              dataType : "json",
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
              success: function(data) {
                  //console.log(data);
                  if(data[0].ERROR == 'success'){
                      
                      let count_pic = 0;
                     
                      
                      if (data[0].COUNT_PIC != 0) {
                        count_pic = data[0].COUNT_PIC;
                      }                  
                      swal({
                          position: 'center',
                          heightAuto: false,
                          type: 'success',
                          title: 'ลบรูปภาพสำเร็จ',
                          showConfirmButton: false,
                          timer: 1000,
                          onBeforeOpen: () => {
                            Swal.hideLoading()
                          }
                      }).then((result) => {
                        console.log(count_pic);
                        // $('#preview'+clearIndex).attr('src', '');; 
                        // $('#rowImage').children().remove("#preId"+clearIndex);
                        $('#rowImage').empty();
                        $('#badge'+(modalId-1)).attr('data-badge', count_pic);

                        $.ajax({
                        url: 'inspector_db.php',
                        data:{
                            "mode":"get_detail",
                            'lid': modalId,
                        },
                        type : "POST",
                        dataType : "json",
                        success: function(data) {

                            if(data[0].ERROR == 'success'){

                                console.log('success');
                                var dt = new Date();

                                if((data[0].PIC1).trim().length > 0){  
                                  appendImage(1);                         
                                  var preview = $('#preview1');  
                                  preview.attr('src', data[0].PIC1+"?"+dt);
                                }
                                if((data[0].PIC2).trim().length > 0){
                                  appendImage(2);
                                  var preview = $('#preview2');  
                                  preview.attr('src', data[0].PIC2+"?"+dt);
                                }
                                if((data[0].PIC3).trim().length > 0){
                                  appendImage(3);
                                  var preview = $('#preview3');  
                                  preview.attr('src', data[0].PIC3+"?"+dt);
                                }
                                          
                            }
                                
                        },
                        error: function (request, status, error) {
                            console.log(error,status);
                        }
                });//end ajax 
                        
                          });  
                  }
                      
              },
              error: function (request, status, error) {
                  console.log(error,status);
              }
          });//end ajax
 
      });

  </script>

  <script>
      var canvas, ctx,
      brush = {
          x: 0,
          y: 0,
          color: '#ff0000',
          size: 10,
          
          down: false,
      },
      strokes = [],
      currentStroke = null;
      var outlineImage = new Image();
      var drawingAreaX = 0;
      var drawingAreaY = 0;
      var drawingAreaWidth = 472;
      var drawingAreaHeight = 330;
      var md = $('.modal-dialog.modal-md');
      var bd = $('.modal-dialog.modal-md .modal-body');
      var doc = document.documentElement;

      function redraw () {       
          ctx.clearRect(0, 0, canvas.width(), canvas.height());        
          ctx.drawImage(outlineImage, drawingAreaX, drawingAreaY, drawingAreaWidth, drawingAreaHeight);
          ctx.lineCap = 'round';
          ctx.lineJoin = "round";
          for (var i = 0; i < strokes.length; i++) {
              var s = strokes[i];        
              ctx.strokeStyle = s.color;
              ctx.lineWidth = s.size;
              ctx.beginPath();
              ctx.moveTo(s.points[0].x, s.points[0].y);
              for (var j = 0; j < s.points.length; j++) {
                  var p = s.points[j];
                  ctx.lineTo(p.x, p.y);
              }
              ctx.stroke();
          }

      }

      function init () {
          canvas = $('#canvasDiv');
          
          canvas.attr({
              // width: window.innerWidth,
              // height: window.innerHeight,
              width: 472,
              height: 330,
          });
          ctx = canvas[0].getContext('2d');
          // outlineImage.src = "temp_img/temp_pic_id9_1.jpg";
          // outlineImage.onload = function(){
          //     ctx.drawImage(outlineImage, drawingAreaX, drawingAreaY, drawingAreaWidth, drawingAreaHeight);  
          // }

          function mouseEvent (e,xx,yy) {
              brush.x = e.pageX - xx;
              brush.y = e.pageY - yy - (doc.scrollTop || 0);
        
              currentStroke.points.push({
                  x: brush.x,
                  y: brush.y,
              });

              redraw();
          }

      canvas.mousedown(function (e) {

          var xx = md[0].offsetLeft + this.offsetLeft;
          var yy = md[0].offsetTop + this.offsetTop + bd[0].offsetTop;  
          
          brush.down = true;

          currentStroke = {
              color: brush.color,
              size: brush.size,
              points: [],
          };

          strokes.push(currentStroke);

          mouseEvent(e,xx,yy);
      }).mouseup(function (e) {
          brush.down = false;
          xx = md[0].offsetLeft + this.offsetLeft;
          yy = md[0].offsetTop  + this.offsetTop + bd[0].offsetTop;
          mouseEvent(e,xx,yy);

          currentStroke = null;
      }).mousemove(function (e) {
          xx = md[0].offsetLeft + this.offsetLeft;
          yy = md[0].offsetTop  + this.offsetTop + bd[0].offsetTop;
          if (brush.down)
          
          mouseEvent(e,xx,yy);
      });

      // Add touch event listeners to canvas element
      canvas[0].addEventListener("touchstart", function(e){       
          // Mouse down location
          var mouseX = (e.changedTouches ? e.changedTouches[0].pageX : e.pageX) - (md[0].offsetLeft + this.offsetLeft),
              mouseY = (e.changedTouches ? e.changedTouches[0].pageY : e.pageY) - (md[0].offsetTop  + this.offsetTop + bd[0].offsetTop);
          // var xx = this.offsetLeft;
          // var yy = this.offsetTop;
          brush.down = true;

          currentStroke = {
              color: brush.color,
              size: brush.size,
              points: [],
          };

          strokes.push(currentStroke);

          // mouseEvent(e,xx,yy);

          brush.x = mouseX;
          brush.y = mouseY - (doc.scrollTop || 0);

          currentStroke.points.push({
              x: brush.x,
              y: brush.y,
          });

          redraw();
      }, false);
      canvas[0].addEventListener("touchmove", function(e){
          var mouseX = (e.changedTouches ? e.changedTouches[0].pageX : e.pageX) - (md[0].offsetLeft + this.offsetLeft),
              mouseY = (e.changedTouches ? e.changedTouches[0].pageY : e.pageY) - (md[0].offsetTop  + this.offsetTop + bd[0].offsetTop);
          // xx = this.offsetLeft;
          // yy = this.offsetTop;
          if (brush.down)
          
          // mouseEvent(e,xx,yy);

          brush.x = mouseX;
          brush.y = mouseY - (doc.scrollTop || 0);

          currentStroke.points.push({
              x: brush.x,
              y: brush.y,
          });

          redraw();
      }, false);
      canvas[0].addEventListener("touchend", function(e){
    
          brush.down = false;
          // xx = this.offsetLeft;
          // yy = this.offsetTop;
          // mouseEvent(e,xx,yy);

          currentStroke = null;
      }, false);
      canvas[0].addEventListener("touchcancel", function(e){
          brush.down = false;
          // currentStroke = null;
      }, false);

      $('#undo-btn').click(function () {
          strokes.pop();
          redraw();
      });

      $('#clear-btn').click(function () {
          strokes = [];
          redraw();
      });

      $('#color-picker').on('input', function () {
          brush.color = this.value;
      });

      $('#brush-size').on('input', function () {
          brush.size = this.value;
      });

      function subLocation(str) {
          return str.substring(0,str.indexOf("?"));
      }

      $('#saveEdit').mousedown(function(e){
          var img = canvas[0].toDataURL();
          var imglocation = $(this).val();    
          var sublocation = subLocation(imglocation);
          var dataindex = $(this).parents().eq(3).attr('data-index');      
          debugBase64(img,sublocation,dataindex);
          
      });

  }

  $(init);

  </script>

  <script>
  function debugBase64(base64URL, nameLocation, modalIndex){
    // var win = window.open();
    // win.document.write('<iframe src="' + base64URL  + '" frameborder="0" style="border:0; top:0px; left:0px; bottom:0px; right:0px; width:100%; height:100%;" allowfullscreen></iframe>');
    $.ajax({
      url: 'qc_db.php',
      data: {
          "mode": "saveEdit_image",
          "img": base64URL,
          "name": nameLocation,
      },
      type: "POST",
      dataType: "json",
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
      success:function (result) {

          if (result[0].ERROR  == 'success' ) {

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

                    var dt = new Date();
                    $('#rowImage').empty();
                    // var preview = $('#'+previewId);  

                    $.ajax({
                        url: 'qc_db.php',
                        data:{
                            "mode":"get_detail",
                            'lid': modalIndex,
                        },
                        type : "POST",
                        dataType : "json",
                        success: function(data) {

                            if(data[0].ERROR == 'success'){

                                console.log('success');
                                var dt = new Date();

                                if((data[0].PIC1).trim().length > 0){  
                                  appendImage(1);                         
                                  var preview = $('#preview1');  
                                  preview.attr('src', data[0].PIC1+"?"+dt);
                                }
                                if((data[0].PIC2).trim().length > 0){
                                  appendImage(2);
                                  var preview = $('#preview2');  
                                  preview.attr('src', data[0].PIC2+"?"+dt);
                                }
                                if((data[0].PIC3).trim().length > 0){
                                  appendImage(3);
                                  var preview = $('#preview3');  
                                  preview.attr('src', data[0].PIC3+"?"+dt);
                                }
                                          
                            }
                                
                        },
                        error: function (request, status, error) {
                            console.log(error,status);
                        }
                });//end ajax 
          
                  
                  // $('.edit-photo').modal("hide"); 
                });                    
          }
          
      },
      error: function (request, status, error) {
                  console.log(error,status);
      }
    });
  }
  </script>
  
</body>

</html>
