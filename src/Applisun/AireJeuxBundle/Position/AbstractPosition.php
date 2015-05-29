<?php

namespace Applisun\AireJeuxBundle\Position;

use Applisun\AireJeuxBundle\Position\PositionInterface;

abstract class AbstractPosition {
    
    protected $center = array('lat' => 0, 'lng' => 0);
    protected $zoom;
    protected $elementId;
    protected $className;
    protected $markers = array();
    protected $icon;
    
    
    
    abstract public function render();
    
    public function setCenter($center = array()){
        $this->center = $center;
    }
    
    public function getCenter(){
        return $this->center;
    }
    
    public function setZoom($zoom){
        $this->zoom = $zoom;
    }
    
    public function getZoom(){
        return $this->zoom;
    }
    
    public function setElementId($elementId){
        $this->elementId = $elementId;
    }
    
    public function getElementId(){
        return $this->elementId;
    }
    
    public function setClassName($className){
        $this->className = $className;
    }
    
    public function getClassName(){
        return $this->className;
    }
    
    public function setIcon($icon){
        $this->icon = $icon;
    }
    
    public function getIcon(){
        return $this->icon;
    }
    
    public function addMarker($marker = array())
    {
        $this->markers[] = $marker;
    }
    
    /**
     *
     * @return string
     */
    protected function renderStart()
    {
        return "function initialize() {\n "
        . "var ".$this->className." = new google.maps.Map(document.getElementById('".$this->elementId."'),
                    ".$this->renderOptions().");\n";
        
    }
    
    /**
     * @return string
     */
    public function renderCenter()
    {
        if ($this->center) 
            return "center: {lat:".$this->center['lat'].", lng: ".$this->center['lng']."},";
        return "";
    }
    
    /**
     * @return string
     */
    public function renderZoom()
    {
        if ($this->zoom)        
            return "zoom: ".$this->zoom.",";
        return "";
    }
    
    /**
     * @return string
     */
    public function renderOptions()
    {
        return "{".$this->renderCenter()."\n".$this->renderZoom()."}\n";
    }
    
    /**
     * @return string
     */
    public function renderMarker()
    {
        $strMarker="";
        
        foreach ($this->markers as $marker)
        {
            $strMarker .="new google.maps.Marker({ \n
                        position: new google.maps.LatLng(".$marker['lat'].",".$marker['lng']."),\n
                        map: ".$this->className.",\n
                        title: '".$marker['name']."',\n
                        icon: '".$this->icon."'\n
                      });\n";                        
        }
        return $strMarker;
    }
    
    /**
     *
     * @return string
     */
    protected function renderEnd()
    {
        return "} \n google.maps.event.addDomListener(window, 'load', initialize);";
        
    }
}
