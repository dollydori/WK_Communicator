<?php
function save_image($inPath, $outPath) {
//Download images from remote server
    $in = fopen($inPath, "rb");
    $out = fopen($outPath, "wb");
    while( $chunk = fread($in, 8192) ) {
        fwrite($out, $chunk, 8192);
    }
    fclose($in);
    fclose($out);
}

define(UNIX_TIMESTAMP, date('U'));
$target = 'upload_img/' . md5(UNIX_TIMESTAMP) . '_' . rand(0, 9999) . '.' . array_pop( split('\.', $_REQUEST[url]) );
echo $target;
save_image($_REQUEST[url], $target);
?>
