<?php
/**
 * Copyright © 2017 Divante, Inc. All rights reserved.
 * See LICENSE for license details.
 */
namespace OpenLoyalty\Bundle\UserBundle\Form\Handler;

use Broadway\CommandHandling\CommandBusInterface;
use Doctrine\ORM\EntityManager;
use OpenLoyalty\Bundle\UserBundle\Service\UserManager;
use OpenLoyalty\Component\Seller\Domain\Command\ActivateSeller;
use OpenLoyalty\Component\Seller\Domain\Command\RegisterSeller;
use OpenLoyalty\Component\Seller\Domain\Exception\EmailAlreadyExistsException;
use OpenLoyalty\Component\Seller\Domain\SellerId;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormInterface;

/**
 * Class SellerRegistrationFormHandler.
 */
class SellerRegistrationFormHandler
{
    /**
     * @var CommandBusInterface
     */
    protected $commandBus;

    /**
     * @var UserManager
     */
    protected $userManager;

    /**
     * @var EntityManager
     */
    protected $em;

    /**
     * SellerRegistrationFormHandler constructor.
     *
     * @param CommandBusInterface $commandBus
     * @param UserManager         $userManager
     * @param EntityManager       $em
     */
    public function __construct(
        CommandBusInterface $commandBus,
        UserManager $userManager,
        EntityManager $em
    ) {
        $this->commandBus = $commandBus;
        $this->userManager = $userManager;
        $this->em = $em;
    }

    public function onSuccess(SellerId $sellerId, FormInterface $form)
    {
        $sellerData = $form->getData();
        $password = $sellerData['plainPassword'];
        unset($sellerData['plainPassword']);

        $command = new RegisterSeller($sellerId, $sellerData);

        $email = $sellerData['email'];

        if ($this->userManager->isSellerExist($email)) {
            $form->get('email')->addError(new FormError('This email is already taken'));

            return $form->getErrors();
        }
        try {
            $this->commandBus->dispatch($command);
        } catch (EmailAlreadyExistsException $e) {
            $form->get('email')->addError(new FormError($e->getMessage()));

            return $form->getErrors();
        }

        $activateSellerCommand = new ActivateSeller($sellerId);
        $this->commandBus->dispatch($activateSellerCommand);

        return $this->userManager->createNewSeller($sellerId, $email, $password);
    }
}
