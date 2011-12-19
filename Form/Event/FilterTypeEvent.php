<?php
/**
 * Created by JetBrains PhpStorm.
 * User: paulo
 * Date: 12/15/11
 * Time: 8:29 PM
 * To change this template use File | Settings | File Templates.
 */
namespace BFOS\TwigExtensionsBundle\Form\Event;

use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\Form\FormTypeInterface;

class FilterTypeEvent extends Event
{
    protected $type;
    protected $configuration = array();

    public function __construct(FormTypeInterface $type)
    {
        $this->type = $type;
    }

    public function getType()
    {
        return $this->type;
    }

    public function setConfiguration($configuration)
    {
        $this->configuration = $configuration;
    }

    public function getConfiguration()
    {
        return $this->configuration;
    }

}