<?php
    
    session_start();

    $conn = mysqli_connect('localhost', 'root', '', 'spec_demo');
        if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
        }
    // mysqli_query($conn, "SET NAMES tis620");
    mysqli_query($conn, "SET NAMES utf8");

    date_default_timezone_set("Asia/Bangkok");

    $json_data = array();

/**********************************************************************************************/
/* mode = problem_list                                                                        */
/**********************************************************************************************/
if($_POST["mode"] == "problem_list"){
    // load role list from master
    $sql =	"	SELECT 	trim(problem) as D1PBM
                FROM	master_problem
                ORDER BY problem 
    ";

    $sqlstm = '';
    $sqlstm = mysqli_query($conn, $sql);

    while($row = mysqli_fetch_assoc($sqlstm)){

    $json_data[] = $row;

    }
}
/**********************************************************************************************/
/* mode = zone_list                                                                           */
/**********************************************************************************************/
if($_POST["mode"] == "zone_list"){
    // load role list from master
    $sql =	"	SELECT 	trim(zone) as D1ZON
                FROM	master_zone
                ORDER BY zone 
    ";

    $sqlstm = '';
    $sqlstm = mysqli_query($conn, $sql);

    while($row = mysqli_fetch_assoc($sqlstm)){

    $json_data[] = $row;

    }
}

/**********************************************************************************************/
/* mode = incharge_list                                                                           */
/**********************************************************************************************/
if($_POST["mode"] == "incharge_list"){
    // load role list from master
    $sql =	"	SELECT 	trim(incharge) as D1INC
                FROM	master_incharge
                ORDER BY incharge
    ";

    $sqlstm = '';
    $sqlstm = mysqli_query($conn, $sql);

    while($row = mysqli_fetch_assoc($sqlstm)){

    $json_data[] = $row;

    }
}

/**********************************************************************************************/
/* mode = clear_tempimg                                                                           */
/**********************************************************************************************/
if($_POST["mode"] == "clear_tempimg"){

    $rid = mysqli_real_escape_string($conn, $_POST['lid']);

    $sql = "    SELECT  *
                FROM    temp_problem
                WHERE   list_id = '$rid'
    ";
    $sqlstm = '';
    $sqlstm = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($sqlstm);
    if($row){

        $sql2 =	"   DELETE	
                    FROM    temp_problem
                    WHERE	list_id = '$rid'
        ";

        $sqlstm2 = '';
        if($sqlstm2 = mysqli_prepare($conn, $sql2)){
            
            if(mysqli_stmt_execute($sqlstm2)){

                foreach (glob('temp_img/temp_pic_id'.$rid.'_'.'*.jpg') as $filename ) {
                    if( file_exists($filename) ){
                        //delete Excel file
                        unlink($filename);
                        }
                }

                $json_data[] = array(  
                    'ERROR' 		=> 'success',
                );



            } else{
                echo "ERROR: Could not execute query: $sql2. " . mysqli_error($conn);
            }
        } else{
            echo "ERROR: Could not prepare query: $sql2. " . mysqli_error($conn);
        }

    }else{

        $json_data[] = array(  
            'ERROR' 		=> 'failed',
        );
        
    }

}

/**********************************************************************************************/
/* mode = saveEdit_image                                                                           */
/**********************************************************************************************/
if($_POST["mode"] == "saveEdit_image"){

    $img = $_POST['img'];
    $name = $_POST['name'];
    $img = str_replace('data:image/png;base64,', '', $img);
    $img = str_replace(' ', '+', $img);
    $data = base64_decode($img);
    $file = $_POST['name'];
    // $file = 'uploads/img'.date("YmdHis").'.png';
    
    if (file_put_contents($file, $data)) {

        $json_data[] = array(  
            'ERROR' 		=> 'success',
        );
    
    } else {
        $json_data[] = array(  
            'ERROR' 		=> 'failed',
        );
    } 

}


