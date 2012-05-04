<?php
namespace BFOS\TwigExtensionsBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\Form\FormInterface;

class FCBKcompleteType extends AbstractType
{
    private $container;

    function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function buildForm(FormBuilder $builder, array $options)
    {
        if(!$options['value_callback'] && !is_callable($options['value_callback'])){
            throw new \InvalidArgumentException('value_callback option must be set and callable.');
        }
        if(!$options['url']){
            throw new \InvalidArgumentException('url option must be set.');
        }

        $builder
            ->setAttribute('value_callback', $options['value_callback'])
            ->setAttribute('url', $options['url'])
            ->setAttribute('fcbkcomplete_options', $options['fcbkcomplete_options'])
        ;
    }

    public function buildView(FormView $view, FormInterface $form)
    {
        $fcbkcomplete_options = $form->hasAttribute('fcbkcomplete_options') ? $form->getAttribute('fcbkcomplete_options') : array();
        /*foreach ($fcbkcomplete_options as $key => $value) {
            $fcbkcomplete_options[$key] = (string) $value;
        }*/

        $fcbkcomplete_options['json_url'] = $form->getAttribute('url');
        $view->set('fcbkcomplete_options', json_encode($fcbkcomplete_options));

        if($form->getData()){
            $valuesWithLabels = $form->getAttribute('value_callback') ? call_user_func($form->getAttribute('value_callback'), $form->getData()) : $form->getData();
            $view->set('valueWithLabels', $valuesWithLabels);
        } else {
            $view->set('valueWithLabels', array());
        }
    }



    public function getDefaultOptions(array $options)
    {
        $options['value_callback'] = null;
        $options['url'] = null;
        $options['fcbkcomplete_options'] = array();

        return $options;
    }


    public function getParent(array $options)
    {
        return 'field';
    }


    /**
     * Returns the name of this type.
     *
     * @return string The name of this type
     */
    function getName()
    {
        return 'bfos_fcbkcomplete';
    }


}
