<?php

namespace Positibe\Bundle\OrmMenuBundle\Event;

final class Events
{
    /**
     * Fired when a menu item is to be created from a node in ContentAwareFactory
     *
     * The event object is a CreateMenuItemFromNodeEvent.
     */
    const CREATE_ITEM_FROM_NODE = 'cmf_menu.create_menu_item_from_node';
}
