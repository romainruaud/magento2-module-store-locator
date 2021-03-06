<?php
/**
 * DISCLAIMER
 * Do not edit or add to this file if you wish to upgrade Smile Elastic Suite to newer
 * versions in the future.
 *
 * @category  Smile
 * @package   Smile\LocalizedRetailer
 * @author    Romain Ruaud <romain.ruaud@smile.fr>
 * @author    Guillaume Vrac <guillaume.vrac@smile.fr>
 * @copyright 2016 Smile
 * @license   Open Software License ("OSL") v. 3.0
 */
namespace Smile\StoreLocator\Block\Retailer;

use Magento\Framework\Registry;
use Magento\Framework\View\Element\Template\Context;
use Smile\StoreLocator\Helper\Data as StoreLocatorHelper;


/**
 * Retailer View Block
 *
 * @category Smile
 * @package  Smile\LocalizedRetailer
 * @author   Romain Ruaud <romain.ruaud@smile.fr>
 * @author   Guillaume Vrac <guillaume.vrac@smile.fr>
 */
class View extends AbstractView
{
    /**
     * @var StoreLocatorHelper
     */
    private $storeLocatorHelper;

    /**
     * Constructor.
     *
     * @param \Magento\Framework\View\Element\Template\Context $context            Application context.
     * @param \Magento\Framework\Registry                      $coreRegistry       Application registry.
     * @param \Smile\StoreLocator\Helper\Data                  $storeLocatorHelper Store locator helper.
     * @param array                                            $data               Block Data.
     */
    public function __construct(
        Context $context,
        Registry $coreRegistry,
        StoreLocatorHelper $storeLocatorHelper,
        array $data = []
    ) {
        parent::__construct($context, $coreRegistry, $data);
        $this->storeLocatorHelper = $storeLocatorHelper;
    }

    /**
     * @SuppressWarnings(PHPMD.CamelCaseMethodName)
     * {@inheritdoc}
     */
    protected function _prepareLayout()
    {
        parent::_prepareLayout();

        $this->setPageTitle()
            ->setPageMeta()
            ->setBreadcrumbs();

        return $this;
    }

    private function setPageTitle()
    {
        $retailer = $this->getRetailer();

        $titleBlock = $this->getLayout()->getBlock('page.main.title');

        if ($titleBlock) {
            $titleBlock->setPageTitle($retailer->getName());
        }

        $pageTitle = $retailer->getMetaTitle();
        if (empty($pageTitle)) {
            $title = $retailer->getName();
        }
        $this->pageConfig->getTitle()->set(__($pageTitle));

        return $this;
    }

    private function setPageMeta()
    {
        $retailer = $this->getRetailer();

        $keywords = $retailer->getMetaKeywords();
        if ($keywords) {
            $this->pageConfig->setKeywords($retailer->getMetaKeywords());
        }

        // Set the page description.
        $description = $retailer->getMetaDescription();
        if ($description) {
            $this->pageConfig->setDescription($retailer->getMetaDescription());
        }

        return $this;
    }

    private function setBreadcrumbs()
    {
        if ($breadcrumbsBlock = $this->getLayout()->getBlock('breadcrumbs')) {
            $retailer = $this->getRetailer();

            $breadcrumbsBlock->addCrumb(
                'home',
                [
                    'label' => __('Home'),
                    'title' => __('Go to Home Page'),
                    'link' => $this->_storeManager->getStore()->getBaseUrl()
                ]
            );

            $breadcrumbsBlock->addCrumb(
                'search',
                [
                    'label' => __('Our stores'),
                    'title' => __('Our stores'),
                    'link' => $this->storeLocatorHelper->getBaseUrl(),
                ]
            );

            $breadcrumbsBlock->addCrumb('store', ['label' => $retailer->getName(), 'title' => $retailer->getName()]);
        }
    }
}