/**********************************************************************************************/
/* mode = temp_prom                                                                           */
/**********************************************************************************************/

    if($_POST["mode"] == "temp_prom"){
        
        $rid = mysqli_real_escape_string($conn, $_POST['row_id']);

        /*----------------------------------------------------*/
        /*  1) Check pic by id                                */
        /*----------------------------------------------------*/  

        $sql0 = "   SELECT  *
                    FROM    temp_problem
                    WHERE   list_id = '$rid'
                    AND     pic1    !=  ''
                    AND     pic2    !=  ''
                    AND     pic3    !=  ''
        ";
        $sqlstm0 = '';
        $sqlstm0 = mysqli_query($conn, $sql0);
        $row0 = mysqli_fetch_assoc($sqlstm0);
        if($row0){

            // return valiable
            $json_data[] = array(  
    
                'ERROR' 		=> 'fullpic',
                    
            );

            goto echo_json;

        }

        /*----------------------------------------------------*/
        /*  1) update by id                                   */
        /*----------------------------------------------------*/  

        $sql = "    SELECT  *
                    FROM    temp_problem
                    WHERE   list_id = '$rid'
        ";
        $sqlstm = '';
        $sqlstm = mysqli_query($conn, $sql);
        $row = mysqli_fetch_assoc($sqlstm);
        if($row){

            if($row['pic1'] == ''){
                
                //----------------------//
                //-----  image 1 -------//
                //----------------------//
        
                if(isset($_FILES['ngImage1']['error'])){
                    $target_dir = "temp_img/";
        
                    $name = 'temp_pic'.'_id'.$rid.'_'.'1.jpg';
                    
                    $target_file = @$target_dir.''.$name;
            
                    $sourcePath = $_FILES['ngImage1']['tmp_name'];       // Storing source path of the file in a variable
                    move_uploaded_file($sourcePath,$target_file) ;    // Moving Uploaded file  
    
                    $sql = "    UPDATE  temp_problem
                                SET 	pic1 = '$target_file'
                                WHERE 	list_id = '$rid'
                    ";
    
                    $sqlstm = '';
                    if($sqlstm = mysqli_prepare($conn, $sql)){
                        
                        if(mysqli_stmt_execute($sqlstm)){
    
                            // return valiable
                            $json_data[] = array(  
    
                                'ERROR' 		=> 'success',
                                'COUNT_PIC' 	=> '1',
                                    
                            );
                            goto echo_json;
    
                        } else{
                            echo "ERROR: Could not execute query: $sql. " . mysqli_error($conn);
                        }
                    } else{
                        echo "ERROR: Could not prepare query: $sql. " . mysqli_error($conn);
                    }

                    
                }// end image 1
            }

            if($row['pic2'] == ''){
                
                //----------------------//
                //-----  image 2 -------//
                //----------------------//
        
                if(isset($_FILES['ngImage1']['error'])){
                    $target_dir = "temp_img/";
        
                    $name = 'temp_pic'.'_id'.$rid.'_'.'2.jpg';
                    
                    $target_file = @$target_dir.''.$name;
            
                    $sourcePath = $_FILES['ngImage1']['tmp_name'];       // Storing source path of the file in a variable
                    move_uploaded_file($sourcePath,$target_file) ;    // Moving Uploaded file  
    
                    $sql = "    UPDATE  temp_problem
                                SET 	pic2 = '$target_file'
                                WHERE 	list_id = '$rid'
                    ";
    
                    $sqlstm = '';
                    if($sqlstm = mysqli_prepare($conn, $sql)){
                        
                        if(mysqli_stmt_execute($sqlstm)){
    
                            // return valiable
                            $json_data[] = array(  
    
                                'ERROR' 		=> 'success',
                                'COUNT_PIC' 	=> '2',
                                    
                            );
                            goto echo_json;
    
                        } else{
                            echo "ERROR: Could not execute query: $sql. " . mysqli_error($conn);
                        }
                    } else{
                        echo "ERROR: Could not prepare query: $sql. " . mysqli_error($conn);
                    }

                    
                }// end image 2
            }

            if($row['pic3'] == ''){
                //----------------------//
                //-----  image 3 -------//
                //----------------------//

                if(isset($_FILES['ngImage1']['error'])){
                    $target_dir = "temp_img/";
        
                    $name = 'temp_pic'.'_id'.$rid.'_'.'3.jpg';
                    
                    $target_file = @$target_dir.''.$name;
            
                    $sourcePath = $_FILES['ngImage1']['tmp_name'];       // Storing source path of the file in a variable
                    move_uploaded_file($sourcePath,$target_file) ;    // Moving Uploaded file  
    
                    $sql = "    UPDATE  temp_problem
                                SET 	pic3 = '$target_file'
                                WHERE 	list_id = '$rid'
                    ";
    
                    $sqlstm = '';
                    if($sqlstm = mysqli_prepare($conn, $sql)){
                        
                        if(mysqli_stmt_execute($sqlstm)){
    
                            // return valiable
                            $json_data[] = array(  
    
                                'ERROR' 		=> 'success',
                                'COUNT_PIC' 	=> '3',
                                    
                            );
                            goto echo_json;
    
                        } else{
                            echo "ERROR: Could not execute query: $sql. " . mysqli_error($conn);
                        }
                    } else{
                        echo "ERROR: Could not prepare query: $sql. " . mysqli_error($conn);
                    }

                    
                }// end image 3
            }

            
        }else{

        /*----------------------------------------------------*/
        /*  Insert where new pic data                         */
        /*----------------------------------------------------*/  
 
        $sql = "    INSERT INTO temp_problem
                    VALUE (?,?,?,?,?,?,?)
        ";

        $sqlstm = '';

        $problem = '';
        $zone = '';
        $inc = '';
        $pic1 = '';
        $pic2 = '';
        $pic3 = '';

        if($sqlstm = mysqli_prepare($conn, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($sqlstm,'sssssss',$rid, $problem,$zone,$inc,$pic1,$pic2,$pic3);

            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($sqlstm)){

                // return valiable

                // $json_data[] = array(  

                //     'ERROR' 		=> 'success',
                //     'id' 		    => $rid,
                        
                // );

            } else{
                echo "ERROR: Could not execute query: $sql. " . mysqli_error($conn);
            }

        } else{
            echo "ERROR: Could not prepare query: $sql. " . mysqli_error($conn);
        }

        mysqli_stmt_close($sqlstm);
        
        /*----------------*/
        /*-- NG picture --*/
        /*----------------*/
	        
        ini_set ('gd.jpeg_ignore_warning', 1);
                //----------------------//
                //-----  image 1 -------//
                //----------------------//

                if(isset($_FILES['ngImage1']['error'])){
                    $target_dir = "temp_img/";
        
                    $name = 'temp_pic'.'_id'.$rid.'_'.'1.jpg';
                    
                    $target_file = @$target_dir.''.$name;
        
                    $sourcePath = $_FILES['ngImage1']['tmp_name'];       // Storing source path of the file in a variable
                    move_uploaded_file($sourcePath,$target_file) ;    // Moving Uploaded file  
    
                    $sql = "    UPDATE  temp_problem
                                SET 	pic1 = '$target_file'
                                WHERE 	list_id = '$rid'
                    ";
    
                    $sqlstm = '';
                    if($sqlstm = mysqli_prepare($conn, $sql)){
                        
                        if(mysqli_stmt_execute($sqlstm)){
    
                            // return valiable
                            $json_data[] = array(  
    
                                'ERROR' 		=> 'success',
                                'COUNT_PIC' 	=> '1',
                                    
                            );
    
                        } else{
                            echo "ERROR: Could not execute query: $sql. " . mysqli_error($conn);
                        }
                    } else{
                        echo "ERROR: Could not prepare query: $sql. " . mysqli_error($conn);
                    }

        
                }// end image 1

        }
         
    }

    
