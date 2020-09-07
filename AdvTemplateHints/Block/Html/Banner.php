<?php

namespace KallieExperiments\AdvTemplateHints\Block\Html;

use \Magento\Framework\View\Element\Template\Context;
use \Magento\Framework\View\Element\Template;

class Banner extends Template
{
    private const TAB = "&nbsp;&nbsp;&nbsp;&nbsp;";
    private const START = "start";
    private const COMBINED = "combo";
    private const END   = "end";

    /**
     * @var array $_data
     */
    protected $_data;

    /**
     * @var string $_layoutXml
     */
    private $_layoutXml;

    /**
     * @param Context $context
     * @param array $data
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function __construct(Context $context, array $data = [])
    {
        $this->_data = $data;
        parent::__construct($context, $this->_data);

        //$this->_layoutXml = $context->getLayout()->getXmlString();
        //$this->_layoutXml->setLayoutXML($this->parseXML($this->getLayout()->getXmlString()));

        $this->_layoutXml = $this->parseXML($this->getLayout()->getXmlString());
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
    public function getLayoutXmlString() {
        return $this->_layoutXml;
    }

    /**
     * @param string $xmlString
     * @return string
     */
    private function parseXML(string $xmlString) {
        $parsedString = "<div class='xml-string'>";
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

        return $parsedString . '</div>';
    }
    /*private function parseXML(string $xmlString) {
        simplexml_load_string($xmlString);
    }*/
}
