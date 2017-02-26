<?php

namespace DictionaryBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\Post;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\View\View;

class DictionaryController extends FOSRestController
{
    /**
     * @Route("/")
     */
    public function indexAction(Request $request)
    {
        return $this->render('DictionaryBundle:dictionary:index.html.twig');
    }

    /**
     * @Post("/begin-examination")
     */
    public function beiginExaminationAction(Request $request)
    {
        $request->getSession()->remove('examination');

        $data = json_decode($request->getContent(), true);

        $examinationService = $this->get('examination');
        $examinationService->init($data['username']);

        $view = $this->view([
            'success' => true
        ]);
        $view->setFormat('json');

        return $this->handleView($view);
    }

    /**
     * @Get("/get-next-question")
     */
    public function getNextQuestionAction()
    {
        $examinationService = $this->get('examination');

        $question = $examinationService->getNextQuestion();

        if (null !== $question) {
            $returnData = [
                'complete' => false,
                'question' => $examinationService->extractQuestion($question)
            ];
        } else {
            $returnData = [
                'complete'    => true,
                'score'       => $examinationService->getExamination()->getScore(),
                'wrong_count' => $examinationService->getExamination()->getWrongCount()
            ];
        }

        $view = $this->view($returnData);
        $view->setFormat('json');

        return $this->handleView($view);
    }

    /**
     * @Get("check-answer")
     */
    public function checkAnswerAction(Request $request)
    {
        $data = [
            'question'  => $request->get('question'),
            'answer'    => $request->get('answer')
        ];

        $examinationService = $this->get('examination');
        $check = $examinationService->checkAnswer($data['answer']);

        if (($check && $examinationService->isComplete()) || (!$check && $examinationService->isLimitExceeded())) {
            $returnData = [
                'complete'    => true,
                'score'       => $examinationService->getExamination()->getScore(),
                'wrong_count' => $examinationService->getExamination()->getWrongCount()
            ];
        } else {
            $returnData = [
                'complete' => false,
                'check'    => $check
            ];
        }

        $view = $this->view($returnData);
        $view->setFormat('json');

        return $this->handleView($view);

    }

}