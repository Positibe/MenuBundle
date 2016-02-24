<?php

/*
 * This file is part of the Symfony CMF package.
 *
 * (c) 2011-2014 Symfony CMF
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Positibe\Bundle\OrmMenuBundle\PublishWorkflow\Voter;

use Symfony\Cmf\Bundle\CoreBundle\PublishWorkflow\PublishWorkflowChecker;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\VoterInterface;

/**
 * Publish workflow voter that decides that a menu node is not published if the
 * content it is pointing to is not published.
 */
class MenuContentVoter implements VoterInterface
{
    protected $container;

    /**
     * @param ContainerInterface $container to get the publish workflow checker
     *                                      from. We cannot inject the publish workflow checker directly as
     *                                      this would lead to a circular reference.
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * {@inheritdoc}
     */
    public function supportsAttribute($attribute)
    {
        return PublishWorkflowChecker::VIEW_ATTRIBUTE === $attribute
            || PublishWorkflowChecker::VIEW_ANONYMOUS_ATTRIBUTE === $attribute
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function supportsClass($class)
    {
        return is_subclass_of($class, 'Positibe\Bundle\OrmMenuBundle\Entity\MenuNode');
    }

    /**
     * {@inheritdoc}
     */
    public function vote(TokenInterface $token, $object, array $attributes)
    {
        if (!$this->supportsClass(get_class($object))) {
            return self::ACCESS_ABSTAIN;
        }

        $publishWorkflowChecker = $this->container->get('cmf_core.publish_workflow.checker');
        $content = $object->getContent();
        $decision = self::ACCESS_GRANTED;
        foreach ($attributes as $attribute) {
            if (! $this->supportsAttribute($attribute)) {
                $decision = self::ACCESS_ABSTAIN;
                continue;
            }

            if ($content &&
                false === $publishWorkflowChecker->isGranted($attribute, $content)
            ) {
                return self::ACCESS_DENIED;
            }
        }

        return $decision;
    }
}