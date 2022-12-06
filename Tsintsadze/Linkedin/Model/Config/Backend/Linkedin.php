<?php

namespace Giorgi\Tsignadze\Model\Config\Backend;

namespace Tsintsadze\Linkedin\Model\Config\Backend;

use Magento\Customer\Api\CustomerMetadataInterface;
use Magento\Customer\Setup\CustomerSetup;
use Magento\Framework\App\Cache\TypeListInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\Config\Value;
use Magento\Framework\Data\Collection\AbstractDb;
use Magento\Framework\Model\Context;
use Magento\Framework\Model\ResourceModel\AbstractResource;
use Magento\Framework\Registry;
use Tsintsadze\Linkedin\Setup\Patch\Data\AddLinkedin;

class Linkedin extends Value
{
    private CustomerSetup $CustomerSetup;

    public function __construct(
        Context $context,
        Registry $registry,
        CustomerSetup $customerSetup,
        ScopeConfigInterface $config,
        TypeListInterface $cacheTypeList,
        AbstractResource $resource = null,
        AbstractDb $resourceCollection = null,
        array $data = []
    ) {
        parent::__construct(
            $context,
            $registry,
            $config,
            $cacheTypeList,
            $resource,
            $resourceCollection,
            $data
        );
        $this->CustomerSetup=$customerSetup;
    }

    public function UpdateRequirement($required, $attributeCode)
    {
        $returnAttribute = $this->CustomerSetup->getEavConfig()->getAttribute(CustomerMetadataInterface::ENTITY_TYPE_CUSTOMER, $attributeCode);
        if ($returnAttribute->getIsRequired() !== $required) {
            $this->CustomerSetup->updateAttribute(
                CustomerMetadataInterface::ENTITY_TYPE_CUSTOMER,
                $attributeCode,
                'is_required',
                $required
            );
        }
    }

    public function afterSave()
    {
        $value = $this->getValue();
        if ($value === '0' || $value === '1') {
            $this->UpdateRequirement(false, AddLinkedin::Linkedin_Customer);
        } else {
            $this->UpdateRequirement(true, AddLinkedin::Linkedin_Customer);
        }
        return parent::afterSave();
    }
}
