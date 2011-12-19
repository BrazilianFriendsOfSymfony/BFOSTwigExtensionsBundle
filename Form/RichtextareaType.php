<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace BFOS\TwigExtensionsBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormView;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormBuilder;

class RichtextareaType extends AbstractType
{
    private $dispatcher = null;

    const CONFIGURE_CKEDITOR = 'form_type.configure_ckeditor';

    public function setEventDispatcher($dispatcher)
    {
        $this->dispatcher = $dispatcher;
        //        if ($dispatcher instanceof TraceableEventDispatcherInterface) {
        //        }
    }

    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder->setAttribute('script_path', $options['script_path']);
        $builder->setAttribute('base_path', $options['base_path']);
        parent::buildForm($builder, $options);
    }

    /**
     * {@inheritdoc}
     */
    public function buildView(FormView $view, FormInterface $form)
    {
        $view->set('pattern', null);
        $view->set('base_path', $form->getAttribute('base_path'));
        $view->set('script_path', $form->getAttribute('script_path'));

        if (!is_null($this->dispatcher)) {
            // create the FilterOrderEvent and dispatch it
            $event = new \BFOS\TwigExtensionsBundle\Form\Event\FilterTypeEvent($this);
            $this->dispatcher->dispatch(RichtextareaType::CONFIGURE_CKEDITOR, $event);
            $config = $event->getConfiguration();
            if(isset($config['toolbar']) && is_string($config['toolbar']) && isset($config['toolbars'][$config['toolbar']])){
                $config['toolbar'] = $config['toolbars'][$config['toolbar']];
                unset($config['toolbars']);
            }
            $view->set('ckeditor_options', $this->jsEncode($config));
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getParent(array $options)
    {
        return 'text';
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'richtextarea';
    }

    /**
     * {@inheritdoc}
     */
    public function getDefaultOptions(array $options)
    {
        return array(
            'base_path'           => '/bundles/bfostwigextensions/vendor/ckeditor/3.6.2/',
            'script_path'         => '/bundles/bfostwigextensions/vendor/ckeditor/3.6.2/ckeditor.js',
        );
    }

    /**
     * This little function provides a basic JSON support.
     *
     * @param mixed $val
     *
     * @return string
     */
    private function jsEncode($val)
    {
        if (is_null($val)) {
            return 'null';
        }
        if (is_bool($val)) {
            return $val ? 'true' : 'false';
        }
        if (is_int($val)) {
            return $val;
        }
        if (is_float($val)) {
            return str_replace(',', '.', $val);
        }
        if (is_array($val) || is_object($val)) {
            if (is_array($val) && (array_keys($val) === range(0, count($val) - 1))) {
                return '[' . implode(',', array_map(array($this, 'jsEncode'), $val)) . ']';
            }
            $temp = array();
            foreach ($val as $k => $v) {
                $temp[] = $this->jsEncode("{$k}") . ':' . $this->jsEncode($v);
            }
            return '{' . implode(',', $temp) . '}';
        }
        // String otherwise
        if (strpos($val, '@@') === 0)
            return substr($val, 2);
        if (strtoupper(substr($val, 0, 9)) == 'CKEDITOR.')
            return $val;

        return '"' . str_replace(array("\\", "/", "\n", "\t", "\r", "\x08", "\x0c", '"'), array('\\\\', '\\/', '\\n', '\\t', '\\r', '\\b', '\\f', '\"'), $val) . '"';
    }

}
