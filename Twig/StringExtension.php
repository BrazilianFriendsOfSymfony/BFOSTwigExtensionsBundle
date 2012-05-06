<?php
/*
* This file is part of the Brazilian Friends of Symfony package.
*
* (c) Paulo Ribeiro <paulo@duocriativa.com.br>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*
* @author Paulo Ribeiro <paulo@duocriativa.com.br>
*/

namespace BFOS\TwigExtensionsBundle\Twig;

class StringExtension extends \Twig_Extension
{
    /**
     * {@inheritdoc}
     */
    public function getFilters()
    {
        return array(
            'bfos_align_right' => new \Twig_Filter_Method($this, 'align_right'),
            'bfos_align_left' => new \Twig_Filter_Method($this, 'align_left'),
            'bfos_replace' => new \Twig_Filter_Method($this, 'replace'),
        );
    }

    public function getFunctions()
    {
        return array(
            'str_starts_with' => new \Twig_Function_Method($this, 'startsWith'),
            'str_ends_with' => new \Twig_Function_Method($this, 'endsWith'),
        );
    }

    /**
     * Returns the name of the extension.
     *
     * @return string The extension name
     */
    public function getName()
    {
        return 'bfos_string';
    }

    public function align_right($str, $cols = 10)
    {
       return sprintf("%{$cols}s", $str); // right-justification with spaces
    }

    public function align_left($str, $cols = 10)
    {
       return sprintf("%-{$cols}s", $str); // left-justification with spaces
    }

    public function replace($subject, $search, $replace, $count = null){
        if(is_null($count)){
            return str_replace($search, $replace, $subject);
        } else {
            return str_replace($search, $replace, $subject, $count);
        }
    }

    public function startsWith($haystack, $needle)
    {
        $length = strlen($needle);
        return (substr($haystack, 0, $length) === $needle);
    }

    public function endsWith($haystack, $needle)
    {
        $length = strlen($needle);
        if ($length == 0) {
            return true;
        }

        $start  = $length * -1; //negative
        return (substr($haystack, $start) === $needle);
    }


}

