<?php

namespace Applisun\AireJeuxBundle\Service;

class ImageManager {

    private $pathImage = '/../../../../web/uploads/aires/';

    public function createImageFromOriginal($filename, $arrayWidth) {
        $extension = pathinfo($filename, PATHINFO_EXTENSION);
        switch ($extension) {
            case 'jpg':
            case 'jpeg':
                $im = imagecreatefromjpeg(__DIR__.$this->pathImage . $filename);
                break;
            case 'gif':
                $im = imagecreatefromgif(__DIR__.$this->pathImage . $filename);
                break;
            case 'png':
                $im = imagecreatefrompng(__DIR__.$this->pathImage . $filename);
                break;
        }
        
        if (isset($im)){
            $ox = imagesx($im);
            $oy = imagesy($im);

            foreach ($arrayWidth as $size) {
                    $nx = $size['w'];
                    $ny = floor($oy * ($size['w'] / $ox));

                    if ($ny > $size['h']){
                        $ny = $size['h'];
                    }

                    $nm = imagecreatetruecolor($nx, $ny);
                    imagecopyresized($nm, $im, 0, 0, 0, 0, $nx, $ny, $ox, $oy);
                    imagejpeg($nm, __DIR__.$this->pathImage . $size['w'] . '-' . $filename);                
            }
        }
    }

}
