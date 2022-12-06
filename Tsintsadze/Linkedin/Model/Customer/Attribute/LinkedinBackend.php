<?php

namespace Tsintsadze\Linkedin\Model\Customer\Attribute;

use Tsintsadze\Linkedin\Setup\Patch\Data\AddLinkedin;
use Magento\Eav\Model\Entity\Attribute\Backend\AbstractBackend;
use Magento\Framework\Exception\LocalizedException;

class LinkedinBackend extends AbstractBackend
{

    /**
     * @param $object
     * @return \Magento\Framework\DataObject|LinkedinBackend
     * @throws LocalizedException
     */
    public function beforeSave($object)
    {
        $objLink = $object->getData(AddLinkedin::Linkedin_Customer);

        if (!$this->validateLinkedin($objLink)) {
            throw new LocalizedException(
                __('Provided linkedin profile url is wrong')
            );
        }

        $this->validate($object);

        return $object;
    }

    private function validateLinkedin(string $profileUrl): bool
    {
        $pattern = '/^(http(s)?:\/\/)?([\w]+\.)?linkedin\.com\/(pub|in|profile)/m';

        return preg_match($pattern, $profileUrl);
    }
}
