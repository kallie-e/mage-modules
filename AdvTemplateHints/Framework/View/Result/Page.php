<?php

namespace KallieExperiments\AdvTemplateHints\Framework\View\Result;

use KallieExperiments\AdvTemplateHints\Helper\LayoutXml;
use Magento\Framework;
use Magento\Framework\App\Response\HttpInterface as HttpResponseInterface;
use Magento\Framework\View;
use Magento\Framework\View\Result\Page as ResultPage;
use Magento\Framework\App\ObjectManager;

class Page extends ResultPage
{
    /**
     * @var string $_layoutXml
     */
    protected $_layoutXml;

    /**
     * @param View\Element\Template\Context $context
     * @param View\LayoutFactory $layoutFactory
     * @param View\Layout\ReaderPool $layoutReaderPool
     * @param Framework\Translate\InlineInterface $translateInline
     * @param View\Layout\BuilderFactory $layoutBuilderFactory
     * @param View\Layout\GeneratorPool $generatorPool
     * @param View\Page\Config\RendererFactory $pageConfigRendererFactory
     * @param View\Page\Layout\Reader $pageLayoutReader
     * @param false $isIsolated
     * @param View\EntitySpecificHandlesList|null $entitySpecificHandlesList
     */
   /* public function __construct(View\Element\Template\Context $context,
                                View\LayoutFactory $layoutFactory,
                                View\Layout\ReaderPool $layoutReaderPool,
                                Framework\Translate\InlineInterface $translateInline,
                                View\Layout\BuilderFactory $layoutBuilderFactory,
                                View\Layout\GeneratorPool $generatorPool,
                                View\Page\Config\RendererFactory $pageConfigRendererFactory,
                                View\Page\Layout\Reader $pageLayoutReader, $template,
                                $isIsolated = false,
                                View\EntitySpecificHandlesList $entitySpecificHandlesList = null)
    {
        parent::__construct($context, $layoutFactory, $layoutReaderPool, $translateInline, $layoutBuilderFactory, $generatorPool, $pageConfigRendererFactory, $pageLayoutReader, $template, $isIsolated, $entitySpecificHandlesList);

        $this->_layoutXml = ObjectManager::getInstance()->get(LayoutXml::class);
    }*/


    /**
     * @param HttpResponseInterface $response
     * @return $this|Page|Framework\Controller\AbstractResult|View\Result\Layout|ResultPage
     * @throws \Exception
     */
    protected function render(HttpResponseInterface $response)
    {
        $this->pageConfig->publicBuild();
        if ($this->getPageLayout()) {
            $config = $this->getConfig();
            $this->addDefaultBodyClasses();
            $addBlock = $this->getLayout()->getBlock('head.additional'); // todo
            $requireJs = $this->getLayout()->getBlock('require.js');
            $this->assign([
                'requireJs' => $requireJs ? $requireJs->toHtml() : null,
                'headContent' => $this->pageConfigRenderer->renderHeadContent(),
                'headAdditional' => $addBlock ? $addBlock->toHtml() : null,
                'htmlAttributes' => $this->pageConfigRenderer->renderElementAttributes($config::ELEMENT_TYPE_HTML),
                'headAttributes' => $this->pageConfigRenderer->renderElementAttributes($config::ELEMENT_TYPE_HEAD),
                'bodyAttributes' => $this->pageConfigRenderer->renderElementAttributes($config::ELEMENT_TYPE_BODY),
                'loaderIcon' => $this->getViewFileUrl('images/loader-2.gif'),
            ]);

            $output = $this->getLayout()->getOutput();
            $this->assign('layoutContent', $output);

            // now that we have the layout XML, get a string version of it to play with
            //$this->_layoutXml->setLayoutXML($this->parseXML($this->getLayout()->getXmlString()));
            //$this->getLayout()->generateElements();

            // now let's render the page
            $output = $this->renderPage();
            $this->translateInline->processResponseBody($output);
            $response->appendBody($output);
        } else {
            parent::render($response);
        }
        return $this;
    }
}

