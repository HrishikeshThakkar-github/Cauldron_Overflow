<?php

namespace App\DataFixtures;

use App\Entity\Answer;
use App\Entity\Question;
use App\Factory\AnswerFactory;
use App\Factory\QuestionFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Response;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
//        $question = new Question();
//        $question->setName('name  of new question')
//            ->setSlug('name of new question'.rand(1,100))
//            ->setQuestion('qqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqq
//            qqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqq')
//            ->setAskedAt(new \DateTime('now'));
//        //dd($question);
//
//        $question->setVotes(rand(0,50));
        $question=  QuestionFactory::new()->createMany(20);

        QuestionFactory::new()
            ->unpublished()
            ->createMany(5)
        ;

        AnswerFactory::new()->createMany(100,function() use ($question){
           return [
                'question'=> $question[array_rand($question)],
            ];
        });

        AnswerFactory::new( function () use ($question){
            return [
                'question'=> $question[array_rand($question)],
            ];
        })->need_approval()->many(20)->create();
//        $question= QuestionFactory::createOne();
//        $answer1=new Answer();
//        $answer1->setContent('answer1');
//        $answer1->setUsername('hrishi');
//
//        $answer2=new Answer();
//        $answer2->setContent('answer2');
//        $answer2->setUsername('abcdefghijklmnopqstuvwxyz');
//
//        $question->addAnswer($answer1);
//        $question->addAnswer($answer2);
//
//        $manager->persist($answer1);
//        $manager->persist($answer2);

        $manager->flush();
    }
}
