<?php
/**
 * Copyright © 2017 Divante, Inc. All rights reserved.
 * See LICENSE for license details.
 */
namespace OpenLoyalty\Bundle\UserBundle\Form\Handler;

use Broadway\CommandHandling\CommandBusInterface;
use Doctrine\ORM\EntityManager;
use OpenLoyalty\Bundle\UserBundle\Service\UserManager;
use OpenLoyalty\Component\Customer\Domain\Command\UpdateCustomerAddress;
use OpenLoyalty\Component\Customer\Domain\Command\UpdateCustomerCompanyDetails;
use OpenLoyalty\Component\Customer\Domain\Command\UpdateCustomerDetails;
use OpenLoyalty\Component\Customer\Domain\Command\UpdateCustomerLoyaltyCardNumber;
use OpenLoyalty\Component\Customer\Domain\CustomerId;
use OpenLoyalty\Component\Customer\Domain\Exception\EmailAlreadyExistsException;
use OpenLoyalty\Component\Customer\Domain\Exception\LoyaltyCardNumberAlreadyExistsException;
use OpenLoyalty\Component\Customer\Domain\Exception\PhoneAlreadyExistsException;
use OpenLoyalty\Component\Customer\Domain\Validator\CustomerUniqueValidator;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormInterface;

/**
 * Class CustomerEditFormHandler.
 */
class CustomerEditFormHandler
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
     * @var CustomerUniqueValidator
     */
    protected $customerUniqueValidator;

    /**
     * CustomerEditFormHandler constructor.
     *
     * @param CommandBusInterface     $commandBus
     * @param UserManager             $userManager
     * @param EntityManager           $em
     * @param CustomerUniqueValidator $customerUniqueValidator
     */
    public function __construct(
        CommandBusInterface $commandBus,
        UserManager $userManager,
        EntityManager $em,
        CustomerUniqueValidator $customerUniqueValidator
    ) {
        $this->commandBus = $commandBus;
        $this->userManager = $userManager;
        $this->em = $em;
        $this->customerUniqueValidator = $customerUniqueValidator;
    }

    public function onSuccess(CustomerId $customerId, FormInterface $form)
    {
        $customerData = $form->getData();
        if (!empty($customerData['email'])) {
            $email = $customerData['email'];

            $emailExists = false;
            if ($this->isUserExistAndIsDifferentThanEdited($customerId->__toString(), $email)) {
                $emailExists = 'This email is already taken';
            }
            try {
                $this->customerUniqueValidator->validateEmailUnique($email, $customerId);
            } catch (EmailAlreadyExistsException $e) {
                $emailExists = $e->getMessage();
            }
            if ($emailExists) {
                $form->get('email')->addError(new FormError($emailExists));
            }
        }
        if (isset($customerData['phone']) && $customerData['phone']) {
            try {
                $this->customerUniqueValidator->validatePhoneUnique($customerData['phone'], $customerId);
            } catch (PhoneAlreadyExistsException $e) {
                $form->get('phone')->addError(new FormError($e->getMessage()));
            }
        }
        if (isset($customerData['loyaltyCardNumber'])) {
            try {
                $this->customerUniqueValidator->validateLoyaltyCardNumberUnique($customerData['loyaltyCardNumber'], $customerId);
            } catch (LoyaltyCardNumberAlreadyExistsException $e) {
                $form->get('loyaltyCardNumber')->addError(new FormError($e->getMessage()));
            }
        }

        if ($form->getErrors(true)->count() > 0) {
            return $form->getErrors();
        }

        if (!$customerData['company']['name'] && !$customerData['company']['nip']) {
            unset($customerData['company']);
        }

        $command = new UpdateCustomerDetails($customerId, $customerData);
        $this->commandBus->dispatch($command);

        if (isset($customerData['address'])) {
            $addressData = $customerData['address'];
        } else {
            $addressData = [];
        }

        $updateAddressCommand = new UpdateCustomerAddress($customerId, $addressData);
        $this->commandBus->dispatch($updateAddressCommand);

        if (isset($customerData['company'])) {
            $company = $customerData['company'];
        } else {
            $company = [];
        }
        $updateCompanyDataCommand = new UpdateCustomerCompanyDetails($customerId, $company);
        $this->commandBus->dispatch($updateCompanyDataCommand);

        if (isset($customerData['loyaltyCardNumber'])) {
            $loyaltyCardCommand = new UpdateCustomerLoyaltyCardNumber($customerId, $customerData['loyaltyCardNumber']);
            $this->commandBus->dispatch($loyaltyCardCommand);
        }
        if (empty($email)) {
            return true;
        }

        $user = $this->em->getRepository('OpenLoyaltyUserBundle:Customer')->find($customerId->__toString());

        $user->setEmail($email);
        $this->userManager->updateUser($user);

        return true;
    }

    private function isUserExistAndIsDifferentThanEdited($id, $email)
    {
        $qb = $this->em->createQueryBuilder()->select('u')->from('OpenLoyaltyUserBundle:Customer', 'u');
        $qb->andWhere('u.email = :email')->setParameter('email', $email);
        $qb->andWhere('u.id != :id')->setParameter('id', $id);

        $result = $qb->getQuery()->getResult();

        return count($result) > 0;
    }
}
