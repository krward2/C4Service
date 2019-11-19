<?php
//Kenneth Ward

//Stores json strings in files
function storeState($file, $jsonString){
    return file_put_contents($file, $jsonString); 
}
?>