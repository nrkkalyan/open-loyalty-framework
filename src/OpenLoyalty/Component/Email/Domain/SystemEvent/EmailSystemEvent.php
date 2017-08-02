<?php
/**
 * Copyright © 2017 Divante, Inc. All rights reserved.
 * See LICENSE for license details.
 */
namespace OpenLoyalty\Component\Email\Domain\SystemEvent;

use OpenLoyalty\Component\Email\Domain\EmailId;

/**
 * Class EmailSystemEvent.
 */
class EmailSystemEvent
{
    /**
     * @var EmailId
     */
    protected $emailId;

    /**
     * EmailCreatedSystemEvents constructor.
     *
     * @param EmailId $emailId
     */
    public function __construct(EmailId $emailId)
    {
        $this->emailId = $emailId;
    }

    /**
     * @return EmailId
     */
    public function getEmailId()
    {
        return $this->emailId;
    }
}
