<?php

namespace DictionaryBundle\Entity;

/**
 * Class QuestionFactory
 *
 * @package DictionaryBundle\Entity
 */
class QuestionFactory
{
    /**
     * @return Question
     */
    public function createQuestion()
    {
        return new Question();
    }
}