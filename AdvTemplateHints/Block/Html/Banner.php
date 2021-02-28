<?php

namespace KallieExperiments\AdvTemplateHints\Block\Html;

use KallieExperiments\AdvTemplateHints\Model\Handles;
use KallieExperiments\AdvTemplateHints\Model\TemplateData;
use KallieExperiments\AdvTemplateHints\ModelView\Layout\Merge;
use KallieExperiments\AdvTemplateHints\ModelView\Element\Template;
//use Magento\Framework\View\Element\Template;
//use Magento\Framework\View\Model\Layout\Merge;
use Magento\Framework\Exception\LocalizedException;
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
     *
     * // ** for test ** //
     * @var string
     */
    private $__testValue;

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
        // todo figure out how to symlink
        // todo update github code
        // todo remove blue line in phtml.
        // todo get the buttons to float
        // todo put all 'debug' buttons as checkmarks with an update button (except adv)
        // todo experiement with jquery search that filters for keywords
        // todo z-index for banner needs to be obnoxiously high to stay on top of all site elements
        parent::__construct($context, $templateData, $data);

        $this->_layoutXml = $this->parseXML($this->getLayout()->getXmlString());
        $this->_layoutMerge = $layoutMerge;
        $this->_globalHandles = $globalHandles;

        // ** for test ** //
        $this->__testValue = $this->_layoutMerge->asString();
    }

    /**
     * While I'm building this sucker, need to test for stuff.
     */
    public function getTestValue(): string
    {
        return $this->__testValue;
    }

    /**
     * Check Advanced Templates option to see if Layout XML banner s hould be displayed
     *
     * @return boolean
     */
    public function displayAthBanner(): bool
    {
        // todo use constants
        return $this->_scopeConfig->getValue(
            'dev/debug/advanced_template_hints',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Check Advanced Templates option to see if Layout XML banner s hould be displayed
     *
     * @return boolean
     */
    public function displayAthBannerInfo(): bool
    {
        // todo use constants
        // todo button is created.  needs form to controller to update db when clicked. for now manually setting here
        return false;
//        return $this->_scopeConfig->getValue(
//            'dev/debug/advanced_template_hints_show',
//            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
//        );
    }

    /**
     * @return string
     */
    public function getLayoutHandles(): string
    {
        return join("<br />", array_keys($this->_globalHandles->getHandles()));
    }

    /**
     * Returns a striing representation of the layout XML for that page
     *
     * @return string
     */
    public function getLayoutXmlString(): string
    {
        return $this->_layoutXml;
    }

    /**
     * @param string $xmlString
     * @return string
     */
    private function parseXML(string $xmlString): string
    {
        // todo .... soooooo ... check another class ... think this is available in single function
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
