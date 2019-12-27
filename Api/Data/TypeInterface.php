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

interface TypeInterface extends \Magento\Framework\Api\ExtensibleDataInterface
{
    const TYPE_ID = 'type_id';
    const DESCRIPTION = 'description';

    /**
     * Get type_id
     * @return string|null
     */
    public function getTypeId();

    /**
     * Set type_id
     * @param string $typeId
     * @return \Perspective\Certification\Api\Data\TypeInterface
     */
    public function setTypeId($typeId);

    /**
     * Get description
     * @return string|null
     */
    public function getDescription();

    /**
     * Set description
     * @param string $description
     * @return \Perspective\Certification\Api\Data\TypeInterface
     */
    public function setDescription($description);

    /**
     * Retrieve existing extension attributes object or create a new one.
     * @return \Perspective\Certification\Api\Data\TypeExtensionInterface|null
     */
    public function getExtensionAttributes();

    /**
     * Set an extension attributes object.
     * @param \Perspective\Certification\Api\Data\TypeExtensionInterface $extensionAttributes
     * @return $this
     */
    public function setExtensionAttributes(
        \Perspective\Certification\Api\Data\TypeExtensionInterface $extensionAttributes
    );
}
