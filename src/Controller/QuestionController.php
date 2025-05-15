<?php

namespace App\Controller;

use App\Entity\Question;
use App\Repository\AnswerRepository;
use App\Service\MarkdownHelper;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class QuestionController extends AbstractController
{
    private $logger;
    private $isDebug;

    public function __construct(LoggerInterface $logger, bool $isDebug)
    {
        $this->logger = $logger;
        $this->isDebug = $isDebug;
    }


    /**
     * @Route("/", name="app_homepage")
     */
    public function homepage(EntityManagerInterface $entityManager): Response
    {
        $repository = $entityManager->getRepository(Question::class);
        //$questions = $repository->findBy([],['askedAt' => 'DESC']);
        //dd($questions);
        $questions=$repository->findAllAskedOrderedByNewest();
        return $this->render('question/homepage.html.twig',['questions'=>$questions]);
    }
    /**
     * @Route("/questions/new")
     */
    public function new(EntityManagerInterface $entityManager)
    {

        return new Response('time for some doctrine magic');
    }
    /**
     * @Route("/questions/{slug}", name="app_question_show")
     */
    public function show($slug,EntityManagerInterface $entityManager, AnswerRepository $answerRepository): Response
    {
        if ($this->isDebug) {
            $this->logger->info('We are in debug mode!');
        }
        $repository = $entityManager->getRepository(Question::class);
        /** @var Question|null $question */
        $question =$repository->findOneBy(['slug' => $slug]);

        if(!$question) {
            throw $this->createNotFoundException('no question found for slug '.$slug);
        }
        //no need of above code as we have used paramconverter from the SensioFrameworkExtraBundle using just the entity class Question $question

        //$answers=$answerRepository->findBy(['question'=>$question]);

        //now as we have allowed to add answers property to question class
        //$answers = $question->getAnswers();
        //dd($answers);

//
        return $this->render('question/show.html.twig', [
            'question' => $question,
            //'answers' => $answers,
        ]);
    }

    /**
     * @Route("/questions/{slug}/vote", name="app_question_vote", methods="POST")
     */
    public function questionVote(Question $question, Request $request, EntityManagerInterface $entityManager)
    {
        $direction = $request->request->get('direction');
        if ($direction === 'up') {
            $question->upVote();
        } elseif ($direction === 'down') {
            $question->downVote();
        }
        $entityManager->flush();
        return $this->redirectToRoute('app_question_show', [
            'slug' => $question->getSlug()
        ]);

        //dd($request->request->all(),$question);
    }

}
