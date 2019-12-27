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

use Perspective\Certification\Api\Data\TypeInterface;

class Type extends \Magento\Framework\Api\AbstractExtensibleObject implements TypeInterface
{
    /**
     * Get entity id
     * @return string|null
     */
    public function getId()
    {
        return $this->_get(self::TYPE_ID);
    }

    /**
     * Set entity id
     * @param string $id
     * @return \Perspective\Certification\Api\Data\TypeInterface
     */
    public function setId($id)
    {
        return $this->setData(self::TYPE_ID, $id);
    }

    /**
     * Get type_id
     * @return string|null
     */
    public function getTypeId()
    {
        return $this->_get(self::TYPE_ID);
    }

    /**
     * Set type_id
     * @param string $typeId
     * @return \Perspective\Certification\Api\Data\TypeInterface
     */
    public function setTypeId($typeId)
    {
        return $this->setData(self::TYPE_ID, $typeId);
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
     * @return \Perspective\Certification\Api\Data\TypeInterface
     */
    public function setDescription($description)
    {
        return $this->setData(self::DESCRIPTION, $description);
    }

    /**
     * Retrieve existing extension attributes object or create a new one.
     * @return \Perspective\Certification\Api\Data\TypeExtensionInterface|null
     */
    public function getExtensionAttributes()
    {
        return $this->_getExtensionAttributes();
    }

    /**
     * Set an extension attributes object.
     * @param \Perspective\Certification\Api\Data\TypeExtensionInterface $extensionAttributes
     * @return $this
     */
    public function setExtensionAttributes(
        \Perspective\Certification\Api\Data\TypeExtensionInterface $extensionAttributes
    ) {
        return $this->_setExtensionAttributes($extensionAttributes);
    }
}