/**********************************************************************************************/
/* mode = get_detail                                                                          */
/**********************************************************************************************/

    if($_POST["mode"] == "get_detail"){
        $lid = mysqli_real_escape_string($conn, $_POST['lid']);

        $sql = "    SELECT  *
                    FROM    temp_problem
                    WHERE   list_id = '$lid'
        ";

        $sqlstm = '';
        $sqlstm = mysqli_query($conn, $sql);
        $row = mysqli_fetch_assoc($sqlstm);



        if($row){

            // return valiable
            $json_data[] = array(  

                'ERROR' 		=> 'success',
                'PROBLEM' 		=> $row['problem'],
                'ZONE' 		    => $row['zone'],
                'INC' 		    => $row['incharge'],
                'PIC1' 		    => $row['pic1'],
                'PIC2' 		    => $row['pic2'],
                'PIC3' 		    => $row['pic3'],
            
            );

        }else{

            $json_data[] = array(  

                'ERROR' 		=> 'failed',            
            );
        }

    }

/**********************************************************************************************/
/* mode = clear_image                                                                          */
/**********************************************************************************************/

if($_POST["mode"] == "clear_image"){
    $lid = mysqli_real_escape_string($conn, $_POST['lid']);
    $picid = mysqli_real_escape_string($conn, $_POST['picId']);
    $set = '';

    if ($picid == 1) {
        $set = "SET pic1 = ''";
    }
    if ($picid == 2) {
        $set = "SET pic2 = ''";
    }
    if ($picid == 3) {
        $set = "SET pic3 = ''";
    }

    $sql = "    SELECT  *
                FROM    temp_problem
                WHERE   list_id = '$lid'
    ";

        $sqlstm = '';
        $sqlstm = mysqli_query($conn, $sql);
        $row = mysqli_fetch_assoc($sqlstm);

        if($row){

            $sql1 = "   UPDATE  temp_problem
                        $set
                        WHERE 	list_id = '$lid'
            ";

            $sqlstm1 = '';
            if($sqlstm1 = mysqli_prepare($conn, $sql1)){
                
                if(mysqli_stmt_execute($sqlstm1)){

                    $filename = 'temp_img/temp_pic_id'.$lid.'_'.$picid.'.jpg';
                    $newpic = '';
                    $i = 1;

                    if( file_exists($filename) ){
                        //delete Excel file
                        unlink($filename);
                    }
                    
                    foreach (glob('temp_img/temp_pic_id'.$lid.'_'.'*.jpg') as $oldfilename ) {

                        rename($oldfilename, 'temp_img/temp_pic_id'.$lid.'_'.$i.'.jpg');
                        $newName = 'temp_img/temp_pic_id'.$lid.'_'.$i.'.jpg';
                        if ($i == 1) {
                            $newpic = "SET pic1 = '$newName' , pic2 = '' , pic3 = '' ";
                        }
                        if ($i == 2) {
                            $newpic = "SET pic2 = '$newName' , pic3 = '' ";
                        }
                        if ($i == 3) {
                            $newpic = "SET pic3 = '$newName'";
                        }

                        $sql2 = "   UPDATE  temp_problem
                                    $newpic
                                    WHERE 	list_id = '$lid'
                        ";

                        $sqlstm2 = '';
                        if($sqlstm2 = mysqli_prepare($conn, $sql2)){
                            
                            if(mysqli_stmt_execute($sqlstm2)){


                        
                            } else{
                                echo "ERROR: Could not execute query: $sql2. " . mysqli_error($conn);
                            }
                        } else{
                            echo "ERROR: Could not prepare query: $sql2. " . mysqli_error($conn);
                        }

                        $i++;
                    }

                    // return valiable
                    $json_data[] = array(  

                        'ERROR' 		=> 'success',
                        'COUNT_PIC' 	=> ($i-1),

                    );
                

                } else{
                    echo "ERROR: Could not execute query: $sql1. " . mysqli_error($conn);
                }
            } else{
                echo "ERROR: Could not prepare query: $sql1. " . mysqli_error($conn);
            }



        }

}


