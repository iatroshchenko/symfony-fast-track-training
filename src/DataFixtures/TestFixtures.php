<?php

namespace App\DataFixtures;

use App\Entity\Conference;
use App\Repository\ConferenceRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class TestFixtures extends Fixture
{
    private $conferenceRepository;

    public function __construct(ConferenceRepository $conferenceRepository)
    {
        $this->conferenceRepository = $conferenceRepository;
    }

    public function load(ObjectManager $manager)
    {
        $conference = new Conference();
        $conference->setCity('amsterdam');
        $conference->setYear('2019');
        $conference->setIsInternational(true);
        $conference->setSlug('amsterdam-2019');

        $manager->persist($conference);
        $manager->flush();


    }
}