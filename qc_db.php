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
/* mode = get_data                                                                            */
/**********************************************************************************************/
$strAction = isset($_POST["mode"]) ? $_POST["mode"] : '';
if($strAction == "get_data"){

   // repair_note <> ' ' //bow
    
    $sql2 = "   SELECT  *
                FROM    problem_list A
                WHERE 	pl_res ='NG'
                AND   EXISTS (SELECT 1 FROM problem_record  B where A.pr_id = B.pr_id
                                AND trim(repair_note) <> '')
                ORDER BY CONVERT(SUBSTRING(pl_index, 6), SIGNED INTEGER)
    ";

    $sqlstm2 = mysqli_query($conn, $sql2);

    while ($row2 = mysqli_fetch_assoc($sqlstm2)) {


    		// $ans_data = array();
            $running = $row2['pl_index'];
            $prid = $row2['pr_id'];
    		$spec_id = 'SP0000000'.$row2['pl_index'];

    		if (strlen(trim($row2['pl_index']))>1){
    			$spec_id = 'SP000000'.$row2['pl_index'];
    		}


    		// answer 1
    		$spec_id = mysqli_real_escape_string($conn, $spec_id);//bow
    	  	 $sql = "   SELECT spec_name as ans
                FROM    spec_master
                WHERE   spec_id = '$spec_id' 
                AND     spec_item = '1'
    		";
    		$sqlstm = mysqli_query($conn, $sql);
    		$row = mysqli_fetch_assoc($sqlstm);
    		$ans1 = $row['ans'];


    		// answer 2
    		$spec_id = mysqli_real_escape_string($conn, $spec_id);//bow
    	  	 $sql = "   SELECT spec_name as ans
                FROM    spec_master
                WHERE   spec_id = '$spec_id' 
                AND     spec_item = '2'
    		";
    		$sqlstm = mysqli_query($conn, $sql);
    		$row = mysqli_fetch_assoc($sqlstm);
    		$ans2 = $row['ans'];


    		// answer 3
    		$spec_id = mysqli_real_escape_string($conn, $spec_id);//bow
    	  	 $sql = "   SELECT spec_name as ans
                FROM    spec_master
                WHERE   spec_id = '$spec_id' 
                AND     spec_item = '3'
    		";
    		$sqlstm = mysqli_query($conn, $sql);
    		$row = mysqli_fetch_assoc($sqlstm);
    		$ans3 = $row['ans'];


            $sql = "    SELECT  problem, zone
                        FROM    problem_record
                        WHERE   pr_id = '$prid' 
    		";
    		$sqlstm = mysqli_query($conn, $sql);
            $row88 = mysqli_fetch_assoc($sqlstm);
    		//get problem
    		// $problem_id = mysqli_real_escape_string($conn, $row2['pr_id']);//bow
    	 //  	 $sql = "   SELECT problem, prom_pic1,prom_pic2,prom_pic3
      //           FROM    problem_record
      //           WHERE   pr_id = '$problem_id' 
    		// ";
    		// $sqlstm = mysqli_query($conn, $sql);
    		// $row = mysqli_fetch_assoc($sqlstm);


            $json_data[] = array(  
                'RUNN' 				=> $running,
                'DB_QUESTION' 			=> trim($row2['check_list']),
                'DB_REG' 				=> $row2['regulation'],
                'DB_SPEC1' 				=> $ans1,
                'DB_SPEC2' 		    	=> $ans2,
                'DB_SPEC3' 		    	=> $ans3,
                'DB_CHOOSE' 		    => $row2['spec_id'],
                'DB_TORQUE' 			=> $row2['pl_toq'],
                'DB_RESULT' 			=> $row2['pl_res'],
                'DB_INC' 				=> $row2['pl_inc'],
                'DB_DEFECT' 			=> $row2['pr_id'],
                'DB_PROM' 				=> $row88['problem'],
                'DB_ZONE' 		    	=> $row88['zone'],
            );

    }

}
/**********************************************************************************************/
/* mode = get_data2                                                                            */
/**********************************************************************************************/
if($strAction == "get_data2"){

	    	//get problem
    		$problem_id = mysqli_real_escape_string($conn, $_POST['lid']);//bow
    	  	 $sql = "   SELECT  problem, prom_pic1,prom_pic2,prom_pic3,zone, repair_note,worker_id
                        FROM    problem_record
                        WHERE   pr_index = '$problem_id' 
    		";
    		$sqlstm = mysqli_query($conn, $sql);
            $row = mysqli_fetch_assoc($sqlstm);
            
            $sql2 = "   SELECT  update_date, update_time
                        FROM    problem_list
                        WHERE   pl_index = '$problem_id' 
            ";
            $sqlstm2 = mysqli_query($conn, $sql2);
            $row2 = mysqli_fetch_assoc($sqlstm2);

	            $json_data[] = array(  
                'ERROR'	            => 'success',
                'PROBLEM' 			=> $row['problem'],
                'ZONE' 			    => $row['zone'],
                'PIC1' 				=> $row['prom_pic1'],
                'PIC2' 				=> $row['prom_pic2'],
                'PIC3' 				=> $row['prom_pic3'],
                'REPAIR'            => $row['repair_note'],
                'WORKER' 			=> $row['worker_id'],
                'DATE' 			    => $row2['update_date'],
                'TIME' 			    => $row2['update_time'],
          
                
            );



}

