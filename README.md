PositibeMenuBundle
==================

This bundle provide a Orm Provider to use KnpMenuBundle with menus loaded from database and it's inspired by Symfony-Cmf MenuBundle.

Installation
------------

To install the bundle just add the dependent bundles:

    php composer.phar require positibe/orm-menu-bundle

Next, be sure to enable the bundles in your application kernel:

    <?php
    // app/AppKernel.php
    public function registerBundles()
    {
        return array(
            // Dependency (check if you already have this bundle included)
            new Symfony\Cmf\Bundle\CoreBundle\CmfCoreBundle(),
            // Vendor specifics bundles
            new Knp\Bundle\MenuBundle\KnpMenuBundle(),
            new Positibe\Bundle\MenuBundle\PositibeMenuBundle(),

            // ...
        );
    }

Configuration
=============

Import all necessary configurations to your app/config/config.yml the basic configuration. You only need to define the available locales:

    # app/config/config.yml
    imports:
        - { resource: @PositibeMenuBundle/Resources/config/config.yml }

    #If you want some advanced configuration
    positibe_menu:
        public_routes: # e.g. [homepage, my-company]  List of public symfony routes available.
        content_class: # e.g. [ AppBundle\Entity\Post, AppBundle\Entity\Category ] List of content that extend of MenuNodeReferralInterface.

    #By default we disabled the `request_listener` in SymfonyCmf Core configuration because we don't use DynamicRouter on this bundles, enable it if you will use it.
    cmf_core:
        publish_workflow:
            request_listener: true # false by default

    parameters:
        locales: [es, en, fr] # Maybe you already have it configured

And finally load the routing to use the form `MenuNodeType` correctly:

    # app/config/routing.yml
    # ... others routings
    positibe_menu:
        resource: "@PositibeMenuBundle/Resources/config/routing.yml"

**Caution:**: This bundle use the timestampable, sluggable, softdeletable, translatable and sortable extension of GedmoDoctrineExtension. Be sure you already have its listeners enabled. You can also to use StofDoctrineExtensionBundle.

Remember to update the schema:

    php app/console doctrine:schema:update --force

Using link type menus
---------------------

    [php]
    <?php
    // Creating the root menu that is a container for submenus
    $menu = new MenuNodeBase('footer'); //Class of `\Positibe\Bundle\MenuBundle\Entity\MenuNodeBase`
    $menu->setChildrenAttributes(['class' => 'nav navbar-nav']); //You can set the ul attributes here

    $manager->persist($menu);

    //Creating an URI menu, that link to a external or internal full url.
    $menuExternalUrl = new MenuNodeBase('Github'); //Class of `\Positibe\Bundle\MenuBundle\Entity\MenuNode`
    $menuExternalUrl->setLinkUri('https://github.com/Positibe/MenuBundle');
    $menu->addChild($menuExternalUrl);

    $manager->persist($menuExternalUrl);

    // Creating a route menu, that link to a route in the routing configuration of your application
    $menuHomePage = new MenuNodeBase(); //Class of `\Positibe\Bundle\MenuBundle\Entity\MenuNodeBase`
    $menuHomePage->setName('homepage'); //You can define a code name to have better control of the menus
    $menuHomePage->setLabel('Inicio'); //And you can define a proper label to show in the views
    $menuHomePage->setLinkRoute('homepage');
    $menu->addChild($menuHomePage);

    $manager->persist($menuHomePage);
    $manager->flush();

Translate a menu label
----------------------

    [php]
    <?php
    $menuContact = $manager->getRepository('PositibeMenuBundle:MenuNodeBase')->findOneBy(['name' => 'homepage']);

    $menuContact->setLabel('Inicio'); //Change the label normally
    $menuContact->setLocale('es'); //Then set the proper locale
    $manager->persist($menuContact);
    $manager->flush();

Set an entity into a menu
-------------------------

An entity that has menus must implement `Positibe\Bundle\MenuBundle\Model\MenuNodeReferrersInterface`.

Add to any entity you want the relation with `Positibe\Bundle\MenuBundle\Entity\MenuNode`:

    <?php
    // src/AppBundle/Entity/Post.php
    namespace AppBundle\Entity;

    use Doctrine\Common\Collections\ArrayCollection;
    use Positibe\Bundle\MenuBundle\Model\MenuNodeReferrersInterface;
    use Positibe\Bundle\MenuBundle\Entity\MenuNode;
    use Doctrine\ORM\Mapping as ORM;

    /**
     *
     * @ORM\Table(name="app_post")
     * @ORM\Entity(repositoryClass="AppBundle\Entity\PostRepository")
     */
    class Post implements MenuNodeReferrersInterface {

        /**
         * @var MenuNode[]|ArrayCollection
         *
         * @ORM\ManyToMany(targetEntity="Positibe\Bundle\MenuBundle\Entity\MenuNode", orphanRemoval=TRUE, cascade="all")
         * @ORM\JoinTable(name="app_post_menus")
         */
        protected $menuNodes;

        /**
         * Get all menu nodes that point to this content.
         *
         * @return MenuNode[]|ArrayCollection Menu nodes that point to this content
         */
        public function getMenuNodes()
        {
            return $this->menuNodes;
        }

        /**
         * Add a menu node for this content.
         *
         * @param MenuNode|NodeInterface $menu
         * @return array|MenuNode
         */
        public function addMenuNode(NodeInterface $menu)
        {
            $this->menuNodes[] = $menu;
            $menu->setContent($this);

            return $this->menuNodes;
        }

        /**
         * Remove a menu node for this content.
         *
         * @param MenuNode|NodeInterface $menu
         * @return $this
         */
        public function removeMenuNode(NodeInterface $menu)
        {
            $this->menuNodes->removeElement($menu);

            return $this;
        }
    }

