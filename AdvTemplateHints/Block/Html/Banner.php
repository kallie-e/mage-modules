<?php

namespace KallieExperiments\AdvTemplateHints\Block\Html;

use KallieExperiments\AdvTemplateHints\Model\Handles;
use KallieExperiments\AdvTemplateHints\Model\TemplateData;
use KallieExperiments\AdvTemplateHints\ModelView\Element\Template;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\View\Model\Layout\Merge;
use Magento\Framework\View\Element\Template\Context;

class Banner extends Template
{
    private const TAB = "&nbsp;&nbsp;&nbsp;&nbsp;";
    private const START = "start";
    private const COMBINED = "combo";
    private const END   = "end";

    /**
     * @var string $_layoutXml
     */
    private $_layoutXml;

    /**
     * @var Merge $_layoutMerge
     */
    private $_layoutMerge;

    /**
     * @var array
     */
    private $_globalHandles;

    /**
     * @param Context $context
     * @param TemplateData $templateData
     * @param Merge $layoutMerge
     * @param Handles $globalHandles
     * @param array $data
     * @throws LocalizedException
     */
    public function __construct(Context $context, TemplateData $templateData, Merge $layoutMerge, Handles $globalHandles, array $data = [])
    {
        parent::__construct($context, $templateData, $data);

        $this->_layoutXml = $this->parseXML($this->getLayout()->getXmlString());
        $this->_layoutMerge = $layoutMerge;
        $this->_globalHandles = $globalHandles;
    }

    /**
     * Check Advanced Templates option to see if Layout XML banner should be displayed
     *
     * @return boolean
     */
    public function displayAthBanner()
    {
        return $this->_scopeConfig->getValue(
            'dev/debug/advanced_template_hints',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * @return string
     */
    public function getLayoutHandles() {
        return join("<br />", array_keys($this->_globalHandles->getHandles()));
    }

    /**
     * Returns a striing representation of the layout XML for that page
     *
     * @return string
     */
    public function getLayoutXmlString() {
        return $this->_layoutXml;
    }

    /**
     * @param string $xmlString
     * @return string
     */
    private function parseXML(string $xmlString) {
        $parsedString = "";
        $layoutElements = explode('**SPACE**',
            str_replace('&gt;&lt;', '&gt;**SPACE**&lt;', htmlspecialchars($xmlString) ));

        $tabCount = 0;
        $prev = self::END;
        foreach($layoutElements as $element) {
            if (strpos($element, '/&gt;') ||         // single tag - <xyz />
                strpos($element, 'lt;/', (strpos($element, '&gt;')))){ // <xyz>***</xyz>
                if ($prev == self::START) {
                    $tabCount++;
                }
                $prev = self::COMBINED;
            } elseif (strpos($element, 'lt;/')) {    // closing tag - </xyz>
                $tabCount = ($tabCount <= 1 ) ? 1 : --$tabCount;
                $prev = self::END;
            } else {
                if($prev == self::START ) {                 // opening tag - <xyz>
                    $tabCount++;
                }
                $prev = self::START;
            }

            $parsedString .= str_repeat(self::TAB, $tabCount) . $element . '<br />';
        }

        return $parsedString;
    }
}
