<?php

namespace App\Security;

use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Authorization\AccessDecisionManagerInterface;

class UserVoter extends Voter
{
    const CAN_INITIALIZE_DECK = 'canInitialiseDeck';
    const CAN_PICK_CARD = 'canPickCard';

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
            // the user must be logged in; if not, deny access
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
     * @param User $user
     * @return bool
     */
    private function canInitializeDeck(User $user)
    {
        // ROLE_SYSTEM can initialize a deck, can not pick a card.
        return in_array('ROLE_SYSTEM', $user->getRoles());
    }

    /**
     * @param User $user
     * @return bool
     */
    private function canPickCard(User $user)
    {
        // ROLE_PLAYER can pick a card, can not initialize a deck.
        return in_array('ROLE_PLAYER', $user->getRoles());
    }
}