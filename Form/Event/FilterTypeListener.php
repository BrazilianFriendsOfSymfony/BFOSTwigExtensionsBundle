<?php
/**
 * Created by JetBrains PhpStorm.
 * User: paulo
 * Date: 12/15/11
 * Time: 8:41 PM
 * To change this template use File | Settings | File Templates.
 */

namespace BFOS\TwigExtensionsBundle\Form\Event;

class FilterTypeListener {

    protected $container;

    public function __construct(\Symfony\Component\DependencyInjection\Container $container){
        $this->container = $container;
    }

    public function onCkEditorConfigure(FilterTypeEvent $event){
        $type = $event->getType();
        $config = $event->getConfiguration();

        $options = $this->container->getParameter('bfos_twig_extensions.ckeditor_options');

        $event->setConfiguration(array_merge_recursive($config, $options));
    }
}