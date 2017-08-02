<?php
/**
 * Copyright © 2017 Divante, Inc. All rights reserved.
 * See LICENSE for license details.
 */
namespace OpenLoyalty\Component\Pos\Domain\Command;

use Broadway\CommandHandling\CommandHandler;
use Broadway\EventDispatcher\EventDispatcher;
use OpenLoyalty\Component\Pos\Domain\Pos;
use OpenLoyalty\Component\Pos\Domain\PosRepository;
use OpenLoyalty\Component\Pos\Domain\SystemEvent\PosSystemEvents;
use OpenLoyalty\Component\Pos\Domain\SystemEvent\PosUpdatedSystemEvent;

/**
 * Class PosCommandHandler.
 */
class PosCommandHandler extends CommandHandler
{
    /**
     * @var PosRepository
     */
    protected $posRepository;

    /**
     * @var EventDispatcher
     */
    protected $eventDispatcher;

    /**
     * PosCommandHandler constructor.
     *
     * @param PosRepository   $posRepository
     * @param EventDispatcher $eventDispatcher
     */
    public function __construct(PosRepository $posRepository, EventDispatcher $eventDispatcher = null)
    {
        $this->posRepository = $posRepository;
        $this->eventDispatcher = $eventDispatcher;
    }

    public function handleCreatePos(CreatePos $command)
    {
        $pos = new Pos($command->getPosId(), $command->getPosData());
        $this->posRepository->save($pos);
    }

    public function handleUpdatePos(UpdatePos $command)
    {
        /** @var Pos $pos */
        $pos = $this->posRepository->byId($command->getPosId());
        $pos->setFromArray($command->getPosData());
        $this->posRepository->save($pos);
        if ($this->eventDispatcher) {
            $this->eventDispatcher->dispatch(PosSystemEvents::POS_UPDATED, [
                new PosUpdatedSystemEvent($command->getPosId(), $pos->getName(), $pos->getLocation() ? $pos->getLocation()->getCity() : null),
            ]);
        }
    }
}
