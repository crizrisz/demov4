<?php
    
    session_start();

    $conn = mysqli_connect('localhost', 'root', '', 'spec_demo');
        if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
        }
    // mysqli_query($conn, "SET NAMES tis620");
    mysqli_query($conn, "SET NAMES utf8");

    date_default_timezone_set("Asia/Bangkok");

/**********************************************************************************************/
/* mode = login                                                                         */
/**********************************************************************************************/

    if($_POST['mode'] == 'login'){

        $json_data = array();
        $username = mysqli_real_escape_string($conn, $_POST['username']);
        $password = mysqli_real_escape_string($conn, $_POST['password']);

        $sql1 =  "  SELECT  user_id, eng_name, trim(user_role) as user_role
                    FROM    user_master
                    WHERE   user_id = '$username'
                    AND     password = '$password'        
        ";

        $sqlstm1 = mysqli_query($conn, $sql1);
        $row1 = mysqli_fetch_assoc($sqlstm1);

        if ($row1) {

            /*------------------------------ */
            /* ---  check role admin     --- */
            /*------------------------------ */
            // $web_role = $row1['user_role'];
            // if( $web_role !== 'Administrator' ){
            //     $json_data[] = array(  
            //         'ERROR' 		=> 'wrong_role',
            //     );    
            // goto echo_json;
            // }

            $_SESSION['loggedINW'] = '1';
            $_SESSION['username_w'] = $username;
            $_SESSION['role'] = $row1['user_role'];
            $_SESSION['ename_w'] = $row1['eng_name'];  

            $json_data[] = array(  
                'ERROR' 		=> 'success',
                'ROLE' 		    => $row1['user_role'],
            );

            goto echo_json;

        } else {

            $json_data[] = array(  
                'ERROR' 		=> 'failed',
            );

            goto echo_json;

        }  
    
        
    }

/************************* Return Variable ****************************************************/
echo_json:
echo json_encode($json_data);
mysqli_close($conn);
exit();

?>