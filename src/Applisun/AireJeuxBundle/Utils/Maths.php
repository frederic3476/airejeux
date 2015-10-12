<?php
namespace Applisun\AireJeuxBundle\Utils;
class Maths
{
    static public function distance($lat1, $lng1, $lat2, $lng2) 
	{
		//rayon de la terre
		$r = 6366;
		$lat1 = deg2rad($lat1);
		$lat2 = deg2rad($lat2);
		$lng1 = deg2rad($lng1);
		$lng2 = deg2rad($lng2); 
 
		//calcul précis
		$dp= 2 * asin(sqrt(pow (sin(($lat1-$lat2)/2) , 2) + cos($lat1)*cos($lat2)* pow( sin(($lng1-$lng2)/2) , 2)));
 
		//sortie en km
		$d = $dp * $r; 
 
		return $d;
	}    
}

