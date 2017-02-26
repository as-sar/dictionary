<?php

namespace DictionaryBundle\Entity;

/**
 * Class Question
 *
 * @package DictionaryBundle\Entity
 */
class Question
{
    const DIRECTION_ENG_TO_RUS = 0;

    const DIRECTION_RUS_TO_ENG = 1;

    /**
     * @var string
     */
    protected $question;

    /**
     * @var array
     */
    protected $variants = array();

    /**
     * @var mixed
     */
    protected $rightAnswer;

    /**
     * @param string $question
     *
     * @return $this
     */
    public function setQuestion($question)
    {
        $this->question = $question;
        return $this;
    }

    /**
     * @return string | null
     */
    public function getQuestion()
    {
        return $this->question;
    }

    /**
     * @param array $variants
     *
     * @return $this
     */
    public function setVariants(array $variants)
    {
        $this->variants = $variants;
        return $this;
    }

    /**
     * @return array
     */
    public function getVariants()
    {
        return $this->variants;
    }

    /**
     * @param mixed $rightAnswer
     *
     * @return $this
     */
    public function setRightAnswer($rightAnswer)
    {
        $this->rightAnswer = $rightAnswer;
        return $this;
    }

    /**
     * @return mixed | null
     */
    public function getRightAnswer()
    {
        return $this->rightAnswer;
    }

}