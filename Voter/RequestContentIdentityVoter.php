<?php

/*
 * This file is part of the Symfony CMF package.
 *
 * (c) 2011-2014 Symfony CMF
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Positibe\Bundle\OrmMenuBundle\Voter;

use Knp\Menu\ItemInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * This voter compares whether a key in the request is identical to the content
 * entry in the menu item extras.
 *
 * This voter is NOT enabled by default, as usually this is already covered
 * by the core menu bundle looking at request URLs.
 *
 * @author David Buchmann <mail@davidbu.ch>
 */
class RequestContentIdentityVoter implements VoterInterface
{
    /**
     * @var string The key to look up the content in the request attributes
     */
    private $requestKey;

    /**
     * @var Request|null
     */
    private $request;

    /**
     * @param string $requestKey The key to look up the content in the request
     *                           attributes.
     */
    public function __construct($requestKey)
    {
        $this->requestKey = $requestKey;
    }

    public function setRequest(RequestStack $requestStack = null)
    {
        $this->request = $requestStack->getMasterRequest();
    }

    /**
     * {@inheritDoc}
     */
    public function matchItem(ItemInterface $item = null)
    {
        if (! $this->request) {
            return null;
        }

        $content = $item->getExtra('content');

        if (null !== $content
            && $this->request->attributes->has($this->requestKey)
            && $this->request->attributes->get($this->requestKey) === $content
        ) {
            return true;
        }

        return null;
    }
}
