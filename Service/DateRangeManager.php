<?php

namespace Shtumi\UsefulBundle\Service;

use Shtumi\UsefulBundle\Model\DateRange;

class DateRangeManager
{
    private $date_format;
    private $default_interval;

    public function __construct($parameters)
    {
        $this->date_format      = $parameters['date_format'];
        $this->default_interval = $parameters['default_interval'];
    }

    public function create($date_format = null)
    {
        $date_format = $date_format ? $date_format : $this->date_format;

        $dateRange = new DateRange($date_format);

        return $dateRange;
    }

    public function createToDate($dateEnd="now", $date_format = null, $date_interval=null)
    {

        $date_format = $date_format ? $date_format : $this->date_format;
        $date_interval = $date_interval ? $date_interval : $this->default_interval;

        $dateRange = new DateRange($date_format);

        if ($dateEnd == "now")
            $dateEnd = new \DateTime;
        $dateRange->createToDate($dateEnd, $date_interval);

        return $dateRange;

    }
}
