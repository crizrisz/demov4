<?php
    
    session_start();

    $conn = mysqli_connect('localhost', 'u640519561_msc', 'msc1234', 'u640519561_spec	');
        if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
        }
    // mysqli_query($conn, "SET NAMES tis620");
    mysqli_query($conn, "SET NAMES utf8");

    date_default_timezone_set("Asia/Bangkok");


    $json_data = array();

/**********************************************************************************************/
/* mode = zone_list                                                                           */
/**********************************************************************************************/
if($_POST["mode"] == "repair_list"){
    // load role list from master
    $sql =	"	SELECT 	trim(repair) as D1REP
                FROM	master_repair
                ORDER BY repair 
    ";

    $sqlstm = '';
    $sqlstm = mysqli_query($conn, $sql);

    while($row = mysqli_fetch_assoc($sqlstm)){

    $json_data[] = $row;

    }
}


/**********************************************************************************************/
/* mode = get_data                                                                            */
/**********************************************************************************************/
if($_POST["mode"] == "get_data"){
    
    $role = strtoupper($_SESSION['role']);//bow
	// $role = strtoupper($_SESSION['role']);//bow

    $sql2 = "   SELECT  *
                FROM    problem_list A
                WHERE   pl_inc = '$role'
                AND 	pl_res ='NG'
                AND   EXISTS (SELECT 1 FROM problem_record  B where A.pr_id = B.pr_id
                                AND trim(repair_note) = '')
                ORDER BY CONVERT(SUBSTRING(pl_index, 6), SIGNED INTEGER);
    ";

    $sqlstm2 = mysqli_query($conn, $sql2);

    while ($row2 = mysqli_fetch_assoc($sqlstm2)) {


    		// $ans_data = array();
    		$running = $row2['pl_index'];
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


    		//get problem
    		$problem_id = mysqli_real_escape_string($conn, $row2['pr_id']);//bow
    	  	 $sql = "   SELECT problem, prom_pic1,prom_pic2,prom_pic3
                FROM    problem_record
                WHERE   pr_id = '$problem_id' 
    		";
    		$sqlstm = mysqli_query($conn, $sql);
    		$row = mysqli_fetch_assoc($sqlstm);


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
                'DB_PROBLEM'            => $row['problem'],
                'DB_PIC1'               => $row['prom_pic1'],
                'DB_PIC2'               => $row['prom_pic2'],
                'DB_PIC3'               => $row['prom_pic3'],
            );

    }

}
/**********************************************************************************************/
/* mode = get_data2                                                                            */
/**********************************************************************************************/
if($_POST["mode"] == "get_data2"){

	    	//get problem
    		$problem_id = mysqli_real_escape_string($conn, $_POST['lid']);//bow
    	  	$sql = "   SELECT problem, prom_pic1,prom_pic2,prom_pic3,zone,repair_note,worker_id
                FROM    problem_record
                WHERE   pr_id = '$problem_id' 
    		";
    		$sqlstm = mysqli_query($conn, $sql);
            $row = mysqli_fetch_assoc($sqlstm);
            
            $sql2 = "   SELECT update_date, update_time
            FROM    problem_list
            WHERE   pr_id = '$problem_id' 
            ";
            $sqlstm2 = mysqli_query($conn, $sql2);
            $row2 = mysqli_fetch_assoc($sqlstm2);

	            $json_data[] = array(  
                'PROBLEM' 			=> $row['problem'],
                'ZONE' 			    => $row['zone'],
                'PIC1' 				=> $row['prom_pic1'],
                'PIC2' 				=> $row['prom_pic2'],
                'PIC3' 				=> $row['prom_pic3'],
                'NOTE' 				=> $row['repair_note'],
                'WORKER' 			=> $row['worker_id'],
                'DATE' 			    => $row2['update_date'],
                'TIME' 			    => $row2['update_time'],
            );



}
/**********************************************************************************************/
/* mode = save_note                                                                            */
/**********************************************************************************************/
if($_POST["mode"] == "save_note"){

    //get problem
    $repair_note = mysqli_real_escape_string($conn, $_POST['note']);
    $rid = mysqli_real_escape_string($conn, $_POST['rid']);

    $sql = "    UPDATE  problem_record
                SET 	repair_note = '$repair_note'
                WHERE 	pr_id = '$rid'
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

/**********************************************************************************************/
/* mode = temp_prom                                                                           */
/**********************************************************************************************/
//wisawa
if($_POST["mode"] == "temp_prom"){
   
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