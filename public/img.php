<?php
//header( "Content-type: image/jpeg");
$PSize = filesize('1.jpg');
$picturedata = fread(fopen('1.jpg', "r"), $PSize);
//var_dump(file_get_contents('1.jpg'));
echo $picturedata;