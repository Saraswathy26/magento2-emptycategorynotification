<?php

namespace Mageesh\EmptyCategoryNotification\Block;

use Exception;
use Mageesh\EmptyCategoryNotification\Helper\Config;
use Magento\Framework\App\Area;
use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Framework\Mail\Template\SenderResolverInterface;
use Magento\Framework\Mail\Template\TransportBuilder;
use Magento\Framework\Translate\Inline\StateInterface;
use Magento\Framework\Validator\EmailAddress;
use Magento\Framework\Validator\ValidatorChain;
use Magento\Store\Model\StoreManagerInterface;
use Psr\Log\LoggerInterface;


class Email extends AbstractHelper
{
    const EMAIL_TEMPLATE = 'empty_category_notification';
    protected StateInterface $inlineTranslation;

    protected TransportBuilder $transportBuilder;
    private Config $config;
    private StoreManagerInterface $storeManager;
    private SenderResolverInterface $senderResolver;
    private LoggerInterface $logger;

    /**
     * @param Context $context
     * @param StateInterface $inlineTranslation
     * @param TransportBuilder $transportBuilder
     * @param Config $config
     * @param StoreManagerInterface $storeManager
     * @param SenderResolverInterface $senderResolver
     */
    public function __construct(
        Context                 $context,
        StateInterface          $inlineTranslation,
        TransportBuilder        $transportBuilder,
        Config                  $config,
        StoreManagerInterface   $storeManager,
        SenderResolverInterface $senderResolver
    )
    {
        parent::__construct($context);
        $this->inlineTranslation = $inlineTranslation;
        $this->transportBuilder = $transportBuilder;
        $this->logger = $context->getLogger();
        $this->config = $config;
        $this->storeManager = $storeManager;
        $this->senderResolver = $senderResolver;
    }

    /**
     * @param null $categoryName
     * @param null $categoryUrl
     * @param null $eventTime
     * @return void
     */
    public function sendEmail($categoryName = null, $categoryUrl = null, $eventTime = null): void
    {
        try {
            $this->inlineTranslation->suspend();
            $sender = $this->config->getSenderEmailIdentity() ?: 'general';
            $managerName = __('Store Manager');
            if ($resolvedContact = $this->senderResolver->resolve($sender)) {
                $managerName = $resolvedContact['name'];
            }

            $transport = $this->transportBuilder
                ->setTemplateIdentifier(self::EMAIL_TEMPLATE)
                ->setTemplateOptions(
                    [
                        'area' => Area::AREA_FRONTEND,
                        'store' => $this->storeManager->getStore()->getId()
                    ]
                )
                ->setTemplateVars([
                    'managerName' => $managerName,
                    'categoryName' => $categoryName,
                    'categoryUrl' => $categoryUrl,
                    'eventTime' => $eventTime
                ])
                ->setFromByScope($sender);
            $toEmail = $this->config->getRecipientEmail();

            if ($toEmail && ValidatorChain::is($toEmail, EmailAddress::class)) {
                $transport->addTo($this->config->getRecipientEmail());
            }

            if ($this->config->getRecipientEmailCC()) {
                $ccEmail = explode(',', $this->config->getRecipientEmailCC());
                $transport->addBcc($ccEmail);
            }
            $transport->getTransport()
                ->sendMessage();
            $this->inlineTranslation->resume();
        } catch (Exception $e) {
            $this->logger->debug($e->getTraceAsString());
        }
    }
}
