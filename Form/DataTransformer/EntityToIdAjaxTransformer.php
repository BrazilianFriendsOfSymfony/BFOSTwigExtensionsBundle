<?php
namespace BFOS\TwigExtensionsBundle\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;
use Symfony\Component\Form\Exception\UnexpectedTypeException;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManager;
use BFOS\TwigExtensionsBundle\Form\ChoiceList\EntityAjaxChoiceList;

class EntityToIdAjaxTransformer implements DataTransformerInterface
{
    private $choiceList;

    public function __construct(EntityAjaxChoiceList $choiceList)
    {
        $this->choiceList = $choiceList;
    }

    /**
     * Transforms entities into choice keys.
     *
     * @param Collection|object $entity A collection of entities, a single entity or NULL
     *
     * @return mixed An array of choice keys, a single key or NULL
     */
    public function transform($entity)
    {
        if (null === $entity || '' === $entity) {
            return '';
        }

        if (!is_object($entity)) {
            throw new UnexpectedTypeException($entity, 'object');
        }

        if ($entity instanceof Collection) {
            throw new \InvalidArgumentException('Expected an object, but got a collection. Did you forget to pass "multiple=true" to an entity field?');
        }

        /*if (count($this->choiceList->getIdentifier()) > 1) {
            // load all choices
            $availableEntities = $this->choiceList->getEntities();

            return array_search($entity, $availableEntities);
        }*/

        return current($this->choiceList->getIdentifierValues($entity));
    }

    /**
     * Transforms choice keys into entities.
     *
     * @param  mixed $key   An array of keys, a single key or NULL
     *
     * @return Collection|object  A collection of entities, a single entity or NULL
     */
    public function reverseTransform($key)
    {
        if ('' === $key || null === $key) {
            return null;
        }

        if (count($this->choiceList->getIdentifier()) > 1 && !is_numeric($key)) {
            throw new UnexpectedTypeException($key, 'numeric');
        }

        foreach($this->choiceList->getEntitiesByIds(array($key)) as $entity){
            return $entity;
        }

        throw new TransformationFailedException(sprintf('The entity with key "%s" could not be found', $key));
    }
}