**Tip:** You can use `Positibe\Bundle\MenuBundle\Entity\HasMenusTrait` to simplify the implementation of MenuNodeReferrersInterface methods.

    <?php
    // src/AppBundle/Entity/Post.php
    namespace AppBundle\Entity;

    use Doctrine\Common\Collections\ArrayCollection;
    use Positibe\Bundle\MenuBundle\Model\MenuNodeReferrersInterface;
    use Positibe\Bundle\MenuBundle\Entity\HasMenuTrait
    use Doctrine\ORM\Mapping as ORM;

    /**
     *
     * @ORM\Table(name="app_post")
     * @ORM\Entity(repositoryClass="AppBundle\Entity\PostRepository")
     */
    class Post implements MenuNodeReferrersInterface {

        use HasMenuTrait;

        public function __construct()
        {
            $this->menuNodes = new ArrayCollection();
        }
    }

Entity Repositories
-------------------

**Important**: The Repository for your entity must implement `Positibe\Bundle\MenuBundle\Entity\HasMenuRepositoryInterface`.

    <?php
    // src/AppBundle/Entity/PostRepository.php
    namespace AppBundle\Entity;

    use Doctrine\ORM\EntityRepository;
    use Positibe\Bundle\MenuBundle\Entity\HasMenuRepositoryInterface;

    class PostRepository extends EntityRepository implements HasMenuRepositoryInterface
    {
        /**
         * @param MenuNode $menuNode
         * @return mixed
         */
        public function findOneByMenuNodes(MenuNode $menuNode)
        {
            $qb = $this->createQueryBuilder('c')
                ->join('c.menuNodes', 'm')
                ->where('m = :menu')
                ->setParameter('menu', $menuNode);

            return $qb->getQuery()->getOneOrNullResult();
        }
    }

**Tip:** You can use `Positibe\Bundle\MenuBundle\Entity\HasMenusRepositoryTrait` to simplify the implementation of HasMenuRepositoryInterface methods.

    <?php
    // src/AppBundle/Entity/PostRepository.php
    namespace AppBundle\Entity;

    use Doctrine\ORM\EntityRepository;
    use Positibe\Bundle\MenuBundle\Entity\HasMenuRepositoryInterface;
    use Positibe\Bundle\MenuBundle\Entity\HasMenuRepositoryTrait;

    class PostRepository extends EntityRepository implements HasMenuRepositoryInterface
    {
        use HasMenuRepositoryTrait;
    }

Remember to update the schema for your new associations:

    php app/console doctrine:schema:update --force

Creating routes
---------------

    // Creating a Root Menu that is a container for submenus
    $menu = new MenuNode(); //Class of `\Positibe\Bundle\MenuBundle\Entity\MenuNode`
    $menu->setName('main');
    $menu->setChildrenAttributes(array('class' => 'nav navbar-nav')); //You can set the ul attributes here

    // Creating a Route menu, that link to a Symfony Routing o PositibeOrmRoutingBundle routing
    $menuHomePage = new MenuNode(); //Class of `\Positibe\Bundle\MenuBundle\Entity\MenuNode`
    $menuHomePage->setName('homepage');
    $menuHomePage->setLinkRoute('homepage');
    $menu->addChild($menuHomePage);

    //Creating a URI menu, that link to a external or internal full url.
    $menuExternalUrl = new MenuNode(); //Class of `\Positibe\Bundle\MenuBundle\Entity\MenuNode`
    $menuExternalUrl->setName('external');
    $menuExternalUrl->setLinkUri('http://external-link.com');
    $menu->addChild($menuExternalUrl);

    //Save the parent menu only
    $manager->persist($menu);
    $manager->flush();

    //Creating a Content menu, that link to a content wherever be its routes.
    $menu = $manager->getRepository('PositibeMenuBundle:MenuNode')->findOneByName('main');
    $post = new Post(); //Class that implement `Positibe\Bundle\MenuBundle\Model\MenuNodeReferrersInterface`
    $post->setTitle('Symfony is awesome');

    $menuContent = new MenuNode(); //Class of `\Positibe\Bundle\MenuBundle\Entity\MenuNode`
    $menuContent->setName(strtolower(str_replace(' ', '-', $new->getTitle())));
    $menuContent->setLinkContent($post);
    $post->addMenuNode($menuContent);
    $menuContent->setParent($menu);

    $manager->persist($post);
    $manager->flush();

Rendering the menu
------------------

You only need to use the `knp_menu_render` function in your twig template:

    {% app/Resources/views/base.html.twig %}
    {{ knp_menu_render('main') }}


To display a human name of content class, you need to translate this class name on the PositibeMenuBundle domain:

    # app/Resources/translations/PositibeMenuBundle.{es|<locales>}.yml
    AppBundle\Entity\Post: Artículo
    AppBundle\Entity\Category: Categoría

For more information see the [Symfony Cmf MenuBundle documentation](http://symfony.com/doc/master/cmf/bundles/menu/index.html) and [KnpMenuBundle Documentation](https://github.com/KnpLabs/KnpMenuBundle/blob/master/Resources/doc/index.md)