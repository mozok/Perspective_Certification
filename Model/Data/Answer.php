<?php
/**
 * Copyright (c) 2019
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
 */

namespace Perspective\Certification\Model\Data;

use Perspective\Certification\Api\Data\AnswerInterface;

class Answer extends \Magento\Framework\Api\AbstractExtensibleObject implements AnswerInterface
{
    /**
     * Get entity id
     * @return string|null
     */
    public function getId()
    {
        return $this->_get(self::ANSWER_ID);
    }

    /**
     * Set entity id
     * @param string $id
     * @return \Perspective\Certification\Api\Data\AnswerInterface
     */
    public function setId($id)
    {
        return $this->setData(self::ANSWER_ID, $id);
    }

    /**
     * Get answer_id
     * @return string|null
     */
    public function getAnswerId()
    {
        return $this->_get(self::ANSWER_ID);
    }

    /**
     * Set answer_id
     * @param string $answerId
     * @return \Perspective\Certification\Api\Data\AnswerInterface
     */
    public function setAnswerId($answerId)
    {
        return $this->setData(self::ANSWER_ID, $answerId);
    }

    /**
     * Get question_id
     * @return string|null
     */
    public function getQuestionId()
    {
        return $this->_get(self::QUESTION_ID);
    }

    /**
     * Set question_id
     * @param string $questionId
     * @return \Perspective\Certification\Api\Data\AnswerInterface
     */
    public function setQuestionId($questionId)
    {
        return $this->setData(self::QUESTION_ID, $questionId);
    }

    /**
     * Get description
     * @return string|null
     */
    public function getDescription()
    {
        return $this->_get(self::DESCRIPTION);
    }

    /**
     * Set description
     * @param string $description
     * @return \Perspective\Certification\Api\Data\AnswerInterface
     */
    public function setDescription($description)
    {
        return $this->setData(self::DESCRIPTION, $description);
    }

    /**
     * Get is_true
     * @return string|null
     */
    public function getIsTrue()
    {
        return $this->_get(self::IS_TRUE);
    }

    /**
     * Set is_true
     * @param string $isTrue
     * @return \Perspective\Certification\Api\Data\AnswerInterface
     */
    public function setIsTrue($isTrue)
    {
        return $this->setData(self::IS_TRUE, $isTrue);
    }

    /**
     * Retrieve existing extension attributes object or create a new one.
     * @return \Perspective\Certification\Api\Data\AnswerExtensionInterface|null
     */
    public function getExtensionAttributes()
    {
        return $this->_getExtensionAttributes();
    }

    /**
     * Set an extension attributes object.
     * @param \Perspective\Certification\Api\Data\AnswerExtensionInterface $extensionAttributes
     * @return $this
     */
    public function setExtensionAttributes(
        \Perspective\Certification\Api\Data\AnswerExtensionInterface $extensionAttributes
    ) {
        return $this->_setExtensionAttributes($extensionAttributes);
    }
}
