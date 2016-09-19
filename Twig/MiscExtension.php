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

//use Symfony\Component\Translation\TranslatorInterface;

use Twig_SimpleFilter;

class MiscExtension extends \Twig_Extension
{

//    private $translator;

    /*public function __construct(TranslatorInterface $translator) {
        $this->translator = $translator;
    }*/

    /*public function getTranslator() {
        return $this->translator;
    }*/

    /**
     * {@inheritdoc}
     */
    public function getFilters()
    {
        return array(
            new Twig_SimpleFilter('bfos_format_bytes', [$this, 'format_bytes']),
        );
    }

    /**
     * Returns the name of the extension.
     *
     * @return string The extension name
     */
    public function getName()
    {
        return 'bfos_misc';
    }

    public function format_bytes($bytes, $si = true)
    {
        $unit = $si ? 1000 : 1024;
        if ($bytes <= $unit) return $bytes . " B";
        $exp = intval((log($bytes) / log($unit)));
        $pre = ($si ? "kMGTPE" : "KMGTPE");
        $pre = $pre[$exp - 1] . ($si ? "" : "i");
        return sprintf("%.1f %sB", $bytes / pow($unit, $exp), $pre);
    }

}

