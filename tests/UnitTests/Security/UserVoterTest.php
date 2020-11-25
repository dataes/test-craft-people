<?php

namespace App\Tests\UnitTests\Security;

use App\Entity\User;
use Faker\Factory;
use App\Security\UserVoter;
use PHPUnit\Framework\TestCase;
use ReflectionMethod;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\AccessDecisionManagerInterface;

class UserVoterTest extends TestCase
{
    protected $faker;
    protected $userVoter;

    public function setUp()
    {
        $this->faker = Factory::create();
        $this->userVoter = new UserVoter(
            $this->createMock(AccessDecisionManagerInterface::class)
        );
    }

    // supports() unit tests

    public function test_supports_return_false_if_attribute_is_not_supported()
    {
        $subject = new User();
        $attribute = $this->faker->name;

        $reflectedMethod = $this->reflectSupportsMethod();

        $this->assertEquals(
            false,
            $reflectedMethod->invokeArgs(
                $this->userVoter,
                [$attribute, $subject]
            )
        );
    }

    public function test_supports_return_true_if_attribute_is_supported()
    {
        $subject = new User();
        $attribute1 = UserVoter::CAN_PICK_CARD;
        $attribute2 = UserVoter::CAN_INITIALIZE_DECK;

        $reflectedMethod = $this->reflectSupportsMethod();

        $this->assertEquals(
            true,
            $reflectedMethod->invokeArgs(
                $this->userVoter,
                [$attribute1, $subject]
            )
        );
        $this->assertEquals(
            true,
            $reflectedMethod->invokeArgs(
                $this->userVoter,
                [$attribute2, $subject]
            )
        );
    }

    public function test_supports_return_false_if_subject_is_not_a_user_object()
    {
        $subject = $this->faker->name;
        $attribute = UserVoter::CAN_PICK_CARD;

        $reflectedMethod = $this->reflectSupportsMethod();

        $this->assertEquals(
            false,
            $reflectedMethod->invokeArgs(
                $this->userVoter,
                [$attribute, $subject]
            )
        );
    }

    // voteOnAttribute() unit tests

    public function test_voteOnAttribute_return_true_if_user_is_retrieved_through_token()
    {
        $attribute = UserVoter::CAN_PICK_CARD;
        $subject = new User();
        $subject->setRoles([UserVoter::ROLE_PLAYER]);
        $token = $this->createMock(TokenInterface::class);

        $token->expects($this->once())
            ->method('getUser')
            ->willReturn($subject);

        $reflectedMethod = $this->reflectVoteOnAttributeMethod();

        $this->assertEquals(
            true,
            $reflectedMethod->invokeArgs(
                $this->userVoter,
                [$attribute, $subject, $token]
            )
        );
    }

    public function test_voteOnAttribute_return_false_if_user_is_not_retrieved_through_token()
    {
        $attribute = UserVoter::CAN_PICK_CARD;
        $subject = $this->faker->name;
        $token = $this->createMock(TokenInterface::class);

        $token->expects($this->once())
            ->method('getUser')
            ->willReturn($subject);

        $reflectedMethod = $this->reflectVoteOnAttributeMethod();

        $this->assertEquals(
            false,
            $reflectedMethod->invokeArgs(
                $this->userVoter,
                [$attribute, $subject, $token]
            )
        );
    }

    public function test_voteOnAttribute_return_false_if_attribute_is_not_supported()
    {
        $attribute = $this->faker->name;
        $subject = new User();
        $subject->setRoles([UserVoter::ROLE_PLAYER]);
        $token = $this->createMock(TokenInterface::class);

        $token->expects($this->once())
            ->method('getUser')
            ->willReturn($subject);

        $reflectedMethod = $this->reflectVoteOnAttributeMethod();

        $this->assertEquals(
            false,
            $reflectedMethod->invokeArgs(
                $this->userVoter,
                [$attribute, $subject, $token]
            )
        );

        $attribute = $this->faker->name;
        $subject = new User();
        $subject->setRoles([UserVoter::ROLE_SYSTEM]);
        $token = $this->createMock(TokenInterface::class);

        $token->expects($this->once())
            ->method('getUser')
            ->willReturn($subject);

        $reflectedMethod = $this->reflectVoteOnAttributeMethod();

        $this->assertEquals(
            false,
            $reflectedMethod->invokeArgs(
                $this->userVoter,
                [$attribute, $subject, $token]
            )
        );
    }