/**********************************************************************************************/
/* mode = temp_prom                                                                           */
/**********************************************************************************************/

if($_POST["mode"] == "save_image"){
        
    // $cur_date = date('Y-m-d');
    // $cur_time = date('H:i:s');
    $rid = mysqli_real_escape_string($conn, $_POST['row_id']);
    // $approve = mysqli_real_escape_string($conn, $_POST['approve']);
    $user = $_SESSION['username_w'];
    // $res = '';

    // if($approve == 'ON'){
    //     $res = 'OK';
    // }
    /*----------------------------------------------------*/
    /*  1) Check pic by id                                */
    /*----------------------------------------------------*/  

    $sql0 = "   SELECT  *
                FROM    problem_record
                WHERE   pr_index = '$rid'
                AND     qc_pic1    !=  ''
                AND     qc_pic2    !=  ''
                AND     qc_pic3    !=  ''
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
                FROM    problem_record
                WHERE   pr_index = '$rid'
    ";
    $sqlstm = '';
    $sqlstm = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($sqlstm);
    if($row){

        if($row['qc_pic1'] == ''){
            
            //----------------------//
            //-----  image 1 -------//
            //----------------------//
    
            if(isset($_FILES['ngImage1']['error'])){
                $target_dir = "image_fix/";
    
                $name = 'fix_pic'.'_id'.$rid.'_'.'1.jpg';
                
                $target_file = @$target_dir.''.$name;
    
                $sourcePath = $_FILES['ngImage1']['tmp_name'];       // Storing source path of the file in a variable
                move_uploaded_file($sourcePath,$target_file) ;    // Moving Uploaded file  
    
                $sql = "    UPDATE  problem_record
                            SET 	qc_pic1 = '$target_file'
                            ,		qc_id = '$user'
                            WHERE 	pr_index = '$rid'
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

        if($row['qc_pic2'] == ''){
            
            //----------------------//
            //-----  image 2 -------//
            //----------------------//
    
            if(isset($_FILES['ngImage1']['error'])){
                $target_dir = "image_fix/";
    
                $name = 'fix_pic'.'_id'.$rid.'_'.'2.jpg';
                
                $target_file = @$target_dir.''.$name;
    
                $sourcePath = $_FILES['ngImage1']['tmp_name'];       // Storing source path of the file in a variable
                move_uploaded_file($sourcePath,$target_file) ;    // Moving Uploaded file  
    
                $sql = "    UPDATE  problem_record
                            SET 	qc_pic2 = '$target_file'
                            ,		qc_id = '$user'
                            WHERE 	pr_index = '$rid'
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

        if($row['qc_pic3'] == ''){
            //----------------------//
            //-----  image 3 -------//
            //----------------------//

            if(isset($_FILES['ngImage1']['error'])){
                $target_dir = "image_fix/";
    
                $name = 'fix_pic'.'_id'.$rid.'_'.'3.jpg';
                
                $target_file = @$target_dir.''.$name;
    
                $sourcePath = $_FILES['ngImage1']['tmp_name'];       // Storing source path of the file in a variable
                move_uploaded_file($sourcePath,$target_file) ;    // Moving Uploaded file  
    
                $sql = "    UPDATE  problem_record
                            SET 	qc_pic3 = '$target_file'
                            ,		qc_id = '$user'
                            WHERE 	pr_index = '$rid'
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

        // $sql = "    UPDATE  problem_list
        //             SET 	pl_res = '$res'
        //             ,		update_date = '$cur_date'
        //             ,		update_time = '$cur_time'
        //             ,		update_user = '$user'
        //             WHERE 	pr_id = '$rid'
        // ";

        // $sqlstm = '';
        // if($sqlstm = mysqli_prepare($conn, $sql)){

        // if(mysqli_stmt_execute($sqlstm)){

        //     // return valiable
        //     $json_data[] = array(  

        //         'ERROR' 		=> 'success',
                    
        //     );

        // } else{
        //     echo "ERROR: Could not execute query: $sql. " . mysqli_error($conn);
        // }
        // } else{
        // echo "ERROR: Could not prepare query: $sql. " . mysqli_error($conn);
        // }

        // mysqli_stmt_close($sqlstm);

        
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
/* mode = get_detail                                                                          */
/**********************************************************************************************/

if($_POST["mode"] == "get_detail"){
    $lid = mysqli_real_escape_string($conn, $_POST['lid']);

    $sql = "    SELECT  *
                FROM    problem_record
                WHERE   pr_index = '$lid'
    ";

    $sqlstm = '';
    $sqlstm = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($sqlstm);

    if($row){

        // return valiable
        $json_data[] = array(  

            'ERROR' 		=> 'success',
            'PIC1' 		    => $row['qc_pic1'],
            'PIC2' 		    => $row['qc_pic2'],
            'PIC3' 		    => $row['qc_pic3'],
        
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
        $set = "SET qc_pic1 = ''";
    }
    if ($picid == 2) {
        $set = "SET qc_pic2 = ''";
    }
    if ($picid == 3) {
        $set = "SET qc_pic3 = ''";
    }

    $sql = "    SELECT  *
                FROM    problem_record
                WHERE   pr_index = '$lid'
    ";

        $sqlstm = '';
        $sqlstm = mysqli_query($conn, $sql);
        $row = mysqli_fetch_assoc($sqlstm);

        if($row){

            $sql1 = "   UPDATE  problem_record
                        $set
                        WHERE 	pr_index = '$lid'
            ";

            $sqlstm1 = '';
            if($sqlstm1 = mysqli_prepare($conn, $sql1)){
                
                if(mysqli_stmt_execute($sqlstm1)){

                    $filename = 'image_fix/fix_pic_id'.$lid.'_'.$picid.'.jpg';
                    $newpic = '';
                    $i = 1;

                    if( file_exists($filename) ){
                        //delete Excel file
                        unlink($filename);
                    }
                    
                    foreach (glob('image_fix/fix_pic_id'.$lid.'_'.'*.jpg') as $oldfilename ) {

                        rename($oldfilename, 'image_fix/fix_pic_id'.$lid.'_'.$i.'.jpg');
                        $newName = 'image_fix/fix_pic_id'.$lid.'_'.$i.'.jpg';
                        if ($i == 1) {
                            $newpic = "SET qc_pic1 = '$newName' , qc_pic2 = '' , qc_pic3 = '' ";
                        }
                        if ($i == 2) {
                            $newpic = "SET qc_pic2 = '$newName' , qc_pic3 = '' ";
                        }
                        if ($i == 3) {
                            $newpic = "SET qc_pic3 = '$newName'";
                        }

                        $sql2 = "   UPDATE  problem_record
                                    $newpic
                                    WHERE 	pr_index = '$lid'
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
/* mode = save_data                                                                           */
/**********************************************************************************************/

if($_POST["mode"] == "save_data"){

    $cur_date = date('Y-m-d');
    $cur_time = date('H:i:s');
    $arr = json_decode($_POST['rowData'],true);
    $user = $_SESSION['username_w'];
    $res = '';

    
    // print_r($arr);
    foreach ($arr  as $value) {

        $rid = $value['id'];
        $approve = $value['app'];

        if($approve == 'ON'){
            $res = 'OK';
        }

        $sql = "    UPDATE  problem_list
                    SET 	pl_res = '$res'
                    ,		update_date = '$cur_date'
                    ,		update_time = '$cur_time'
                    ,		update_user = '$user'
                    WHERE 	pl_index = '$rid'
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

}


/**********************************************************************************************/
/* mode = temp_prom                                                                           */
/**********************************************************************************************/
//wisawa
if($strAction == "temp_prom"){
   
        $rid = mysqli_real_escape_string($conn, $_POST['row_id']);
        $problem = mysqli_real_escape_string($conn, $_POST['problem']);
        $zone = mysqli_real_escape_string($conn, $_POST['zone']);

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

            $sql2 =	"   DELETE	
                        FROM    temp_problem
                        WHERE	list_id = '$rid'
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

            // mysqli_stmt_close($sqlstm2);

        }

        /*----------------------------------------------------*/
        /*  2) Insert ng_barcode data                         */
        /*----------------------------------------------------*/  
 
        $sql = "    INSERT INTO temp_problem
                    VALUE (?,?,?,?,?,?)
        ";

        $sqlstm = '';

        $pic1 = '';
        $pic2 = '';
        $pic3 = '';

        if($sqlstm = mysqli_prepare($conn, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($sqlstm,'ssssss',$rid, $problem,$zone,$pic1,$pic2,$pic3);

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
        
        /*----------------*/
        /*-- NG picture --*/
        /*----------------*/
	        
    
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
                                    
                            );
    
                        } else{
                            echo "ERROR: Could not execute query: $sql. " . mysqli_error($conn);
                        }
                    } else{
                        echo "ERROR: Could not prepare query: $sql. " . mysqli_error($conn);
                    }

        
                }// end image 1
        
                //----------------------//
                //-----  image 2 -------//
                //----------------------//
        
                if(isset($_FILES['ngImage2']['error'])){
                    $target_dir = "temp_img/";
        
                    $name = 'temp_pic'.'_id'.$rid.'_'.'2.jpg';
                    
                    $target_file = @$target_dir.''.$name;
            
                    $sourcePath = $_FILES['ngImage2']['tmp_name'];       // Storing source path of the file in a variable
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
                                    
                            );
    
                        } else{
                            echo "ERROR: Could not execute query: $sql. " . mysqli_error($conn);
                        }
                    } else{
                        echo "ERROR: Could not prepare query: $sql. " . mysqli_error($conn);
                    }

                    
                }// end image 2

                //----------------------//
                //-----  image 3 -------//
                //----------------------//

                if(isset($_FILES['ngImage3']['error'])){
                    $target_dir = "temp_img/";
        
                    $name = 'temp_pic'.'_id'.$rid.'_'.'3.jpg';
                    
                    $target_file = @$target_dir.''.$name;
            
                    $sourcePath = $_FILES['ngImage3']['tmp_name'];       // Storing source path of the file in a variable
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
                                    
                            );
    
                        } else{
                            echo "ERROR: Could not execute query: $sql. " . mysqli_error($conn);
                        }
                    } else{
                        echo "ERROR: Could not prepare query: $sql. " . mysqli_error($conn);
                    }

                    
                }// end image 3
 

}
/************************* Return Variable ****************************************************/
echo_json:
echo json_encode($json_data);
mysqli_close($conn);
exit();

?>