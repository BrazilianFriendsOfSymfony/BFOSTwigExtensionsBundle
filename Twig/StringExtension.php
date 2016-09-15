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

use Twig_SimpleFilter;
use BFOS\TwigExtensionsBundle\Utils\StringUtils;

class StringExtension extends \Twig_Extension
{
    /**
     * {@inheritdoc}
     */
    public function getFilters()
    {
        return array(
            new Twig_SimpleFilter('bfos_align_right', [$this, 'align_right']),
            new Twig_SimpleFilter('bfos_align_left', [$this, 'align_left']),
            new Twig_SimpleFilter('bfos_replace', [$this, 'replace']),
        );
    }

    public function getFunctions()
    {
        return array(
            new Twig_SimpleFunction('str_starts_with', [$this, 'startsWith']),
            new Twig_SimpleFunction('str_ends_with', [$this, 'endsWith']),
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
        return StringUtils::startsWith($haystack, $needle);
    }

    public function endsWith($haystack, $needle)
    {
        return StringUtils::endsWith($haystack, $needle);
    }


}
