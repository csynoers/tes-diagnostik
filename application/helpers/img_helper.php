<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
function resize($pixel,$path,$name)
{
    $resize['image_library']     = 'gd2';
    $resize['new_image']         = $path .$pixel .'/' .$name;
    $resize['source_image']      = $path .$name;
    $resize['maintain_ratio']    = TRUE;
    $resize['width']             = $pixel;
    $resize['height']            = $pixel;
    return $resize;
}