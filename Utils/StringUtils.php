<?php
namespace BFOS\TwigExtensionsBundle\Utils;

class StringUtils
{

    static public function startsWith($haystack, $needle)
    {
        $length = strlen($needle);
        return (substr($haystack, 0, $length) === $needle);
    }

    static public function endsWith($haystack, $needle)
    {
        $length = strlen($needle);
        if ($length == 0) {
            return true;
        }

        $start  = $length * -1; //negative
        return (substr($haystack, $start) === $needle);
    }

}
