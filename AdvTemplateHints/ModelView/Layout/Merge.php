<?php

namespace KallieExperiments\AdvTemplateHints\ModelView\Layout;

use KallieExperiments\AdvTemplateHints\Model\Handles;
use Magento\Framework\Filesystem\File\ReadFactory;
use Magento\Framework\Serialize\SerializerInterface;
use Magento\Framework\View\Layout\LayoutCacheKeyInterface;

class Merge extends \Magento\Framework\View\Model\Layout\Merge
{
    /**
     * @var array Handles
     */
    private $_globalHandles;

    /**
     * Init merge model
     *
     * @param \Magento\Framework\View\DesignInterface $design
     * @param \Magento\Framework\Url\ScopeResolverInterface $scopeResolver
     * @param \Magento\Framework\View\File\CollectorInterface $fileSource
     * @param \Magento\Framework\View\File\CollectorInterface $pageLayoutFileSource
     * @param \Magento\Framework\App\State $appState
     * @param \Magento\Framework\Cache\FrontendInterface $cache
     * @param \Magento\Framework\View\Model\Layout\Update\Validator $validator
     * @param \Psr\Log\LoggerInterface $logger
     * @param ReadFactory $readFactory
     * @param Handles $globalHandles
     * @param \Magento\Framework\View\Design\ThemeInterface|null $theme Non-injectable theme instance
     * @param string $cacheSuffix
     * @param LayoutCacheKeyInterface|null $layoutCacheKey
     * @param SerializerInterface|null $serializer
     * @SuppressWarnings(PHPMD.ExcessiveParameterList)
     */
    public function __construct(
        \Magento\Framework\View\DesignInterface $design,
        \Magento\Framework\Url\ScopeResolverInterface $scopeResolver,
        \Magento\Framework\View\File\CollectorInterface $fileSource,
        \Magento\Framework\View\File\CollectorInterface $pageLayoutFileSource,
        \Magento\Framework\App\State $appState,
        \Magento\Framework\Cache\FrontendInterface $cache,
        \Magento\Framework\View\Model\Layout\Update\Validator $validator,
        \Psr\Log\LoggerInterface $logger,
        ReadFactory $readFactory,
        Handles $globalHandles,
        \Magento\Framework\View\Design\ThemeInterface $theme = null,
        $cacheSuffix = '',
        LayoutCacheKeyInterface $layoutCacheKey = null,
        SerializerInterface $serializer = null
    ) {
        parent::__construct($design, $scopeResolver, $fileSource, $pageLayoutFileSource, $appState, $cache, $validator, $logger, $readFactory, $theme, $cacheSuffix, $layoutCacheKey, $serializer);
        $this->_globalHandles = $globalHandles;
    }
    /**
     * Add handle(s) to update
     *
     * @param array|string $handleName
     * @return \Magento\Framework\View\Model\Layout\Merge
     */
    public function addHandle($handleName)
    {
        if (is_array($handleName)) {
            foreach ($handleName as $name) {
                $this->handles[$name] = 1;
                $this->_globalHandles->addHandle($name);
            }
        } else {
            $this->handles[$handleName] = 1;
            $this->_globalHandles->addHandle($handleName);
        }

        return $this;
    }

    /**
     * Cleanup circular references
     *
     * Destructor should be called explicitly in order to work around the PHP bug
     * https://bugs.php.net/bug.php?id=62468
     */
    public function __destruct()
    {
        parent::__destruct();
        //echo "**Destroyyyyy....**<br />";
    }

//    /**
//     * Get layout updates as \Magento\Framework\View\Layout\Element object
//     *
//     * @return \SimpleXMLElement
//     */
//    public function asSimplexml()
//    {
//        $updates = trim($this->asString());
/*        $updates = '<?xml version="1.0"?>'*/
//            . '<layout xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">'
//            . $updates
//            . '</layout>';
//        return $this->_loadXmlString($updates);
//    }
}
