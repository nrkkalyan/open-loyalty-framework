<?php
/**
 * Copyright © 2017 Divante, Inc. All rights reserved.
 * See LICENSE for license details.
 */
namespace OpenLoyalty\Component\EarningRule\Domain\Command;

use Broadway\CommandHandling\CommandHandler;
use Broadway\UuidGenerator\UuidGeneratorInterface;
use OpenLoyalty\Component\EarningRule\Domain\CustomEventEarningRule;
use OpenLoyalty\Component\EarningRule\Domain\EarningRule;
use OpenLoyalty\Component\EarningRule\Domain\EarningRuleRepository;
use OpenLoyalty\Component\EarningRule\Domain\EarningRuleUsage;
use OpenLoyalty\Component\EarningRule\Domain\EarningRuleUsageId;
use OpenLoyalty\Component\EarningRule\Domain\Exception\CustomEventEarningRuleAlreadyExistsException;

/**
 * Class EarningRuleCommandHandler.
 */
class EarningRuleCommandHandler extends CommandHandler
{
    /**
     * @var EarningRuleRepository
     */
    protected $earningRuleRepository;

    /**
     * @var UuidGeneratorInterface
     */
    protected $uuidGenerator;

    /**
     * EarningRuleCommandHandler constructor.
     *
     * @param EarningRuleRepository  $earningRuleRepository
     * @param UuidGeneratorInterface $uuidGenerator
     */
    public function __construct(EarningRuleRepository $earningRuleRepository, UuidGeneratorInterface $uuidGenerator)
    {
        $this->earningRuleRepository = $earningRuleRepository;
        $this->uuidGenerator = $uuidGenerator;
    }

    public function handleCreateEarningRule(CreateEarningRule $command)
    {
        $type = $command->getType();
        $class = EarningRule::TYPE_MAP[$type];
        $rule = new $class($command->getEarningRuleId(), $command->getEarningRuleData());

        if ($rule instanceof CustomEventEarningRule
            && $this->earningRuleRepository->isCustomEventEarningRuleExist($rule->getEventName())
        ) {
            throw new CustomEventEarningRuleAlreadyExistsException();
        }

        $this->earningRuleRepository->save($rule);
    }

    public function handleUpdateEarningRule(UpdateEarningRule $command)
    {
        $data = $command->getEarningRuleData();
        /** @var EarningRule $rule */
        $rule = $this->earningRuleRepository->byId($command->getEarningRuleId());
        $rule::validateRequiredData($data);
        $rule->setFromArray($data);

        if ($rule instanceof CustomEventEarningRule
            && $this->earningRuleRepository->isCustomEventEarningRuleExist($rule->getEventName(), $rule->getEarningRuleId())
        ) {
            throw new CustomEventEarningRuleAlreadyExistsException();
        }

        $this->earningRuleRepository->save($rule);
    }

    public function handleActivateEarningRule(ActivateEarningRule $command)
    {
        /** @var EarningRule $rule */
        $rule = $this->earningRuleRepository->byId($command->getEarningRuleId());
        $rule->setActive(true);
        $this->earningRuleRepository->save($rule);
    }

    public function handleDeactivateEarningRule(DeactivateEarningRule $command)
    {
        /** @var EarningRule $rule */
        $rule = $this->earningRuleRepository->byId($command->getEarningRuleId());
        $rule->setActive(false);
        $this->earningRuleRepository->save($rule);
    }

    public function handleUseCustomEventEarningRule(UseCustomEventEarningRule $command)
    {
        /** @var EarningRule $rule */
        $rule = $this->earningRuleRepository->byId($command->getEarningRuleId());
        $usage = new EarningRuleUsage(
            new EarningRuleUsageId($this->uuidGenerator->generate()),
            $command->getSubject(),
            $rule
        );
        $rule->addUsage($usage);
        $this->earningRuleRepository->save($rule);
    }
}
