<?php

namespace KallieExperiments\AdvTemplateHints\Model;

class TemplateData {
    private $_templateData;

    public function getTemplateData() {
        return $this->_templateData;
    }

    public function addTemplateData($value) {
        $this->_templateData .= $value . '<br />';
    }
}
