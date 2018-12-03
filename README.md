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

Copy the configuration on yuor configuration packages:

    # config/packages/positibe_menu.yaml
    parameters:
    #    locales: [es, en, fr] # Maybe you already have it configured
       positibe.menu_node.class: Positibe\Bundle\MenuBundle\Doctrine\Orm\MenuNode
    
    knp_menu:
        providers:
            builder_alias: false
            container_aware: false
        twig:  # use "twig: false" to disable the Twig extension and the TwigRenderer
            template: PositibeMenuBundle::_knp_menu.html.twig
        templating: false # if true, enables the helper for PHP templates
        default_renderer: twig # The renderer to use, list is also available by default
    
    doctrine:
        orm:
            resolve_target_entities:
                Positibe\Bundle\MenuBundle\Model\MenuNodeInterface: "%positibe.menu_node.class%"

**Caution:**: This bundle use the timestampable, sluggable, softdeletable, translatable and sortable extension of GedmoDoctrineExtension. Be sure you already have its listeners enabled. You can also to use StofDoctrineExtensionBundle.

Remember to update the schema:

    php app/console doctrine:schema:update --force

Using link type menus
---------------------

    [php]
    <?php
    $menuClass = $this->container->getParameter('positibe.menu_node.class');
    // Creating the root menu that is a container for submenus
    $menu = new $menuClass('footer');
    $menu->setChildrenAttributes(['class' => 'nav navbar-nav']); //You can set the ul attributes here

    $manager->persist($menu);

    //Creating an URI menu, that link to a external or internal full url.
    /** @var \Positibe\Bundle\MenuBundle\Doctrine\Orm\MenuNode $menuExternalUrl */
    $menuExternalUrl = new $menuClass('Github');
    $menuExternalUrl->setLinkUri('https://github.com/Positibe/MenuBundle');
    $menu->addChild($menuExternalUrl);

    $manager->persist($menuExternalUrl); //The menu is configured with cascade persist, so you don't need to do this

    // Creating a route menu, that link to a route in the routing configuration of your application
    /** @var \Positibe\Bundle\MenuBundle\Doctrine\Orm\MenuNode $menuHomePage */
    $menuHomePage = new $menuClass();
    $menuHomePage->setName('homepage'); //You can define a code name to have better control of the menus
    $menuHomePage->setLabel('Inicio'); //And you can define a proper label to show in the views
    $menuHomePage->setLinkRoute('homepage');
    $menu->addChild($menuHomePage);

    $manager->persist($menuHomePage); //The menu is configured with cascade persist, so you don't need to do this

    $manager->flush();

Translate a menu label
----------------------

    [php]
    <?php
    $menuClass = $this->container->getParameter('positibe.menu_node.class');

    $menuContact = $manager->getRepository($menuClass)->findOneBy(['name' => 'homepage']);

    $menuContact->setLabel('Inicio'); //Change the label normally
    $menuContact->setLocale('es'); //Then set the proper locale
    $manager->persist($menuContact);
    $manager->flush();

Rendering the menu
------------------

You only need to use the `knp_menu_render` function in your twig template:

    {% app/Resources/views/base.html.twig %}
    {{ knp_menu_render('footer') }}

Set an entity into a menu
-------------------------

You can also integrate this bundle with SymfonyCMf RoutingBundle by implementing an entity that has route.

See [Positibe ContentBundle](https://github.com/Positibe/ContentBundle)

For more information see the [Symfony Cmf MenuBundle documentation](http://symfony.com/doc/master/cmf/bundles/menu/index.html) and [KnpMenuBundle Documentation](https://github.com/KnpLabs/KnpMenuBundle/blob/master/Resources/doc/index.md)