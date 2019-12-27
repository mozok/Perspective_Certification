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

namespace Perspective\Certification\Api\Data;

interface AnswerInterface extends \Magento\Framework\Api\ExtensibleDataInterface
{
    const ANSWER_ID = 'answer_id';
    const QUESTION_ID = 'question_id';
    const DESCRIPTION = 'description';
    const IS_TRUE = 'is_true';

    /**
     * Get answer_id
     * @return string|null
     */
    public function getAnswerId();

    /**
     * Set answer_id
     * @param string $answerId
     * @return \Perspective\Certification\Api\Data\AnswerInterface
     */
    public function setAnswerId($answerId);

    /**
     * Get question_id
     * @return string|null
     */
    public function getQuestionId();

    /**
     * Set question_id
     * @param string $questionId
     * @return \Perspective\Certification\Api\Data\AnswerInterface
     */
    public function setQuestionId($questionId);

    /**
     * Get description
     * @return string|null
     */
    public function getDescription();

    /**
     * Set description
     * @param string $description
     * @return \Perspective\Certification\Api\Data\AnswerInterface
     */
    public function setDescription($description);

    /**
     * Get is_true
     * @return string|null
     */
    public function getIsTrue();

    /**
     * Set is_true
     * @param string $isTrue
     * @return \Perspective\Certification\Api\Data\AnswerInterface
     */
    public function setIsTrue($isTrue);

    /**
     * Retrieve existing extension attributes object or create a new one.
     * @return \Perspective\Certification\Api\Data\AnswerExtensionInterface|null
     */
    public function getExtensionAttributes();

    /**
     * Set an extension attributes object.
     * @param \Perspective\Certification\Api\Data\AnswerExtensionInterface $extensionAttributes
     * @return $this
     */
    public function setExtensionAttributes(
        \Perspective\Certification\Api\Data\AnswerExtensionInterface $extensionAttributes
    );
}
