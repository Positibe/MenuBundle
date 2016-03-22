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

/**
 * Interface to be implemented by content that exposes editable menu referrers.
 * This is used with the Sonata MenuAwareExtension.
 *
 * Interface MenuNodeReferrersInterface
 * @package Positibe\Bundle\OrmMenuBundle\Model
 *
 * @author Pedro Carlos Abreu <pcabreus@gmail.com>
 */
interface MenuNodeReferrersInterface
{
    /**
     * Get all menu nodes that point to this content.
     *
     * @return NodeInterface[] Menu nodes that point to this content
     */
    public function getMenuNodes();

    /**
     * Add a menu node for this content.
     *
     * @param NodeInterface $menu
     */
    public function addMenuNode(NodeInterface $menu);

    /**
     * Remove a menu node for this content.
     *
     * @param NodeInterface $menu
     */
    public function removeMenuNode(NodeInterface $menu);

    /**
     * Set a collection of MenuNode
     *
     * @param $menus
     * @return mixed
     */
    public function setMenuNodes($menus);
}