    public function test_voteOnAttribute_return_true_if_attribute_is_supported()
    {
        $attribute = UserVoter::CAN_PICK_CARD;
        $subject = new User();
        $subject->setRoles([UserVoter::ROLE_PLAYER]);
        $token = $this->createMock(TokenInterface::class);

        $token->expects($this->once())
            ->method('getUser')
            ->willReturn($subject);

        $reflectedMethod = $this->reflectVoteOnAttributeMethod();

        $this->assertEquals(
            true,
            $reflectedMethod->invokeArgs(
                $this->userVoter,
                [$attribute, $subject, $token]
            )
        );

        $attribute = UserVoter::CAN_INITIALIZE_DECK;
        $subject = new User();
        $subject->setRoles([UserVoter::ROLE_SYSTEM]);
        $token = $this->createMock(TokenInterface::class);

        $token->expects($this->once())
            ->method('getUser')
            ->willReturn($subject);

        $reflectedMethod = $this->reflectVoteOnAttributeMethod();

        $this->assertEquals(
            true,
            $reflectedMethod->invokeArgs(
                $this->userVoter,
                [$attribute, $subject, $token]
            )
        );
    }

    // canInitializeDeck() unit tests

    public function test_canInitializeDeck_return_true_if_role_system()
    {
        $subject = new User();
        $subject->setRoles([UserVoter::ROLE_SYSTEM]);

        $reflectedMethod = $this->reflectCanInitializeDeckMethod();

        $this->assertEquals(
            true,
            $reflectedMethod->invokeArgs(
                $this->userVoter,
                [$subject]
            )
        );
    }

    public function test_canInitializeDeck_return_false_if_role_player()
    {
        $subject = new User();
        $subject->setRoles([UserVoter::ROLE_PLAYER]);

        $reflectedMethod = $this->reflectCanInitializeDeckMethod();

        $this->assertEquals(
            false,
            $reflectedMethod->invokeArgs(
                $this->userVoter,
                [$subject]
            )
        );
    }

    // canPickCard() unit tests

    public function test_canPickCard_return_true_if_role_player()
    {
        $subject = new User();
        $subject->setRoles([UserVoter::ROLE_PLAYER]);

        $reflectedMethod = $this->reflectCanPickCardMethod();

        $this->assertEquals(
            true,
            $reflectedMethod->invokeArgs(
                $this->userVoter,
                [$subject]
            )
        );
    }

    public function test_canPickCard_return_false_if_role_system()
    {
        $subject = new User();
        $subject->setRoles([UserVoter::ROLE_SYSTEM]);

        $reflectedMethod = $this->reflectCanPickCardMethod();

        $this->assertEquals(
            false,
            $reflectedMethod->invokeArgs(
                $this->userVoter,
                [$subject]
            )
        );
    }

    // reflected Protected Methods

    /**
     * @return ReflectionMethod
     */
    private function reflectSupportsMethod(): ReflectionMethod
    {
        $reflectedMethod = new ReflectionMethod(
            UserVoter::class,
            'supports'
        );
        $reflectedMethod->setAccessible(true);

        return $reflectedMethod;
    }

    /**
     * @return ReflectionMethod
     */
    private function reflectVoteOnAttributeMethod(): ReflectionMethod
    {
        $reflectedMethod = new ReflectionMethod(
            UserVoter::class,
            'voteOnAttribute'
        );
        $reflectedMethod->setAccessible(true);

        return $reflectedMethod;
    }

    /**
     * @return ReflectionMethod
     */
    private function reflectCanInitializeDeckMethod(): ReflectionMethod
    {
        $reflectedMethod = new ReflectionMethod(
            UserVoter::class,
            'canInitializeDeck'
        );
        $reflectedMethod->setAccessible(true);

        return $reflectedMethod;
    }

    /**
     * @return ReflectionMethod
     */
    private function reflectCanPickCardMethod(): ReflectionMethod
    {
        $reflectedMethod = new ReflectionMethod(
            UserVoter::class,
            'canPickCard'
        );
        $reflectedMethod->setAccessible(true);

        return $reflectedMethod;
    }

}