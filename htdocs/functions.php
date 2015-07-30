<?php
global $toReturn, $target_file, $target_dir, $tmp_name;
function checkValid($file){
    $name = $file["name"];
    //echo "var dump of file: " .
    //var_dump($file);

    global $toReturn, $target_file, $target_dir, $tmp_name;

    $toReturn = "";
    $space = "<br>";

    $tmp_name = $file['tmp_name'];
    $target_dir = "uploads/";
    $target_file = $target_dir . basename($name);
    $fileType = pathinfo($target_file, PATHINFO_EXTENSION);
    $check = filesize($tmp_name);

    $toReturn .= "File name: " . basename($name). $space;
    $toReturn .= "File is empty: ";
    if ($check == false) {
        $toReturn .= "True";
        $uploadOk = 0;
    } else {
        $toReturn .= "False; Size: ".$check;
        $uploadOk = 1;
    }

    // Check if file already exists
    $toReturn .= $space. "File already exists: "; //TODO space
    if (file_exists($target_file)) {

        $toReturn .= "True; adding timestamp";
        $date = date('U');
        //unlink($target_file);
        $target_file = $target_dir . pathinfo($name)['filename'] . $date .'.'. $fileType;
        //
    } else {
        $toReturn .= "False";
    }

    // Check file size
/*    $toReturn .= $space."File size too large: ";
    if ($name > 8000000) {
        $toReturn .= "True";
        $uploadOk = 0;
    } else {
        $toReturn .= "False";
    }*/

    // Allow certain file formats
    $toReturn .= $space."File is NOT .txt or .xlsm: ";
    if ($fileType != "txt" && $fileType != "xlsm" && $fileType != "tsv") {
        $toReturn .= "True";
        $uploadOk = 0;
    } else {
        $toReturn .= "False";
    }


    $toReturn .= $space."File errors: " . ($uploadOk == 0 ? 'True' : 'False') .$space;
    //$toReturn .= "<br>";

    return ($uploadOk == 1 ? True : False);
}



?>