<?php

namespace App\DataFixtures;

use App\Entity\Question;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Response;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $question = new Question();
        $question->setName('name  of new question')
            ->setSlug('name of new question'.rand(1,100))
            ->setQuestion('qqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqq
            qqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqq')
            ->setAskedAt(new \DateTime('now'));
        //dd($question);

        $question->setVotes(rand(0,50));
        $manager->persist($question);

        $manager->flush();
    }
}
