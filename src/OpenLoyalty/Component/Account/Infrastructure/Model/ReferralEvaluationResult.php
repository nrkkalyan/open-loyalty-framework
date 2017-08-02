<?php

namespace OpenLoyalty\Component\Account\Infrastructure\Model;

use OpenLoyalty\Component\Customer\Domain\ReadModel\InvitationDetails;

/**
 * Class ReferralEvaluationResult.
 */
class ReferralEvaluationResult extends EvaluationResult
{
    /**
     * @var string
     */
    protected $rewardType;

    /**
     * @var InvitationDetails
     */
    protected $invitation;

    public function __construct($earningRuleId, $points, $rewardType, InvitationDetails $invitationDetails)
    {
        parent::__construct($earningRuleId, $points);
        $this->rewardType = $rewardType;
        $this->invitation = $invitationDetails;
    }

    /**
     * @return string
     */
    public function getRewardType()
    {
        return $this->rewardType;
    }

    /**
     * @return InvitationDetails
     */
    public function getInvitation()
    {
        return $this->invitation;
    }
}
