<?php
namespace BFOS\TwigExtensionsBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\Form\FormInterface;

class DateTimePickerType extends AbstractType
{
    private $container;


    function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function buildForm(FormBuilder $builder, array $options)
    {

        $builder
            ->setAttribute('type', $options['type'])
            ->setAttribute('locale', $options['locale'])
        ;

    }

    public function buildView(FormView $view, FormInterface $form)
    {
//        $fcbkcomplete_options = $form->hasAttribute('fcbkcomplete_options') ? $form->getAttribute('fcbkcomplete_options') : array();

//        $fcbkcomplete_options['json_url'] = $form->getAttribute('url');

        $view->set('widget_block_parent', $form->getAttribute('type') . '_widget');
        $view->set('type', $form->getAttribute('type'));
        $view->set('locale', $form->getAttribute('locale'));
    }

    public function getDefaultOptions(array $options)
    {
        $defaultOptions = array(
            'type'                 => 'date',
            'timepicker_options' => null,
            'locale' => $this->container->get('session')->getLocale()
        );

        $options = array_replace($defaultOptions, $options);
        return $options;
    }


    public function getParent(array $options)
    {
        return $options['type'];
    }


    /**
     * Returns the name of this type.
     *
     * @return string The name of this type
     */
    function getName()
    {
        return 'bfos_datetime';
    }


}
