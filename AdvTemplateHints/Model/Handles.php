<?php

namespace KallieExperiments\AdvTemplateHints\Model;

class Handles {
    /**
     * @var array
     */
    private $_handles;

    /**
     * @return array
     */
    public function getHandles()
    {
        return $this->_handles;
    }

    /**
     * @param string $handle
     */
    public function addHandle(string $handle) {
        // using the handle name as a key ensures that the array
        // contains only unique values, since this will be called multiple times
        // for multiple reasons
        $this->_handles[$handle] = 1;
    }
}
