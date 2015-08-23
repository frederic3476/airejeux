<?php

namespace Applisun\AireJeuxBundle\Service;

class ImageManager {

    private $pathImage = __DIR__ . '/../../../../web/uploads/aires/';

    public function createImageFromOriginal($filename, $arrayWidth) {
        $extension = pathinfo($filename, PATHINFO_EXTENSION);
        switch ($extension) {
            case 'jpg':
            case 'jpeg':
                $im = imagecreatefromjpeg($this->pathImage . $filename);
                break;
            case 'gif':
                $im = imagecreatefromgif($this->pathImage . $filename);
                break;
            case 'png':
                $im = imagecreatefrompng($this->pathImage . $filename);
                break;
        }

        $ox = imagesx($im);
        $oy = imagesy($im);

        foreach ($arrayWidth as $size) {
            if ($ox > $size['w']) {
                $nx = $size['w'];
                $ny = floor($oy * ($size['w'] / $ox));

                $nm = imagecreatetruecolor($nx, $ny);

                imagecopyresized($nm, $im, 0, 0, 0, 0, $nx, $ny, $ox, $oy);
                
                if ($ny > $size['h']){
                    $nm = imagecrop($nm, array('x' =>0 , 'y' => 0, 'width' => $nx, 'height'=> $size['h']));
                }
                
                imagejpeg($nm, $this->pathImage . $size['w'] . '-' . $filename);
            }
        }
    }

}