<?php
/**
 * This file is part of the PositibeLabs Projects.
 *
 * (c) Pedro Carlos Abreu <pcabreus@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Positibe\Bundle\OrmMenuBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Gedmo\Sluggable\Util\Urlizer;
use Knp\Menu\NodeInterface;
use Positibe\Bundle\OrmMenuBundle\Menu\Factory\ContentAwareFactory;
use Positibe\Bundle\OrmMenuBundle\Model\MenuNode;
use Positibe\Bundle\OrmMenuBundle\Model\MenuNodeInterface;
use Positibe\Bundle\OrmMenuBundle\Model\MenuNodeReferrersInterface;
use Positibe\Component\ContentAware\Entity\ContentAwareTrait;
use Positibe\Component\Publishable\Entity\PublishableTrait;
use Positibe\Component\Publishable\Entity\PublishTimePeriodTrait;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * This is the standard CMF MenuNode implementation
 *
 * Menu bundle specific additions:
 *
 * - Link type: Ability to explicitly specify the type of link
 * - Content aware: Either a route of document implementing
 *     RouteAware can be used to determine the link.
 *
 * Standard CMF features:
 *
 * - Translatable
 * - Publish Workflow
 *
 * @ORM\MappedSuperclass
 * @Gedmo\TranslationEntity(class="Positibe\Bundle\OrmMenuBundle\Entity\MenuNodeTranslation")
 * @ORM\EntityListeners({"Positibe\Bundle\OrmMenuBundle\EventListener\ContentAwareListener"})
 *
 * Class MenuNode
 * @package Positibe\Bundle\OrmMenuBundle\Entity
 *
 * @author Pedro Carlos Abreu <pcabreus@gmail.com>
 */
abstract class MenuNodeBase extends MenuNode implements MenuNodeInterface {
    use ContentAwareTrait;
    use PublishableTrait;
    use PublishTimePeriodTrait;
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * Node name.
     *
     * @var string
     *
     * @Gedmo\Slug(fields={"label"}, updatable=false, separator="_")
     * @ORM\Column(name="name", type="string", length=128)
     */
    protected $name;

    /**
     * Parent menu node.
     *
     * @var MenuNode
     */
    protected $parent;

    /**
     * Child menu nodes
     *
     * @var Collection
     */
    protected $children;

    /**
     * Menu label.
     *
     * @var string
     *
     * @Gedmo\Translatable
     * @ORM\Column(name="label", type="string", length=255)
     */
    protected $label = '';

    /**
     * @var string
     *
     * @ORM\Column(name="uri", type="string", length=255, nullable=TRUE)
     */
    protected $uri;

    /**
     * The name of the route to generate.
     *
     * @var string
     *
     * @ORM\Column(name="route", type="string", length=255, nullable=TRUE)
     */
    protected $route;

    /**
     * HTML attributes to add to the individual menu element.
     *
     * e.g. array('class' => 'foobar', 'style' => 'bar: foo')
     *
     * @var array
     *
     * @ORM\Column(name="attributes", type="array")
     */
    protected $attributes = array();

    /**
     * HTML attributes to add to the children list element.
     *
     * e.g. array('class' => 'foobar', 'style' => 'bar: foo')
     *
     * @var array
     *
     * @ORM\Column(name="childrenAttributes", type="array")
     */
    protected $childrenAttributes = array();

    /**
     * HTML attributes to add to items link.
     *
     * e.g. array('class' => 'foobar', 'style' => 'bar: foo')
     *
     * @var array
     *
     * @ORM\Column(name="linkAttributes", type="array")
     */
    protected $linkAttributes = array();

    /**
     * HTML attributes to add to the items label.
     *
     * e.g. array('class' => 'foobar', 'style' => 'bar: foo')
     *
     * @var array
     *
     * @ORM\Column(name="labelAttributes", type="array")
     */
    protected $labelAttributes = array();

    /**
     * Hashmap for extra stuff associated to the node.
     *
     * @var array     *
     *
     * @ORM\Column(name="extras", type="array")
     */
    protected $extras = array();

    /**
     * Parameters to use when generating the route.
     *
     * Used with the "route" option.
     *
     * @var array
     *
     * @ORM\Column(name="routeParameters", type="array")
     */
    protected $routeParameters = array();

    /**
     * Set to false to not render
     *
     * @var boolean
     *
     * @ORM\Column(name="display", type="boolean")
     */
    protected $display = true;

    /**
     * Set to false to not render the children.
     *
     * @var boolean
     *
     * @ORM\Column(name="displayChildren", type="boolean")
     */
    protected $displayChildren = true;

