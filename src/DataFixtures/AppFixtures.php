<?php

namespace App\DataFixtures;

use App\Entity\Conference;
use App\Repository\ConferenceRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;
use App\Entity\Comment;

class AppFixtures extends Fixture
{
    protected $conferenceRepository;

    public function __construct(ConferenceRepository $conferenceRepository)
    {
        $this->conferenceRepository = $conferenceRepository;
    }

    /** @var Generator */
    protected $faker;

    public function createConference(ObjectManager $manager): Conference
    {
        $conference = new Conference();
        $conference->setCity($this->faker->city);
        $conference->setIsInternational($this->faker->boolean(60));
        $conference->setYear($this->faker->year($max = 'now'));
        $manager->persist($conference);
        $manager->flush();
        return $conference;
    }

    public function createComment(Conference $conference, ObjectManager $manager)
    {
        $comment = new Comment();
        $comment->setAuthor($this->faker->name);
        $comment->setCreatedAt(new \DateTime());
        $comment->setEmail($this->faker->email);
        $comment->setText($this->faker->sentence(10));
        $comment->setState('published');
        $comment->setConference($conference);
        $manager->persist($comment);
        $manager->flush();
    }

    public function load(ObjectManager $manager)
    {
        /* manual data */
        $con = new Conference();
        $con->setCity('Amsterdam');
        $con->setIsInternational(true);
        $con->setYear(2019);
        $manager->persist($con);
        $manager->flush();

        $this->faker = Factory::create();

        for ($i = 1; $i < 100; $i++) {
            $conference = $this->createConference($manager);
        }

        $conferences = $this->conferenceRepository->findAll();
        foreach ($conferences as $conference) {
            $this->createComment($conference, $manager);
            $this->createComment($conference, $manager);
            $this->createComment($conference, $manager);
            $this->createComment($conference, $manager);
            $this->createComment($conference, $manager);
            $this->createComment($conference, $manager);
            $this->createComment($conference, $manager);
            $this->createComment($conference, $manager);
            $this->createComment($conference, $manager);
            $this->createComment($conference, $manager);
            $this->createComment($conference, $manager);
            $this->createComment($conference, $manager);
        }
    }
}
