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
/* mode = get_data                                                                            */
/**********************************************************************************************/
// copy('temp_img/temp_pic_id1_1.jpg', 'image_detail/image1.jpg');

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

        $sql =	"   DELETE	FROM    problem_list 
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

        $sql =	"   DELETE	FROM    problem_record 
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
        
        foreach (glob('temp_img/temp_pic_id*.jpg') as $filename ) {
            if( file_exists($filename) ){
				//delete Excel file
				unlink($filename);
				}
        }

        foreach (glob('image_detail/temp_pic_id*.jpg') as $filename ) {
            if( file_exists($filename) ){
				//delete Excel file
				unlink($filename);
				}
        }

        foreach (glob('image_fix/fix_pic_id*.jpg') as $filename ) {
            if( file_exists($filename) ){
				//delete Excel file
				unlink($filename);
				}
        }

    header('Location: logout.php');
    exit();

/************************* Return Variable ****************************************************/
echo_json:
echo json_encode($json_data);
mysqli_close($conn);
exit();

?>