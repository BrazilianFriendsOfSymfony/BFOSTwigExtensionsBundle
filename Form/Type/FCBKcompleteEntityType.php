<?php
namespace BFOS\TwigExtensionsBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use BFOS\TwigExtensionsBundle\Form\DataTransformer\EntityToIdAjaxTransformer;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormBuilder;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\Form\FormInterface;
use BFOS\TwigExtensionsBundle\Form\DataTransformer\EntitiesToArrayAjaxTransformer;
use BFOS\TwigExtensionsBundle\Form\ChoiceList\EntityAjaxChoiceList;
use Doctrine\ORM\EntityManager;

class FCBKcompleteEntityType extends AbstractType
{
    private $container;

    /**
     * @var EntityManager $em
     */
    private $em;

    function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        /**
         * @var \Doctrine\Bundle\DoctrineBundle\Registry $registry
         */
        $registry = $container->get('doctrine');
        $this->em = $registry->getEntityManager();
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->addViewTransformer(new EntitiesToArrayAjaxTransformer($options['class'], $this->em, $options['multiple']));
    }

    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $fcbkcomplete_options = isset($options['fcbkcomplete_options']) ? $options['fcbkcomplete_options'] : array();

        $fcbkcomplete_options['json_url'] = $options['url'];

        if($form->getData()){
            $valuesWithLabels = array();
            foreach($form->getData() as $value){
                $valuesWithLabels[$value->getId()] = (string) $value;
            }
            $view->vars['valueWithLabels'] = $valuesWithLabels;
        } else {
            $view->vars['valueWithLabels'] = array();
//            $fcbkcomplete_options['maxitems'] = 1;
//            $fcbkcomplete_options['maxshownitems'] = 1;
        }
        $view->vars['fcbkcomplete_options'] = json_encode($fcbkcomplete_options);
//        $view->vars['full_name'] = $view->vars['full_name'].'[]';
        $view->vars['multiple'] = $options['multiple'];

    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver
            ->setRequired(array('url', 'class'))
            ->setOptional(array('fcbkcomplete_options'))
            ->setDefaults(array('compound' => false, 'multiple'=>true));
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
        return 'bfos_fcbkcomplete_entity';
    }


}
