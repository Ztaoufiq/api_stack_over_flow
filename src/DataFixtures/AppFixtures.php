<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use App\Entity\User;
use App\Entity\Answer;
use App\Entity\Question;

/**
 * Class AppFixtures
 * @package App\DataFixtures
 */
class AppFixtures extends Fixture
{
    private UserPasswordHasherInterface $passwordHasher;

    /**
     * AppFixtures constructor.
     * @param UserPasswordHasherInterface $passwordHasher
     */
    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    /*public function load(ObjectManager $manager): void
    {
        for ($i = 1; $i <= 10; $i++) {
            $user = User::create(
                sprintf("email+%d@email.com", $i),
                sprintf("name+%d", $i)
            );
            $user->setPassword($this->passwordHasher->hashPassword($user, "password"));
            $manager->persist($user);
        }

        $manager->flush();
    }*/
    public function load(ObjectManager $manager)
    {
        $users = [];
        for ($i = 1; $i <= 10; $i++) {
            $user = User::create(
                sprintf("email+%d@email.com", $i),
                sprintf("name+%d", $i)
            );
            $user->setPassword($this->passwordHasher->hashPassword($user, "password"));
            $manager->persist($user);

            $users[] = $user;
        }

        foreach ($users as $user) {
            for ($j = 1; $j <= 5; $j++) {
                $question = Question::create("Title","Content", $user);

                /*shuffle($users);

                foreach (array_slice($users, 0, 5) as $userCanLike) {
                    $question->likeBy($userCanLike);
                }*/

                $manager->persist($question);

                for ($k = 1; $k <= 10; $k++) {
                    $answer = Answer::create(sprintf("Response %d", $k), $users[array_rand($users)], $question);
                    shuffle($users);

                    foreach (array_slice($users, 0, 5) as $userValid) {
                        $answer->setValidatedBy($userValid);
                    }
                    $manager->persist($answer);
                }
            }
        }

        $manager->flush();
    }
}