/**********************************************************************************************/
/* mode = save_prom                                                                           */
/**********************************************************************************************/

if($_POST["mode"] == "save_prom"){
        
    $rid = mysqli_real_escape_string($conn, $_POST['row_id']);
    $problem = mysqli_real_escape_string($conn, $_POST['problem']);
    $zone = mysqli_real_escape_string($conn, $_POST['zone']);
    $inc = mysqli_real_escape_string($conn, $_POST['inc']);

    /*----------------------------------------------------*/
    /*  1) delete id                                      */
    /*----------------------------------------------------*/  

    $sql = "    SELECT  *
                FROM    temp_problem
                WHERE   list_id = '$rid'
    ";
    $sqlstm = '';
    $sqlstm = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($sqlstm);
    if($row){

        $sql = "    UPDATE  temp_problem
                    SET 	problem     = '$problem'
                    ,       zone        = '$zone'
                    ,       incharge    = '$inc'  
                    WHERE 	list_id     = '$rid'
        ";

        $sqlstm = '';
        if($sqlstm = mysqli_prepare($conn, $sql)){
            
            if(mysqli_stmt_execute($sqlstm)){

                // return valiable
                $json_data[] = array(  

                    'ERROR' 		=> 'success',
                    'id' 		    => $rid,
                        
                );
                goto echo_json;

            } else{
                echo "ERROR: Could not execute query: $sql. " . mysqli_error($conn);
            }
        } else{
            echo "ERROR: Could not prepare query: $sql. " . mysqli_error($conn);
        }

    }else{

        /*----------------------------------------------------*/
        /*  2) Insert ng_barcode data                         */
        /*----------------------------------------------------*/  

        $sql = "    INSERT INTO temp_problem
                    VALUE (?,?,?,?,?,?,?)
        ";

        $sqlstm = '';

        $pic1 = '';
        $pic2 = '';
        $pic3 = '';

        if($sqlstm = mysqli_prepare($conn, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($sqlstm,'sssssss',$rid, $problem,$zone,$inc,$pic1,$pic2,$pic3);

            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($sqlstm)){

                // return valiable

                $json_data[] = array(  

                    'ERROR' 		=> 'success',
                    'id' 		    => $rid,
                        
                );

            } else{
                echo "ERROR: Could not execute query: $sql. " . mysqli_error($conn);
            }

        } else{
            echo "ERROR: Could not prepare query: $sql. " . mysqli_error($conn);
        }

        mysqli_stmt_close($sqlstm);
        

    }

}

