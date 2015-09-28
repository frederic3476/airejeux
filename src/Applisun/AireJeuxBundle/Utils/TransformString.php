<?php
namespace Applisun\AireJeuxBundle\Utils;
class TransformString
{
    static public function slugify($text)
    {
        // replace non letter or digits by -
        $text = preg_replace('#[^\\pL\d]+#u', '-', $text);
        // trim
        $text = trim($text, '-');
        // transliterate
        if (function_exists('iconv'))
        {
            $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
        }
        // lowercase
        $text = strtolower($text);
        // remove unwanted characters
        $text = preg_replace('#[^-\w]+#', '', $text);
        if (empty($text))
        {
            return 'n-a';
        }
        return $text;
    }
    
    static function generatePass( $nbChar = 8 ) {
            $characters = '023456789abcdefghijkmnopqrstuvwxyzABCDEFGHJKLMNOPQRSTUVWXYZ#!$';
            $specials = '#!?$%&*';

            $firstPart = substr(str_shuffle($characters), 0, $nbChar - 1);
            $lastPart = substr(str_shuffle($specials), 0, 1);

            return str_shuffle($firstPart . $lastPart);
        }
}
