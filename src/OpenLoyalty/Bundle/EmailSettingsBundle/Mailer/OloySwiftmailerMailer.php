<?php
/**
 * Copyright © 2017 Divante, Inc. All rights reserved.
 * See LICENSE for license details.
 */

namespace OpenLoyalty\Bundle\EmailSettingsBundle\Mailer;

use OpenLoyalty\Bundle\EmailBundle\Mailer\OloySwiftmailerMailer as BaseMailer;
use OpenLoyalty\Component\Email\Domain\ReadModel\DoctrineEmailRepositoryInterface;
use OpenLoyalty\Bundle\EmailBundle\Model\MessageInterface;
use Symfony\Bridge\Twig\TwigEngine;
use OpenLoyalty\Component\Email\Domain\ReadModel\Email;
use Swift_Mailer;
use Twig_Environment;

/**
 * Class OloySwiftmailerMailer.
 */
class OloySwiftmailerMailer extends BaseMailer
{
    /**
     * @var DoctrineEmailRepositoryInterface
     */
    protected $emailRepository;

    /**
     * @var Twig_Environment
     */
    protected $twig;

    /**
     * {@inheritdoc}
     *
     * @param DoctrineEmailRepositoryInterface $emailRepository
     * @param Twig_Environment                 $twig
     */
    public function __construct(
        TwigEngine $twigEngine,
        Swift_Mailer $swiftmailer,
        DoctrineEmailRepositoryInterface $emailRepository,
        Twig_Environment $twig
    ) {
        parent::__construct($twigEngine, $swiftmailer);

        $this->emailRepository = $emailRepository;
        $this->twig            = $twig;
    }

    /**
     * {@inheritdoc}
     */
    protected function decorateMessage(MessageInterface $message)
    {
        $result = parent::decorateMessage($message);

        // decorate message with data from database
        if ($emailTemplate = $this->getEmailTemplate($message->getTemplate())) {
            $newSubject = $emailTemplate->getSubject();
            foreach ($message->getParams() as $search => $value) {
                $newSubject = str_replace('{{ '.$search.' }}', $value, $newSubject);
            }
            $message->setSubject($newSubject);
            $message->setSenderName($emailTemplate->getSenderName());
            $message->setSenderEmail($emailTemplate->getSenderEmail());

            $template = $this->twig->createTemplate($emailTemplate->getContent());
            $renderedContent = $template->render($message->getParams());
            $message->setContent($renderedContent);
        }

        return $result;
    }

    /**
     * @param $key
     *
     * @return Email|null
     */
    protected function getEmailTemplate($key)
    {
        return $this->emailRepository->getByKey($key);
    }
}
