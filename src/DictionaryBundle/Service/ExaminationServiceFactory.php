<?php

namespace DictionaryBundle\Service;

use DictionaryBundle\Entity\Examination;
use DictionaryBundle\Service\ExaminationService;
use Symfony\Component\HttpFoundation\RequestStack;
use DictionaryBundle\Mapper\DictionaryMapperInterface;
use DictionaryBundle\Entity\QuestionFactory;

/**
 * Class ExaminationServiceFactory
 *
 * @package DictionaryBundle\Service
 */
class ExaminationServiceFactory
{
    /**
     * @param                           $questionsCount
     * @param RequestStack              $requestStack
     * @param DictionaryMapperInterface $dictionaryMapper
     * @param QuestionFactory           $questionFactory
     *
     * @return \DictionaryBundle\Service\ExaminationService
     */
    public function createSerivce(
        $questionsCount,
        RequestStack $requestStack,
        DictionaryMapperInterface $dictionaryMapper,
        QuestionFactory $questionFactory
    ){
        $request = $requestStack->getCurrentRequest();
        $session = $request->getSession();

        if (!$session->get('examination')) {
            $examination = new Examination();
            $session->set('examination', $examination);
        }

        $service = new ExaminationService(
            $questionsCount,
            $dictionaryMapper,
            $questionFactory,
            $session
        );

        return $service;
    }
}