<?php
/**
 * Copyright © 2017 Divante, Inc. All rights reserved.
 * See LICENSE for license details.
 */
namespace OpenLoyalty\Bundle\EarningRuleBundle\Event\Listener;

use JMS\Serializer\EventDispatcher\EventSubscriberInterface;
use JMS\Serializer\EventDispatcher\ObjectEvent;
use OpenLoyalty\Bundle\EarningRuleBundle\Model\EarningRule;
use OpenLoyalty\Component\EarningRule\Domain\CustomEventEarningRule;
use OpenLoyalty\Component\EarningRule\Domain\PointsEarningRule;
use OpenLoyalty\Component\Core\Domain\Model\SKU;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use OpenLoyalty\Component\Level\Domain\Level;
use OpenLoyalty\Component\Level\Domain\LevelId;
use OpenLoyalty\Component\Level\Domain\LevelRepository;
use OpenLoyalty\Component\Segment\Domain\Segment;
use OpenLoyalty\Component\Segment\Domain\SegmentId;
use OpenLoyalty\Component\Segment\Domain\SegmentRepository;
use OpenLoyalty\Component\EarningRule\Domain\EarningRule as BaseEarningRule;

/**
 * Class EarningRuleSerializationListener.
 */
class EarningRuleSerializationListener implements EventSubscriberInterface
{
    /**
     * @var UrlGeneratorInterface
     */
    private $urlGenerator;

    protected $segmentRepository;

    protected $levelRepository;

    /**
     * EarningRuleSerializationListener constructor.
     *
     * @param UrlGeneratorInterface $urlGenerator
     * @param SegmentRepository     $segmentRepository
     * @param LevelRepository       $levelRepository
     */
    public function __construct(
        UrlGeneratorInterface $urlGenerator,
        SegmentRepository $segmentRepository,
        LevelRepository $levelRepository
    ) {
        $this->urlGenerator = $urlGenerator;
        $this->segmentRepository = $segmentRepository;
        $this->levelRepository = $levelRepository;
    }

    public static function getSubscribedEvents()
    {
        return array(
            array('event' => 'serializer.post_serialize', 'method' => 'onPostSerialize'),
        );
    }

    public function onPostSerialize(ObjectEvent $event)
    {
        /** @var EarningRule $rule */
        $rule = $event->getObject();

        if ($rule instanceof PointsEarningRule) {
            $event->getVisitor()->addData('excludedSKUs', array_map(function (SKU $sku) {
                return $sku->getCode();
            }, $rule->getExcludedSKUs()));
        }
        if ($rule instanceof CustomEventEarningRule) {
            $event->getVisitor()->setData(
                'usageUrl',
                $this->urlGenerator->generate('oloy.earning_rule.report_custom_event', [
                    'customer' => ':customerId',
                    'eventName' => $rule->getEventName(),
                ], UrlGeneratorInterface::ABSOLUTE_URL)
            );
        }

        if ($rule instanceof BaseEarningRule) {
            $segmentNames = [];
            $levelNames = [];

            foreach ($rule->getSegments() as $segmentId) {
                $segment = $this->segmentRepository->byId(new SegmentId($segmentId->__toString()));
                if ($segment instanceof Segment) {
                    $segmentNames[$segmentId->__toString()] = $segment->getName();
                }
            }
            foreach ($rule->getLevels() as $levelId) {
                $level = $this->levelRepository->byId(new LevelId($levelId->__toString()));
                if ($level instanceof Level) {
                    $levelNames[$levelId->__toString()] = $level->getName();
                }
            }

            $event->getVisitor()->addData('segmentNames', $segmentNames);
            $event->getVisitor()->addData('levelNames', $levelNames);
        }
    }
}