    /**
     * Generate an absolute route
     *
     * To be used with the "content" or "route" option.
     *
     * @var boolean
     *
     * @ORM\Column(name="routeAbsolute", type="boolean")
     */
    protected $routeAbsolute = false;

    /**
     * @var string
     * @Gedmo\Locale
     */
    protected $locale;

    /**
     * Enum, values determined by ContentAwareFactory
     * @var string
     *
     * @ORM\Column(name="linkType", type="string", length=128)
     */
    protected $linkType = ContentAwareFactory::LINK_TYPE_ROOT;

    /**
     * @Gedmo\SortablePosition
     * @ORM\Column(name="position", type="integer")
     */
    protected $position = 0;

    /**
     * @var string
     *
     * @ORM\Column(name="icon_class", type="string", length=255, nullable=TRUE)
     */
    protected $iconClass;

    /**
     * @param null $name
     */
    public function __construct($name = null)
    {
        $this->name = $name;
        $this->children = new ArrayCollection();
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->getName();
    }

    /**
     * Return ID of this menu node
     *
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    public function setDisplay($display)
    {
        parent::setDisplay($display);
        $this->setPublishable($display);
    }

    public function setLinkRoute($route)
    {
        $this->setLinkType(ContentAwareFactory::LINK_TYPE_ROUTE);
        $this->route = $route;
    }

    public function setLinkUri($uri)
    {
        $this->setLinkType(ContentAwareFactory::LINK_TYPE_URI);
        $this->uri = $uri;
    }

    public function setLinkContent($content)
    {
        $this->setLinkType(ContentAwareFactory::LINK_TYPE_CONTENT);
        $this->setContent($content);
    }

    /**
     * @param MenuNodeReferrersInterface $content
     */
    public function setContent($content)
    {
        if ($this->content) {
            $newList = new ArrayCollection();
            foreach ($this->content->getMenuNodes() as $menus) {
                if ($menus->getId() !== $this->getId()) {
                    $newList[] = $menus;
                }
            }
            $this->content->setMenuNodes($newList);
            $content->addMenuNode($this);
        }
        $this->content = $content;
        if(is_object($content)) $this->setContentClassByContent($content);
    }

    /**
     * Returns the parent of this menu node.
     *
     * @return object
     */
    public function getParentDocument()
    {
        return $this->getParentObject();
    }

    /**
     * {@inheritDoc}
     */
    public function getParentObject()
    {
        return $this->parent;
    }

    /**
     * Add a child menu node, automatically setting the parent node.
     *
     * @param NodeInterface $child
     *
     * @return NodeInterface - The newly added child node.
     */
    public function addChild(NodeInterface $child)
    {
        if ($child instanceof MenuNodeInterface) {
            $child->setParentObject($this);
        }

        return parent::addChild($child);
    }

    /**
     * {@inheritDoc}
     */
    public function setParentObject($parent)
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * @deprecated use getParentObject instead.
     */
    public function getParent()
    {
        return $this->getParentObject();
    }

    /**
     * {@inheritDoc}
     */
    public function setParent($parent)
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * @return string the loaded locale of this menu node
     */
    public function getLocale()
    {
        return $this->locale;
    }

    /**
     * Set the locale this menu node should be. When doing a flush,
     * this will have the translated fields be stored as that locale.
     *
     * @param string $locale the locale to use for this menu node
     */
    public function setLocale($locale)
    {
        $this->locale = $locale;
    }

    /**
     * {@inheritDoc}
     */
    public function getOptions()
    {
        $options = parent::getOptions();

        return array_merge(
            $options,
            array(
                'linkType' => $this->linkType,
                'content' => $this->getContent(),
                'contentClass' => $this->getContentClass(),
                'iconClass' => $this->getIconClass()
            )
        );
    }

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
    public function getLinkType()
    {
        return $this->linkType;
    }

    /**
     * @see getLinkType
     * @see ContentAwareFactory::$validLinkTypes
     *
     * Valid link types are defined in ContenentAwareFactory
     *
     * @param $linkType string - one of uri, route or content
     */
    public function setLinkType($linkType)
    {
        $this->linkType = $linkType;
    }

    /**
     * @return mixed
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * @param mixed $position
     */
    public function setPosition($position)
    {
        $this->position = $position;
    }

    /**
     * @return string
     */
    public function getIconClass()
    {
        return $this->iconClass;
    }

    /**
     * @param string $iconClass
     */
    public function setIconClass($iconClass)
    {
        $this->iconClass = $iconClass;
    }
}
