<?php

namespace App\DataFixtures;

use App\Entity\Answer;
use App\Entity\Question;
use App\Entity\Tag;
use App\Factory\AnswerFactory;
use App\Factory\QuestionFactory;
use App\Factory\QuestionTagFactory;
use App\Factory\TagFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Response;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        TagFactory::createMany(100);

        $questions = QuestionFactory::createMany(20);

        QuestionTagFactory::createMany(100, function() {
            return [
                'tag' => TagFactory::random(),
                'question' => QuestionFactory::random(),
            ];
        });

        QuestionFactory::new()
            ->unpublished()
            ->many(5)
            ->create()
        ;

        AnswerFactory::createMany(100, function() use ($questions) {
            return [
                'question' => $questions[array_rand($questions)]
            ];
        });
//        AnswerFactory::new(function() use ($questions) {
//            return [
//                'question' => $questions[array_rand($questions)]
//            ];
//        })->needsApproval()->many(20)->create();

        $manager->flush();


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

    //        $question = QuestionFactory::new()->createOne();
    //        // Create two tags
    //        $tag1 = new Tag();
    //        $tag1->setName('Symfony');
    //
    //        $tag2 = new Tag();
    //        $tag2->setName('PHP');
    //
    //        // Relate the tags to the question
    //        $question->addTag($tag1);
    //        $question->addTag($tag2);
    //
    //        $manager->persist($tag1);
    //        $manager->persist($tag2);
    //        $manager->flush();
    }
}
