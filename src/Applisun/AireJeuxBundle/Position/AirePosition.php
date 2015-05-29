<?php

namespace Applisun\AireJeuxBundle\Position;

use Applisun\AireJeuxBundle\Position\AbstractPosition;
use Applisun\AireJeuxBundle\Position\PositionInterface;


class AirePosition extends AbstractPosition implements PositionInterface{
    
    public function render() {
        $js = "";
        $js.= $this->renderStart();
        $js.= $this->renderMarker();
        $js.= $this->renderEnd();
        
        return trim($js);
    }
    
}
