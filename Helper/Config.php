<?php

namespace Mageesh\EmptyCategoryNotification\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Store\Model\ScopeInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;

class Config extends AbstractHelper
{
    const XML_PATH_ENABLE = "emptycategorynotification/general/enabled";

    protected $scopeConfig;

    const XML_PATH_RECIPIENT_EMAIL = "emptycategorynotification/email/recipient_email";

    const XML_PATH_RECIPIENT_EMAIL_CC = "emptycategorynotification/email/recipient_email_cc";

    const XML_PATH_SENDER_EMAIL_IDENTITY = "emptycategorynotification/email/sender_email_identity";

    const XML_PATH_MANAGER_NAME = "emptycategorynotification/email/manager_name";

    private Context $context;

    /**
     * @param Context $context
     * @param ScopeConfigInterface $scopeConfig
     */
    public function __construct(
        Context              $context,
        ScopeConfigInterface $scopeConfig
    ) {
        parent::__construct($context);
        $this->scopeConfig = $scopeConfig;
        $this->context = $context;
    }

    /**
     * {@inheritdoc}
     */
    public function isEnabled()
    {
        return $this->scopeConfig->isSetFlag(
            self::XML_PATH_ENABLE,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * @return mixed
     */
    public function getRecipientEmail()
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_RECIPIENT_EMAIL,
                    ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * @return mixed
     */
    public function getRecipientEmailCC()
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_RECIPIENT_EMAIL_CC,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * @return mixed
     */
    public function getSenderEmailIdentity()
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_SENDER_EMAIL_IDENTITY,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * @return mixed
     */
    public function getManagerName()
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_MANAGER_NAME,
            ScopeInterface::SCOPE_STORE
        );
    }
}

