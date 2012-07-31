<?php
namespace BFOS\TwigExtensionsBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormBuilder;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\Form\FormInterface;
use BFOS\TwigExtensionsBundle\Form\DataTransformer\EntitiesToArrayAjaxTransformer;
use BFOS\TwigExtensionsBundle\Form\ChoiceList\EntityAjaxChoiceList;
//use Symfony\Bridge\Doctrine\Form\EventListener\MergeCollectionListener;

class FCBKcompleteType extends AbstractType
{
    private $container;

    private $registry;

    function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        $this->registry = $container->get('doctrine');
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if(!$options['url']){
            throw new \InvalidArgumentException('url option must be set.');
        }

        $builder
            ->setAttribute('choice_list', $options['choice_list'])
            ->setAttribute('url', $options['url'])
            ->setAttribute('fcbkcomplete_options', $options['fcbkcomplete_options'])
        ;

        if($options['multiple']){
            $builder
                ->prependClientTransformer(new EntitiesToArrayAjaxTransformer($options['choice_list']));
        } else {

        }

    }

    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $fcbkcomplete_options = $form->hasAttribute('fcbkcomplete_options') ? $form->getAttribute('fcbkcomplete_options') : array();

        $fcbkcomplete_options['json_url'] = $form->getAttribute('url');

        if($form->getData()){
            $valuesWithLabels = $form->getAttribute('choice_list')->getChoices($form->getData());
            $view->vars['valueWithLabels'] = $valuesWithLabels;
        } else {
            $view->vars['valueWithLabels'] = array();
            $fcbkcomplete_options['maxitems'] = 1;
            $fcbkcomplete_options['maxshownitems'] = 1;
        }
        $view->vars['fcbkcomplete_options'] = json_encode($fcbkcomplete_options);
    }

    public function getDefaultOptions(array $options)
    {
        $defaultOptions = array(
            'em'                   => null,
            'class'                => null,
            'property'             => null,
            'query_builder'        => null,
            'url'                  => null,
            'fcbkcomplete_options' => null,
            'multiple'             => true,
        );

        $options = array_replace($defaultOptions, $options);

        if (!isset($options['choice_list'])) {
            $defaultOptions['choice_list'] = new EntityAjaxChoiceList(
                $this->registry->getEntityManager($options['em']),
                $options['class'],
                $options['property'],
                $options['query_builder']
            );
        }

        return $defaultOptions;
    }


    public function getParent()
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
