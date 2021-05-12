<?php



header ("Content-type: image/png");
$im = @ImageCreate (100, 30);

$background_color = ImageColorAllocate ($im, 200, 200, 200);
$text_color = ImageColorAllocate ($im, 233, 14, 91);
ImageString ($im, 4, 20, 6, "1sa5d6", $text_color);
ImagePNG ($im);


?>