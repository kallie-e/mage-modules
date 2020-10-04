<?php

namespace KallieExperiments\AdvTemplateHints\ModelView\Element;

use KallieExperiments\AdvTemplateHints\Model\TemplateData;
use Magento\Framework\Exception\ValidatorException;
use Magento\Framework\View\Element\Template\Context;

class Template extends \Magento\Framework\View\Element\Template
{

    /**
     * Todo Soooooo .... I need to really question how much of this is in use
     * because I keep changing things up as I discover stuff
     */
    /**
     * @var TemplateData
     */
    private $_templateData;

    /**
     * Constructor
     *
     * @param Context $context
     * @param TemplateData $templateData
     * @param array $data
     */
     public function __construct(Context $context, TemplateData $templateData, array $data = [])
    {
        parent::__construct($context, $data);
        $this->_templateData = $templateData;
    }

    /**
     * @return TemplateData
     */
    public function getTemplateData() {
        return $this->_templateData->getTemplateData();
    }

//    /**
//     * Retrieve block html
//     *
//     * @param   string $name
//     * @return  string
//     */
//    public function getBlockHtml($name)
//    {
//        return $this->_wrapHtml(parent::getBlockHtml($name));
//    }
//
//    /**
//     * Retrieve child block HTML
//     *
//     * @param   string $alias
//     * @param   boolean $useCache
//     * @return  string
//     */
//    public function getChildHtml($alias = '', $useCache = true)
//    {
//        return $this->_wrapHtml(parent::getChildHtml());
//    }

    /**
     * Preparing layout
     *
     * You can redefine this method in child classes for changing layout
     *
     * @return $this
     */
    protected function _prepareLayout()
    {
        $this->_templateData->addTemplateData("Name in Layout: " . (($value = $this->getNameInLayout()) ? $value : 'N/A'));
        $this->_templateData->addTemplateData("Template File: " . (($value = $this->getTemplateFile()) ? $value : 'N/A'));
        $this->_templateData->addTemplateData("Module Name: " . (($value = $this->getModuleName()) ? $value : 'N/A'));
        $this->_templateData->addTemplateData("***");

        return $this;
    }

//    /**
//     * Render output of child child element
//     *
//     * @param string $alias
//     * @param string $childChildAlias
//     * @param bool $useCache
//     * @return string
//     */
//    public function getChildChildHtml($alias, $childChildAlias = '', $useCache = true)
//    {
//        return $this->_wrapHtml(parent::getChildChildHtml($alias, $childChildAlias, $useCache));
//    }
//
//    /**
//     * Render block HTML
//     *
//     * @return string
//     */
//    protected function _toHtml()
//    {
//        return $this->_wrapHtml(parent::_toHtml());
//    }
//
//    /**
//     * Processing block html after rendering
//     *
//     * @param mixed $html
//     * @return  string
//     */
//    protected function _afterToHtml($html)
//    {
//        return $html;
//    }
//
//    /**
//     * Produce and return block's html output
//     *
//     * Documentation says this:
//     * This method should not be overridden. You can override _toHtml() method in descendants if needed.
//     *
//     * So I'm'a gonna do it anyways.  Because ... yeah.
//     *
//     * @return string
//     */
//    public function toHtml()
//    {
//        return $this->_wrapHtml(parent::toHtml());
//    }
//
//    /**
//     * Retrieve block view from file (template)
//     *
//     * @param string $fileName
//     * @return string
//     * @throws ValidatorException
//     */
//    public function fetchView($fileName)
//    {
//        return $this->_wrapHtml(parent::fetchView($fileName));
//    }
//
//    private function _wrapHtml($html) {
//        //return "<span class='adv-template-hints'>" . $html . "</span>";
//        return $html;
//    }
}
