<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace BFOS\TwigExtensionsBundle\Form\DataTransformer;

use Symfony\Component\Form\Exception\UnexpectedTypeException;
use Symfony\Component\Form\Exception\FormException;
use Symfony\Component\Form\Exception\TransformationFailedException;
use Symfony\Component\Form\DataTransformerInterface;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;

class EntitiesToArrayAjaxTransformer implements DataTransformerInterface
{
    private $class;

    /**
     * @var EntityManager $em
     */
    private $em;

    /**
     * @var \Doctrine\Common\Persistence\Mapping\ClassMetadata
     */
    private $classMetadata;

    /**
     * @var EntityRepository
     */
    private $repository;


    public function __construct($class, $em)
    {
        $this->class = $class;
        $this->classMetadata = $em->getClassMetadata($class);
        $this->repository = $em->getRepository($class);
        $this->em = $em;
    }

    /**
     * Transforms entities into choice keys.
     *
     * @param  Collection|object $collection A collection of entities, a single entity or NULL
     *
     * @return mixed An array of choice keys, a single key or NULL
     */
    public function transform($collection)
    {
        if (null === $collection) {
            return array();
        }

        if (!($collection instanceof Collection)) {
            throw new UnexpectedTypeException($collection, 'Doctrine\Common\Collections\Collection');
        }

        $array = array();

        foreach ($collection as $entity) {
            //$value = current($this->getIdentifierValues($entity));
            $value = $entity->getId();
            $array[] = is_numeric($value) ? (int) $value : $value;
        }

        return $array;
    }

    /**
     * Transforms choice keys into entities.
     *
     * @param  mixed $keys        An array of keys, a single key or NULL
     *
     * @return Collection|object  A collection of entities, a single entity or NULL
     */
    public function reverseTransform($keys)
    {
        $collection = new ArrayCollection();

        if ('' === $keys || null === $keys || ( is_array($keys) && count($keys)==0 )) {
            return $collection;
        }

        if (!is_array($keys)) {
            throw new UnexpectedTypeException($keys, 'array');
        }

        $notFound = array();

        // optimize this into a SELECT WHERE IN query
        $entities = $this->repository->findById($keys);
        foreach($entities as $entity){
            $collection->add($entity);
        }

        return $collection;
    }


    /**
     * Returns the values of the identifier fields of an entity.
     *
     * Doctrine must know about this entity, that is, the entity must already
     * be persisted or added to the identity map before. Otherwise an
     * exception is thrown.
     *
     * @param object $entity The entity for which to get the identifier
     *
     * @return array          The identifier values
     *
     * @throws FormException  If the entity does not exist in Doctrine's identity map
     */
    private function getIdentifierValues($entity)
    {
        if (!$this->em->contains($entity)) {
            throw new FormException('Entities passed to the choice field must be managed');
        }

        $this->em->initializeObject($entity);

        return $this->classMetadata->getIdentifierValues($entity);
    }
}
