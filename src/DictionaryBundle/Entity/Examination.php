<?php

namespace DictionaryBundle\Entity;

/**
 * Class Examination
 *
 * @package DictionaryBundle\Entity
 */
class Examination
{
    const QUESTION_FORMAT_ARRAY = 'array';

    /**
     * @var string
     */
    protected $username;

    /**
     * @var \SplQueue
     */
    protected $questions;

    /**
     * @var int
     */
    protected $score = 0;

    /**
     * @var int
     */
    protected $wrongCount = 0;

    /**
     * Examination constructor.
     */
    public function __construct()
    {
        $this->questions = new \SplQueue();
    }

    /**
     * @param string $username
     *
     * @return $this
     */
    public function setUsername($username)
    {
        $this->username = $username;
        return $this;
    }

    /**
     * @return string | null
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @param \SplQueue $questions
     *
     * @return $this
     */
    public function setQuestions(\SplQueue $questions)
    {
        $this->questions = $questions;
        return $this;
    }

    /**
     * @return \SplQueue
     */
    public function getQuestions()
    {
        return $this->questions;
    }

    /**
     * @param Question $question
     *
     * @return $this
     */
    public function addQuestion(Question $question)
    {
        $this->questions->push($question);
        return $this;
    }

    /**
     * @return Question | null
     */
    public function getNextQuestion()
    {
        $question = null;

        if (!$this->questions->isEmpty()){
            $question = $this->questions->pop();
        }

        return $question;
    }

    /**
     * @return $this
     */
    public function addScore()
    {
        $this->score++;
        return $this;
    }

    /**
     * @return int
     */
    public function getScore()
    {
        return $this->score;
    }

    /**
     * @return $this
     */
    public function addWrong()
    {
        $this->wrongCount++;
        return $this;
    }

    /**
     * @return int
     */
    public function getWrongCount()
    {
        return $this->wrongCount;
    }


}