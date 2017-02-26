<?php

namespace DictionaryBundle\Service;

use DictionaryBundle\Entity\Examination;
use DictionaryBundle\Entity\Question;
use DictionaryBundle\Entity\QuestionFactory;
use DictionaryBundle\Mapper\DictionaryMapperInterface;
use Symfony\Component\HttpFoundation\Session\Session;

/**
 * Class ExaminationService
 *
 * @package DictionaryBundle\Service
 */
class ExaminationService
{
    const VARIANTS_OF_ANSWER = 4;

    const LIMIT_WRONG_ANSWERS = 3;

    /**
     * @var int
     */
    protected $questionsCount;

    /**
     * @var DictionaryMapperInterface
     */
    protected $dictionaryMapper;

    /**
     * @var QuestionFactory
     */
    protected $questionFactory;

    /**
     * @var Session
     */
    protected $session;

    /**
     * @var Examination
     */
    protected $examination;

    /**
     * ExaminationService constructor.
     *
     * @param                           $questionsCount
     * @param DictionaryMapperInterface $dictionaryMapper
     * @param QuestionFactory           $questionFactory
     * @param Session                   $session
     */
    public function __construct(
        $questionsCount,
        DictionaryMapperInterface $dictionaryMapper,
        QuestionFactory $questionFactory,
        Session $session
    ){
        $this->questionsCount = $questionsCount;

        $this->dictionaryMapper = $dictionaryMapper;

        $this->questionFactory = $questionFactory;

        $this->session = $session;
    }

    /**
     * @param $username
     *
     * @return $this
     */
    public function init($username)
    {
        $examination = $this->session->get('examination');
        $examination->setUsername($username);

        $words = $this->dictionaryMapper->findRandom($this->questionsCount);
        $wrongVariantsCount = $this->questionsCount * self::VARIANTS_OF_ANSWER;
        $wrongVariants = $this->dictionaryMapper->findRandom($wrongVariantsCount);

        $directions = [
            Question::DIRECTION_ENG_TO_RUS,
            Question::DIRECTION_RUS_TO_ENG
        ];

        foreach ($words as $word => $translation) {

            $direction = $directions[array_rand($directions)];

            $questionVariants = array_rand(
                array_diff($wrongVariants, [$translation]),
                self::VARIANTS_OF_ANSWER - 1
            );

            switch ($direction) {
                case Question::DIRECTION_ENG_TO_RUS:
                    $questionVariants = array_values(
                        array_intersect_key($wrongVariants, array_flip($questionVariants))
                    );
                    $questionWord = $word;
                    $questionRightAnswer = $translation;
                    break;
                case Question::DIRECTION_RUS_TO_ENG:
                    $questionWord = $translation;
                    $questionRightAnswer =  $word;
                    break;
            }

            $question = $this->questionFactory->createQuestion();
            $question
                ->setQuestion($questionWord)
                ->setRightAnswer($questionRightAnswer)
                ->setVariants($questionVariants);

            $examination->addQuestion($question);
        }

        $this->session->set('examination', $examination);
        $this->session->set('wrongs', []);

        return $this;
    }

    /**
     * @return Question|null
     */
    public function getNextQuestion()
    {
        $examination = $this->session->get('examination');
        $question = $examination->getNextQuestion();
        if (null !== $question) {
            $this->session->set('question', $question);
        } else {
            $this->session->remove('question');
        }

        return $question;
    }

    /**
     * @param Question $question
     *
     * @return array
     */
    public function extractQuestion(Question $question)
    {
        $data =  [
            'question'  => $question->getQuestion(),
            'variants'  => $question->getVariants(),
        ];
        $data['variants'][] = $question->getRightAnswer();

        return $data;
    }

    /**
     * @param string $answer
     *
     * @return bool
     */
    public function checkAnswer($answer)
    {
        $question = $this->session->get('question');
        $check = ($answer == $question->getRightAnswer());

        if ($check) {
            $this->getExamination()->addScore();
        } else {
            $this->getExamination()->addWrong();
            $question = $this->session->get('question');
            $wrongs = $this->session->get('wrongs');
            $text = $question->getQuestion();
            if (!isset($wrongs[$text][$answer])) {
                $wrongs[$text][$answer] = 0;
            }
            $wrongs[$text][$answer]++;
        }

        return $check;
    }

    /**
     * @return Examination|null
     */
    public function getExamination()
    {
        if (null === $this->examination) {
            $this->examination = $this->session->get('examination');
        }

        return $this->examination;
    }

    /**
     * @return bool
     */
    public function isComplete()
    {
        return $this->getExamination()->getQuestions()->count() == 0;

    }

    /**
     * @return bool
     */
    public function isLimitExceeded()
    {
        return ($this->getExamination()->getWrongCount() >= self::LIMIT_WRONG_ANSWERS);
    }

}