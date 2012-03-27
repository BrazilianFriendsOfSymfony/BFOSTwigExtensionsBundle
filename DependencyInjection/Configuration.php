<?php

namespace BFOS\TwigExtensionsBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('bfos_twig_extensions');

        // Here you should define the parameters that are allowed to
        // configure your bundle. See the documentation linked above for
        // more information on that topic.

        $rootNode->children()
            ->arrayNode('ckeditor_options')
                ->useAttributeAsKey('name')
                ->defaultValue(array(
                    'toolbars'=>array(
                            'Default' => array(
                              array('Format', 'Bold', 'Italic', 'Blockquote'),
                              array('NumberedList','BulletedList','-','Link','Unlink','Anchor','-','Table',
                            '-','FitWindow', 'Source')),
                            'Main' => array(
                                array('Format', 'Bold', 'Italic', 'Blockquote'),
                                array('NumberedList','BulletedList','-','Link','Unlink','Anchor','-','Table','-','FitWindow','Source')),
                            'MainExtra' => array(
                                array('Format', 'FontSize', 'Bold', 'Italic', 'Strike', 'Blockquote', 'TextColor', 'BGColor'),
                                array('NumberedList','BulletedList','-','Link','Unlink','Anchor','-','Table','-','FitWindow','Source')),
                            'Sidebar' => array(
                                array('Format', 'Bold', 'Italic', 'Blockquote'),
                                array('NumberedList','BulletedList','-','Link','Unlink','Anchor'),
                                array('Source')),
                            'Media' => array(
                                array('Bold', 'Italic', '-','Link', 'Unlink','Anchor', '-', 'Source'))),
                    'toolbar' => 'Sidebar',
                    'format_tags'=>'p;h3;h4;h5;h6;pre',
                    'skin'=>'kama',
                    'uiColor' => '#e1e1e1',
                    'width' => '100%',
                    'height' => '225',

                    ))
                ->prototype('array')->end()
            ->end();

//        $rootNode->append($this->getCkeditorOptions());

        return $treeBuilder;
    }

    private function getCkeditorOptions(){
        $treeBuilder = new TreeBuilder();
        $node = $treeBuilder->root('ckeditor_options');

        $node
            ->useAttributeAsKey('name')
            ->prototype('array')
            ->children()
            ->end()
            ->defaultValue(array(
                'toolbars'=>array(
                    'Default' => array(
                        array('Format', 'Bold', 'Italic', 'Blockquote'),
                        array('NumberedList','BulletedList','-','Link','Unlink','Anchor','-','Table',
                            '-','FitWindow', 'Source')),
                    'Main' => array(
                        array('Format', 'Bold', 'Italic', 'Blockquote'),
                        array('NumberedList','BulletedList','-','Link','Unlink','Anchor','-','Table','-','FitWindow','Source')),
                    'Sidebar' => array(
                        array('Format', 'Bold', 'Italic', 'Blockquote'),
                        array('NumberedList','BulletedList','-','Link','Unlink','Anchor'),
                        array('Source')),
                    'Media' => array(
                        array('Bold', 'Italic', '-','Link', 'Unlink','Anchor', '-', 'Source'))),
                'toolbar' => 'Sidebar',
                'format_tags'=>'p;h3;h4;h5;h6;pre',
                'skin'=>'kama',
                'uiColor' => '#e1e1e1',
                'width' => '100%',
                'height' => '225',

            ))
            ->end()
        ;

        return $node;
    }
}
