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

interface ResultInterface extends \Magento\Framework\Api\ExtensibleDataInterface
{
    const RESULT_ID = 'result_id';
    const SCORE = 'score';
    const ANSWERS = 'answers';

    /**
     * Get result_id
     * @return string|null
     */
    public function getResultId();

    /**
     * Set result_id
     * @param string $resultId
     * @return \Perspective\Certification\Api\Data\ResultInterface
     */
    public function setResultId($resultId);

    /**
     * Get answers
     * @return string|null
     */
    public function getAnswers();

    /**
     * Set answers
     * @param string $answers
     * @return \Perspective\Certification\Api\Data\ResultInterface
     */
    public function setAnswers($answers);

    /**
     * Get score
     * @return string|null
     */
    public function getScore();

    /**
     * Set score
     * @param string $score
     * @return \Perspective\Certification\Api\Data\ResultInterface
     */
    public function setScore($score);

    /**
     * Retrieve existing extension attributes object or create a new one.
     * @return \Perspective\Certification\Api\Data\ResultExtensionInterface|null
     */
    public function getExtensionAttributes();

    /**
     * Set an extension attributes object.
     * @param \Perspective\Certification\Api\Data\ResultExtensionInterface $extensionAttributes
     * @return $this
     */
    public function setExtensionAttributes(
        \Perspective\Certification\Api\Data\ResultExtensionInterface $extensionAttributes
    );
}
