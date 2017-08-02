<?php
/**
 * Copyright © 2017 Divante, Inc. All rights reserved.
 * See LICENSE for license details.
 */
namespace OpenLoyalty\Component\Seller\Domain\Command;

use Broadway\CommandHandling\CommandHandler;
use OpenLoyalty\Component\Seller\Domain\PosId;
use OpenLoyalty\Component\Seller\Domain\Seller;
use OpenLoyalty\Component\Seller\Domain\SellerRepository;
use OpenLoyalty\Component\Seller\Domain\Validator\SellerUniqueValidator;

/**
 * Class SellerCommandHandler.
 */
class SellerCommandHandler extends CommandHandler
{
    /**
     * @var SellerRepository
     */
    private $repository;

    /**
     * @var SellerUniqueValidator
     */
    private $uniqueValidator;

    /**
     * SellerCommandHandler constructor.
     *
     * @param SellerRepository      $repository
     * @param SellerUniqueValidator $uniqueValidator
     */
    public function __construct(SellerRepository $repository, SellerUniqueValidator $uniqueValidator)
    {
        $this->repository = $repository;
        $this->uniqueValidator = $uniqueValidator;
    }

    public function handleRegisterSeller(RegisterSeller $command)
    {
        $this->uniqueValidator->validateEmailUnique($command->getSellerData()['email']);
        $sellerData = $command->getSellerData();
        if (!$sellerData['posId'] instanceof PosId) {
            $sellerData['posId'] = new PosId($sellerData['posId']);
        }
        /** @var Seller $seller */
        $seller = Seller::registerSeller($command->getSellerId(), $sellerData);
        $this->repository->save($seller);
    }

    public function handleActivateSeller(ActivateSeller $command)
    {
        /** @var Seller $seller */
        $seller = $this->repository->load($command->getSellerId());
        $seller->activate();
        $this->repository->save($seller);
    }

    public function handleDeactivateSeller(DeactivateSeller $command)
    {
        /** @var Seller $seller */
        $seller = $this->repository->load($command->getSellerId());
        $seller->deactivate();
        $this->repository->save($seller);
    }

    public function handleDeleteSeller(DeleteSeller $command)
    {
        /** @var Seller $seller */
        $seller = $this->repository->load($command->getSellerId());
        $seller->delete();
        $this->repository->save($seller);
    }

    public function handleUpdateSeller(UpdateSeller $command)
    {
        $sellerData = $command->getSellerData();
        if (isset($sellerData['email'])) {
            $this->uniqueValidator->validateEmailUnique($sellerData['email'], $command->getSellerId());
        }
        if (isset($sellerData['posId']) && !$sellerData['posId'] instanceof PosId) {
            $sellerData['posId'] = new PosId($sellerData['posId']);
        }
        /** @var Seller $seller */
        $seller = $this->repository->load($command->getSellerId());
        $seller->update($sellerData);
        $this->repository->save($seller);
    }
}
