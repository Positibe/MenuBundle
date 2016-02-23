<?php
/**
 * This file is part of the PositibeLabs Projects.
 *
 * (c) Pedro Carlos Abreu <pcabreus@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Positibe\Bundle\OrmMenuBundle\Model;

use Knp\Menu\NodeInterface;
use Positibe\Component\ContentAware\Model\ContentAwareInterface;
use Symfony\Cmf\Bundle\CoreBundle\Model\ChildInterface;
use Symfony\Cmf\Bundle\CoreBundle\PublishWorkflow\PublishableInterface;
use Symfony\Cmf\Bundle\CoreBundle\PublishWorkflow\PublishTimePeriodInterface;
use Symfony\Cmf\Bundle\CoreBundle\Translatable\TranslatableInterface;


/**
 * Interface MenuNodeInterface
 * @package Positibe\Bundle\OrmMenuBundle\Model
 *
 * @author Pedro Carlos Abreu <pcabreus@gmail.com>
 */
interface MenuNodeInterface extends
    TranslatableInterface,
    PublishTimePeriodInterface,
    PublishableInterface,
    MenuOptionsInterface,
    ChildInterface,
    ContentAwareInterface
{
    public function setDisplay($display);

    public function setLinkRoute($route);

    public function setLinkUri($uri);

    public function setLinkContent($content);

    /**
     * @param MenuNodeReferrersInterface $content
     */
    public function setContent($content);

    /**
     * Returns the parent of this menu node.
     *
     * @return object
     */
    public function getParentDocument();

    /**
     * {@inheritDoc}
     */
    public function getParentObject();

    /**
     * Add a child menu node, automatically setting the parent node.
     *
     * @param NodeInterface $child
     *
     * @return NodeInterface - The newly added child node.
     */
    public function addChild(NodeInterface $child);

    /**
     * {@inheritDoc}
     */
    public function setParentObject($parent);

    public function getParent();

    /**
     * {@inheritDoc}
     */
    public function setParent($parent);

    /**
     * @return string the loaded locale of this menu node
     */
    public function getLocale();

    /**
     * Set the locale this menu node should be. When doing a flush,
     * this will have the translated fields be stored as that locale.
     *
     * @param string $locale the locale to use for this menu node
     */
    public function setLocale($locale);

    /**
     * {@inheritDoc}
     */
    public function getOptions();

    /**
     * Get the link type
     *
     * The link type is used to explicitly determine which of the uri, route
     * and content fields are used to determine the link which will bre
     * rendered for the menu item. If it is empty this will be determined
     * automatically.
     *
     * @return string
     */
    public function getLinkType();

    /**
     * @see getLinkType
     * @see ContentAwareFactory::$validLinkTypes
     *
     * Valid link types are defined in ContenentAwareFactory
     *
     * @param $linkType string - one of uri, route or content
     */
    public function setLinkType($linkType);

    /**
     * @return mixed
     */
    public function getPosition();

    /**
     * @param mixed $position
     */
    public function setPosition($position);

    /**
     * @return string
     */
    public function getIconClass();

    /**
     * @param string $iconClass
     */
    public function setIconClass($iconClass);

    public function getContentClass();
} 