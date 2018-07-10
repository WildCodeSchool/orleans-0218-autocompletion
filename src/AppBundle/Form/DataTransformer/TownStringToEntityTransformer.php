<?php
/**
 * Created by PhpStorm.
 * User: sylvain
 * Date: 09/07/18
 * Time: 11:58
 */

namespace AppBundle\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;
use AppBundle\Entity\Town;

class TownStringToEntityTransformer implements DataTransformerInterface
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * Transforms an object (town) to a string (number).
     *
     * @param  Town|null $town
     * @return string
     */
    public function transform($town)
    {
        if (null === $town) {
            return '';
        }

        return $town->getTown();
    }

    /**
     * Transforms a string (number) to an object (town).
     *
     * @param  string $townNumber
     * @return Town|null
     * @throws TransformationFailedException if object (town) is not found.
     */
    public function reverseTransform($townString)
    {
        // no town number? It's optional, so that's ok
        if (!$townString) {
            return;
        }

        $town = $this->entityManager
            ->getRepository(Town::class)
            ->findOneByTown($townString)
        ;

        if (null === $town) {
            // causes a validation error
            // this message is not shown to the user
            // see the invalid_message option
            throw new TransformationFailedException(sprintf(
                'An town with name "%s" does not exist!',
                $townString
            ));
        }

        return $town;
    }
}