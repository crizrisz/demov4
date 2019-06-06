<?php
    session_start();

    $conn = mysqli_connect('localhost', 'u640519561_msc', 'msc1234', 'u640519561_spec	');
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    $sql =	"   DELETE	FROM    temp_problem 
    WHERE	1
    ";

    $sqlstm = '';
    if($sqlstm = mysqli_prepare($conn, $sql)){

    if(mysqli_stmt_execute($sqlstm)){

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

    unset($_SESSION['loggedINW']);
    unset($_SESSION['username_w']);
    unset($_SESSION['role']);
    unset($_SESSION['ename_w']);
    // session_unset();
    // session_destroy();
    header('Location: index.php');
    exit();

?>