/**********************************************************************************************/
/* mode = save_data                                                                           */
/**********************************************************************************************/

if($_POST["mode"] == "save_data"){

    $cur_date = date('Y-m-d');
    $cur_time = date('H:i:s');
    $arr = json_decode($_POST['rowData'],true);
    // print_r($arr);
    foreach ($arr  as $value) {

        $id = $value['id'];
        /*-------------------------*/
		/*  3) select number       */
        /*-------------------------*/  
        
        $sql1 = "   SELECT  COALESCE(MAX(pl_id),0) AS maxseq
                    FROM    problem_list
        ";

        $sqlstm1 = mysqli_query($conn, $sql1);
        $row1 = mysqli_fetch_assoc($sqlstm1);
        
        if ($row1) {
            $number = $row1['maxseq'] + 1;
        }

        $code = sprintf('P%09d', $number);

        /*----------------------------------------------------*/
        /*   Insert ng_barcode data                           */
        /*----------------------------------------------------*/  
        $inc = '';
        $sql = "    INSERT INTO problem_list
                    VALUE (NULL,?,?,?,?,?,?,?,?,?,?,?,?,?,?)
        ";

        $sqlstm = '';

        if($sqlstm = mysqli_prepare($conn, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($sqlstm,'ssssssssssssss',$value['id'], $value['reg'],$value['check_list'],$value['option'],$value['toq'],$value['res'],$inc,$code,$cur_date,$cur_time,$_SESSION['username_w'],$cur_date,$cur_time,$_SESSION['username_w']);

            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($sqlstm)){

                // return valiable

                $json_data[] = array(  

                    'ERROR' 		=> 'success',
                        
                );

            } else{
                echo "ERROR: Could not execute query: $sql. " . mysqli_error($conn);
            }

        } else{
            echo "ERROR: Could not prepare query: $sql. " . mysqli_error($conn);
        }

        mysqli_stmt_close($sqlstm);

        $sql3 = "   SELECT  *
                    FROM    temp_problem
                    WHERE   list_id = '$id'
        ";

        $sqlstm3 = '';
        $sqlstm3 = mysqli_query($conn, $sql3);
        $row3 = mysqli_fetch_assoc($sqlstm3);

        if($row3){
            $incharge = $row3['incharge'];
            // $x = 1;
            $repair_report = '';
            $repair_pic1 = '';
            $repair_pic2 = '';
            $repair_pic3 = '';
            $repairman_id = '';
            $repair_flag = 'N';

            if($row3['pic1'] != ''){
                $re_pic1 = 'image_detail/'.'temp_pic_id'.$id.'_'.'1'.'.jpg';
            }else{
                $re_pic1 = '';
            }
            if($row3['pic2'] != ''){
                $re_pic2 = 'image_detail/'.'temp_pic_id'.$id.'_'.'2'.'.jpg';
            }else{
                $re_pic2 = '';
            }
            if($row3['pic3'] != ''){
                $re_pic3 = 'image_detail/'.'temp_pic_id'.$id.'_'.'3'.'.jpg';
            }else{
                $re_pic3 = '';
            }

        /*----------------------------------------------------*/
        /*   update inc to problem_list                       */
        /*----------------------------------------------------*/  

            $sql = "    UPDATE  problem_list
                        SET 	pl_inc = '$incharge'
                        WHERE 	pl_index = '$id'
            ";

            $sqlstm = '';
            if($sqlstm = mysqli_prepare($conn, $sql)){

            if(mysqli_stmt_execute($sqlstm)){

            // return valiable
            $json_data[] = array(  

                'ERROR' 		=> 'success',
                    
            );

            } else{
            echo "ERROR: Could not execute query: $sql. " . mysqli_error($conn);
            }
            } else{
            echo "ERROR: Could not prepare query: $sql. " . mysqli_error($conn);
            }

            
        /*----------------------------------------------------*/
        /*   Insert                                           */
        /*----------------------------------------------------*/  
 
        $sql = "    INSERT INTO problem_record
                    VALUE (?,?,?,?,?,?,?,?,?,?,?,?,?,?)
        ";

        $sqlstm = '';

        if($sqlstm = mysqli_prepare($conn, $sql)){
        // Bind variables to the prepared statement as parameters
        mysqli_stmt_bind_param($sqlstm,'ssssssssssssss',$code, $id,$row3['problem'],$row3['zone'],$re_pic1,$re_pic2,$re_pic3,$_SESSION['username_w'],$repair_report,$repair_pic1,$repair_pic2,$repair_pic3,$repairman_id,$repair_flag);

        // Attempt to execute the prepared statement
        if(mysqli_stmt_execute($sqlstm)){


        } else{
            echo "ERROR: Could not execute query: $sql. " . mysqli_error($conn);
        }

        } else{
        echo "ERROR: Could not prepare query: $sql. " . mysqli_error($conn);
        }

        mysqli_stmt_close($sqlstm);

        // $x++;

        }

        $dir = 'temp_img/';
        $pic_name = 'temp_pic_id';
        $picfilename = 'temp_img/';
        $i = 1;
        
        foreach (glob($dir.$pic_name.$id.'_'.'*.jpg') as $filename ) {
            copy($filename, 'image_detail/'.$pic_name.$id.'_'.$i.'.jpg');
            $i++;
            if( file_exists($filename) ){
				//delete Excel file
				unlink($filename);
				}
        }

    }

    

    $sql =	"   DELETE	FROM    temp_problem 
    WHERE	1
    ";

    $sqlstm = '';
    if($sqlstm = mysqli_prepare($conn, $sql)){

    if(mysqli_stmt_execute($sqlstm)){

    // return valiable
    $json_data[] = array(  

    'ERROR' 		=> 'success',
        
    );

    } else{
    echo "ERROR: Could not execute query: $sql. " . mysqli_error($conn);
    }

    } else{
    echo "ERROR: Could not prepare query: $sql. " . mysqli_error($conn);
    }

    mysqli_stmt_close($sqlstm);
}

/************************* Return Variable ****************************************************/
echo_json:
echo json_encode($json_data);
mysqli_close($conn);
exit();

?>