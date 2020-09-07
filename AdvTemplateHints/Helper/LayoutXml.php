<?php
namespace KallieExperiments\AdvTemplateHints\Helper;

class LayoutXml
{
    //@todo probably should delete thsi file
    /**
     * @var string $_layoutXml
     */
    private $_layoutXml = 'Layout XML Here...';

    public function setLayoutXML($value) {
        $this->_layoutXml = $value;
    }

    public function getLayoutXML() {
        return $this->_layoutXml;
    }
}
