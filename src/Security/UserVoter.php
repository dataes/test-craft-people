<?php

namespace App\Security;

use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Authorization\AccessDecisionManagerInterface;

class UserVoter extends Voter
{
    // Permission
    const CAN_INITIALIZE_DECK = 'canInitialiseDeck';
    const CAN_PICK_CARD = 'canPickCard';
    // Roles
    const ROLE_PLAYER = 'ROLE_PLAYER';
    const ROLE_SYSTEM = 'ROLE_SYSTEM';

    /**
     * @var AccessDecisionManagerInterface
     */
    private $decisionManager;

    /**
     * UserVoter constructor.
     * @param AccessDecisionManagerInterface $decisionManager
     */
    public function __construct(AccessDecisionManagerInterface $decisionManager)
    {
        $this->decisionManager = $decisionManager;
    }

    /**
     * @param string $attribute
     * @param mixed $subject
     * @return bool
     */
    protected function supports($attribute, $subject)
    {
        // if the attribute isn't one we support, return false
        if (!in_array($attribute, [self::CAN_INITIALIZE_DECK, self::CAN_PICK_CARD])) {
            return false;
        }
        // only vote on User objects inside this voter
        if (!$subject instanceof User) {
            return false;
        }

        return true;
    }

    /**
     * @param string $attribute
     * @param mixed $subject
     * @param TokenInterface $token
     * @return bool
     */
    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        $user = $token->getUser();

        if (!$user instanceof User) {
            return false;
        }

        switch ($attribute) {
            case self::CAN_INITIALIZE_DECK:
                return $this->canInitializeDeck($user);
            case self::CAN_PICK_CARD:
                return $this->canPickCard($user);
            default:
                return false;
        }
    }

    /**
     * ROLE_SYSTEM can initialize a deck, can not pick a card.
     *
     * @param User $user
     * @return bool
     */
    private function canInitializeDeck(User $user)
    {
        return in_array(self::ROLE_SYSTEM, $user->getRoles());
    }

    /**
     * ROLE_PLAYER can pick a card, can not initialize a deck.
     *
     * @param User $user
     * @return bool
     */
    private function canPickCard(User $user)
    {
        return in_array(self::ROLE_PLAYER, $user->getRoles());
    }
}