<?php


namespace Perspective\Certification\Model\Type;

use Magento\Framework\Data\OptionSourceInterface;

class OptionProvider implements OptionSourceInterface
{
    /** @var \Perspective\Certification\Model\ResourceModel\Type\CollectionFactory */
    protected $collectionFactory;

    /**
     * OptionProvider constructor.
     * @param \Perspective\Certification\Model\ResourceModel\Type\CollectionFactory $collectionFactory
     */
    public function __construct(
        \Perspective\Certification\Model\ResourceModel\Type\CollectionFactory $collectionFactory
    ) {
        $this->collectionFactory = $collectionFactory;
    }

    /**
     * @return array
     */
    public function toOptionArray()
    {
        $options = [];
        $options[] = [
            'value' => null,
            'label' => '- Please Select Certification Type -'
        ];
        /** @var \Perspective\Certification\Model\ResourceModel\Type\Collection $collection */
        $collection = $this->collectionFactory->create();

        foreach ($collection as $item) {
            $options[] = [
                'value' => $item->getId(),
                'label' => $item->getDescription()
            ];
        }

        return $options;
    }
}
