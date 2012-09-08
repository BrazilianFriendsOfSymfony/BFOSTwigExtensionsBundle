<?php
namespace BFOS\TwigExtensionsBundle\Utils;

class DateUtils
{

    /**
     * Returns the \DateTime object of the first day of the month.
     *
     * @static
     * @param null|int $month
     * @param null|int $year
     * @return \DateTime
     */
    static public function firstDayOfMonth($month = null, $year = null)
    {
        if(is_null($month)){
            $month = date('m');
        }
        if(is_null($year)){
            $year = date('Y');
        }
        $date = new \DateTime($year.'-'.$month.'-01');
        return $date;
    }

    /**
     * Returns the \DateTime object of the last day of the month.
     *
     * @static
     * @param null|int $month
     * @param null|int $year
     * @return \DateTime
     */
    static public function lastDayOfMonth($month = null, $year = null)
    {
        $date = self::firstDayOfMonth($month, $year);
        $date->add(new \DateInterval('P1M'));
        $date->sub(new \DateInterval('P1D'));
        $date = new \DateTime($date->format('Y-m-d') . ' 23:59:59');
        return $date;
    }


}
