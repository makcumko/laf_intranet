<?php

class ImageResizer {

    static function makeResizedPhoto($src, $dst, $w_dst, $h_dst, $crop = false, $enlarge = false) {
        ini_set('memory_limit', '100M');   //  handle large images
        @unlink($dst);
        list($w_src, $h_src, $type) = getimagesize($src);

        switch ($type) {
            case 1:   //   gif -> jpg
                $img_src = imagecreatefromgif($src);
                break;
            case 2:   //   jpeg -> jpg
                $img_src = imagecreatefromjpeg($src);
                break;
            case 3:  //   png -> jpg
                $img_src = imagecreatefrompng($src);
                break;
        }

        // avoid enlarging
        if (!$enlarge && $h_src < $h_dst && $w_src < $w_dst) {
            $h_dst = $h_src;
            $w_dst = $w_src;
        }

        $ratioSrc = $w_src/$h_src;
        $ratioDst = $w_dst/$h_dst;

        if ($crop) {
            if ($ratioSrc >= $ratioDst) {
                // if
                $w_span = $h_src*$ratioDst;
                $x_span = ($w_src-$w_span)/2;
                $h_span = $h_src;
                $y_span = 0;
            } else {
                $h_span = $w_src/$ratioDst;
                $y_span = ($h_src-$h_span)/2;
                $w_span = $w_src;
                $x_span = 0;
            }

        } else {
            $w_span = $w_src;
            $h_span = $h_src;
            $x_span = 0;
            $y_span = 0;
            if ($ratioSrc >= $ratioDst) {
                $h_dst = $w_dst / $ratioSrc;
            } else {
                $w_dst = $h_dst * $ratioSrc;
            }
        }

        $img_dst = imagecreatetruecolor($w_dst, $h_dst);

        imagecopyresampled($img_dst, $img_src, 0, 0, $x_span, $y_span, $w_dst, $h_dst, $w_span, $h_span);

        imagejpeg($img_dst, $dst);

        imagedestroy($img_src);
        imagedestroy($img_dst);
    }
}
?>