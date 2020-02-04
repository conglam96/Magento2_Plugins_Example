<?php
/**
 
 */

namespace PHPCuong\TextLink\Plugin\Catalog\Helper;

class Output
{
    /**
     * Eav config
     *
     * @var \Magento\Eav\Model\Config
     */
    protected $_eavConfig;

    /**
     * Url
     *
     * @var \Magento\Framework\UrlInterface
     */
    protected $_urlInterface;

    /**
     * @param \Magento\Eav\Model\Config $eavConfig
     * @param \Magento\Framework\UrlInterface $_urlInterface
     */
    public function __construct(
        \Magento\Eav\Model\Config $eavConfig,
        \Magento\Framework\UrlInterface $urlInterface
    ) {
        $this->_eavConfig = $eavConfig;
        $this->_urlInterface = $urlInterface;
    }

    /**
     * @param \Magento\Catalog\Helper\Output $output
     * @param callable $proceed
     * @param \Magento\Catalog\Model\Product $product
     * @param string $attributeHtml
     * @param string $attributeName
     * @return string
     */
    public function aroundProductAttribute(
        \Magento\Catalog\Helper\Output $output,
        callable $proceed,
        \Magento\Catalog\Model\Product $product,
        $attributeHtml,
        $attributeName
    ) {
        $result = $proceed($product, $attributeHtml, $attributeName);
        $attribute = $this->_eavConfig->getAttribute(\Magento\Catalog\Model\Product::ENTITY, $attributeName);
        if ($attribute &&
            $attribute->getId() &&
            ($attribute->getAttributeCode() == 'description' || $attribute->getAttributeCode() == 'short_description')
        ) {
            $textLink = 'gray';
            $textLinkUrl = $this->_urlInterface->getUrl('catalogsearch/result', ['q' => $textLink]);
            $result = preg_replace('/'.$textLink.'/i', '<a href="'.$textLinkUrl.'"><b>'.$textLink.'</b></a>', $result);
        }

        return $result;
    }
}