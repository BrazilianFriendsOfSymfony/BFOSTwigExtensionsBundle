<?php
namespace BFOS\TwigExtensionsBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
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

    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder
            ->setAttribute('locale', $options['locale'])
        ;

    }

    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $view->set('widget_block_parent', 'date_widget');
        $view->set('locale', $form->getAttribute('locale'));
    }

    public function getDefaultOptions(array $options)
    {
        $defaultOptions = array(
            'timepicker_options' => null,
            'locale' => $this->container->get('request')->getLocale()
        );

        $options = array_replace($defaultOptions, $options);
        return $options;
    }


    public function getParent()
    {
        return 'datetime';
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
