<?php

namespace Applisun\AireJeuxBundle\Service;

class ImageManager {

    private $pathImage = '/../../../../web/uploads/aires/';
    private $pathAvatar = '/../../../../web/uploads/avatars/';

    public function createImageFromOriginal($filename, $arrayWidth, $pathImage = true) {
        if ($pathImage){
            $path = $this->pathImage;
        }
        else{
          $path = $this->pathAvatar;  
        }
        $extension = pathinfo($filename, PATHINFO_EXTENSION);
        switch ($extension) {
            case 'jpg':
            case 'jpeg':
                $im = imagecreatefromjpeg(__DIR__.$path . $filename);
                break;
            case 'gif':
                $im = imagecreatefromgif(__DIR__.$path . $filename);
                break;
            case 'png':
                $im = imagecreatefrompng(__DIR__.$path . $filename);
                break;
        }
        
        if (isset($im)){
            $ox = imagesx($im);
            $oy = imagesy($im);

            foreach ($arrayWidth as $size) {
                    $nx = $ox;
                    $ny = $oy;
                    //ratio image origin
                    $r = $ox/$oy;
                    $pt_x = $pt_y = 0;
                
                    //ratio new image
                    $new_r = $size['w']/$size['h'];
                    
                    //pas assez hauteur 
                    if ($r > $new_r){
                        $ny=$oy;
                        $nx = $oy*$new_r;
                        $pt_x = ($ox-$nx)/2;
                    }
                    else{
                        //trop haut
                        $nx = $ox;
                       //new height
                        $ny = $ox/$new_r;
                        $pt_y = ($oy-$ny)/2;
                    }
                    
                    //new image cropped
                    $nmc = imagecreatetruecolor($nx, $ny);
                    imagecopy($nmc, $im, 0, 0, $pt_x, $pt_y, $nx, $ny);
                    
                    //final image
                    $nm = imagecreatetruecolor($size['w'], $size['h']);
                    imagecopyresized($nm, $nmc, 0, 0, 0, 0, $size['w'], $size['h'], $nx, $ny);
                    
                    //insert copyright
                    if ($size['copyright']){
                      //$copyright = imagecreatefrompng(__DIR__.'/../../../../web/bundles/applisunairejeux/images/copyright.png');
                      //var_dump($copyright); exit;
                      //imagecopymerge($nm, $copyright, $size['w']-120, $size['h']-40, 0, 0, $size['w'], $size['h'], 100);
                    }
                    
                    switch ($extension) {
                        case 'jpg':
                        case 'jpeg':
                            imagejpeg($nm, __DIR__.$path . $size['w'] . '-' . $filename); 
                            break;
                        case 'gif':
                            imagegif($nm, __DIR__.$path . $size['w'] . '-' . $filename); 
                            break;
                        case 'png':
                            imagepng($nm, __DIR__.$path . $size['w'] . '-' . $filename); 
                            break;
                    }
                    imagedestroy($nmc);
                    imagedestroy($nm);
            }
        }
    }

}
