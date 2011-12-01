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
            'bfos_format_bytes' => new \Twig_Filter_Method($this, 'format_bytes'),
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

    public function dateage($date, $timezone = null, $domain = "ucotwigextensions", $locale = null)
    {

        if (!$date instanceof \DateTime) {
            if (ctype_digit((string)$date)) {
                $date = new \DateTime('@' . $date);
                $date->setTimezone(new \DateTimeZone(date_default_timezone_get()));
            } else {
                $date = new \DateTime($date);
            }
        }

        $now = new \DateTime("now");

        if (null !== $timezone) {
            if (!$timezone instanceof \DateTimeZone) {
                $timezone = new \DateTimeZone($timezone);
            }

            $date->setTimezone($timezone);
            $now->setTimezone($timezone);
        }

        // from http://es2.php.net/manual/en/function.ngettext.php
        $interval = $now->diff($date);

        $format = array();
        if ($interval->y !== 0) {
            $format[] = $this->getTranslator()->transChoice(
                'date.year',
                $interval->y,
                array('%year%' => $interval->y),
                $domain,
                $locale
            );
        }
        if ($interval->m !== 0) {
            $format[] = $this->getTranslator()->transChoice(
                'date.month',
                $interval->m,
                array('%month%' => $interval->m),
                $domain,
                $locale
            );
        }
        if ($interval->d !== 0) {
            $format[] = $this->getTranslator()->transChoice(
                'date.day',
                $interval->d,
                array('%day%' => $interval->d),
                $domain,
                $locale
            );
        }
        if ($interval->h !== 0) {
            $format[] = $this->getTranslator()->transChoice(
                'date.hour',
                $interval->h,
                array('%hour%' => $interval->h),
                $domain,
                $locale
            );
        }
        if ($interval->i !== 0) {
            $format[] = $this->getTranslator()->transChoice(
                'date.minute',
                $interval->i,
                array('%minute%' => $interval->i),
                $domain,
                $locale
            );
        }
        if ($interval->s !== 0) {
            if (!count($format)) {
                return $this->getTranslator()->trans(
                    'date.now',
                    array(),
                    $domain,
                    $locale
                );
            } else {
                $format[] = $this->getTranslator()->transChoice(
                    'date.second',
                    $interval->i,
                    array('%second%' => $interval->i),
                    $domain,
                    $locale
                );
            }
        }

        // We use the two biggest parts
        if (count($format) > 1) {
            $format = array_shift($format) .
                $this->getTranslator()->trans(
                    "date.and",
                    array(),
                    $domain,
                    $locale
                ) .
                array_shift($format);
        } else {
            $format = array_pop($format);
        }

        return $format;
    }

}

