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

namespace Perspective\Certification\Api;

interface AnswerRepositoryInterface
{
    /**
     * Save Answer
     * @param \Perspective\Certification\Api\Data\AnswerInterface $answer
     * @return \Perspective\Certification\Api\Data\AnswerInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function save(
        \Perspective\Certification\Api\Data\AnswerInterface $answer
    );

    /**
     * Retrieve Answer
     * @param string $answerId
     * @return \Perspective\Certification\Api\Data\AnswerInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function get($answerId);

    /**
     * Retrieve Answer matching the specified criteria.
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \Perspective\Certification\Api\Data\AnswerSearchResultsInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getList(
        \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
    );

    /**
     * Delete Answer
     * @param \Perspective\Certification\Api\Data\AnswerInterface $answer
     * @return bool true on success
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function delete(
        \Perspective\Certification\Api\Data\AnswerInterface $answer
    );

    /**
     * Delete Answer by ID
     * @param string $answerId
     * @return bool true on success
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function deleteById($answerId);
}
