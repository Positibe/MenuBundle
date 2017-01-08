<?php
/**
 * This file is part of the PositibeLabs Projects.
 *
 * (c) Pedro Carlos Abreu <pcabreus@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Positibe\Bundle\MenuBundle\Entity;

use Gedmo\Translatable\Entity\MappedSuperclass\AbstractTranslation;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class MenuNodeTranslation
 * @package Positibe\Bundle\MenuBundle\Entity
 *
 * @ORM\Table(name="positibe_menu_translations", indexes={
 *      @ORM\Index(name="positibe_menu_translation_idx", columns={"locale", "object_class", "field", "foreign_key"})
 * })
 * @ORM\Entity(repositoryClass="Gedmo\Translatable\Entity\Repository\TranslationRepository")
 *
 * @author Pedro Carlos Abreu <pcabreus@gmail.com>
 */
class MenuNodeTranslation extends AbstractTranslation {

} 