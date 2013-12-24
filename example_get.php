<?php
 $attachment_location = $_SERVER["DOCUMENT_ROOT"] . "/garbage.png";
        if (file_exists($attachment_location)) {

            header($_SERVER["SERVER_PROTOCOL"] . " 200 OK");
            header("Cache-Control: public"); // needed for i.e.
            header("Content-Type: image/png");
            header("Content-Transfer-Encoding: Binary");
            header("Content-Length:".filesize($attachment_location));
            header("Content-Disposition: attachment; filename=garbage.png");
            readfile($attachment_location);
            die();        
        } else {
            die("Error: File not found.");
        } 
?>
