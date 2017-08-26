<?php
/**
 * This file is part of the PositibeLabs Projects.
 *
 * (c) Pedro Carlos Abreu <pcabreus@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Positibe\Bundle\MenuBundle\Model;

use Doctrine\ORM\EntityManager;

/**
 * Class ContentIdUtil
 * @package Positibe\Bundle\MenuBundle\Doctrine\Orm
 *
 * @author Pedro Carlos Abreu <pcabreus@gmail.com>
 */
final class ContentIdUtil
{
    /**
     * Determine target class and id for this content.
     *
     * @param mixed $identifier as produced by getContentId
     *
     * @return array with model first element, id second
     */
    public static function getModelAndId($identifier)
    {
        return explode(':', $identifier, 2);
    }

    /**
     * Return the content identifier for the provided content object
     *
     * @param $content
     * @param EntityManager|null $entityManager
     * @return string|void
     */
    public static function getContentId($content, EntityManager $entityManager = null)
    {
        if (!is_object($content)) {
            return null;
        }

        try {
            $class = get_class($content);
            if (!$entityManager && method_exists($content, 'getId')) {
                return $class.':'.$content->getId();
            } else {
                $meta = $entityManager->getClassMetadata($class);
                $ids = $meta->getIdentifierValues($content);
                if (1 !== count($ids)) {
                    throw new \Exception(sprintf('Class "%s" must use only one identifier', $class));
                }

                return implode(':', [$class, reset($ids)]);
            }

        } catch (\Exception $e) {
            return null;
        }
    }
}