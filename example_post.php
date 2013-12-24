<?php



if($_FILES['fileContents']['error'] == ''){
    $uploaddir = './';
    $file = basename($_FILES['fileContents']['name']);

    $uploadfile = $uploaddir . $file;
    if(file_exists($_FILES['fileContents']['tmp_name'])){
       if (move_uploaded_file($_FILES['fileContents']['tmp_name'], $uploadfile)) {
          error_log( "GOOD");
       }
    } 
    else {
       error_log("ERROR");
    }

}
else{
    error_log("Error In Uploading File");
}


foreach (getallheaders() as $name => $value) {
    error_log( "Header Info-- " . "$name: $value\n");
}
// Dump x
ob_start();
var_dump($_FILES);
$contents = ob_get_contents();
ob_end_clean();
error_log($contents);
 ob_end_flush();
//    error_log ( "Name = '" . $name . "', filename = '" . $filename . "'." ); 

?>
