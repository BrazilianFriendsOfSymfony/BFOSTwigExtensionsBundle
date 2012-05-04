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
use Symfony\Component\Form\Exception\TransformationFailedException;
use Symfony\Component\Form\DataTransformerInterface;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use \BFOS\TwigExtensionsBundle\Form\ChoiceList\EntityAjaxChoiceList;

class EntitiesToArrayAjaxTransformer implements DataTransformerInterface
{
    private $choiceList;

    public function __construct(EntityAjaxChoiceList $choiceList)
    {
        $this->choiceList = $choiceList;
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
            $value = current($this->choiceList->getIdentifierValues($entity));
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

        if ('' === $keys || null === $keys) {
            return $collection;
        }

        if (!is_array($keys)) {
            throw new UnexpectedTypeException($keys, 'array');
        }

        $notFound = array();

        // optimize this into a SELECT WHERE IN query
        foreach($this->choiceList->getEntitiesByIds($keys) as $entity){
            $collection->add($entity);
        }
        /*foreach ($keys as $key) {
            if ($entity = ) {
            } else {
                $notFound[] = $key;
            }
        }

        if (count($notFound) > 0) {
            throw new TransformationFailedException(sprintf('The entities with keys "%s" could not be found', implode('", "', $notFound)));
        }*/

        return $collection;
    }
}
