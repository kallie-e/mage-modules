<?php
/**
 * Decorator that inserts debugging hints into the rendered block contents
 *
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace KallieExperiments\AdvTemplateHints\Model\TemplateEngine\Decorator;

/**
 * Decorates block with block and template hints
 *
 * @api
 * @since 100.0.2
 */
class DebugHints extends \Magento\Developer\Model\TemplateEngine\Decorator\DebugHints
{
    /**
     * @var \Magento\Framework\View\TemplateEngineInterface
     */
    private $_subject;

    /**
     * @var bool
     */
    private $_showBlockHints;

    /**
     * @param \Magento\Framework\View\TemplateEngineInterface $subject
     * @param bool $showBlockHints Whether to include block into the debugging information or not
     */
    public function __construct(\Magento\Framework\View\TemplateEngineInterface $subject, $showBlockHints)
    {
        $this->_subject = $subject;
        $this->_showBlockHints = $showBlockHints;
    }

    /**
     * Insert debugging hints into the rendered block contents
     *
     * {@inheritdoc}
     */
    public function render(\Magento\Framework\View\Element\BlockInterface $block, $templateFile, array $dictionary = [])
    {
        $result = $this->_subject->render($block, $templateFile, $dictionary);
        if ($this->_showBlockHints) {
            $result = $this->_renderBlockHints($result, $block);
        }
        $result = $this->_renderTemplateHints($result, $templateFile);
        return $result;
    }

    /**
     * Insert template debugging hints into the rendered block contents
     *
     * @param string $blockHtml
     * @param string $templateFile
     * @return string
     */
    protected function _renderTemplateHints($blockHtml, $templateFile)
    {
        // @codingStandardsIgnoreStart
        return <<<HTML
<span class="adv-debugging-hints">
<span class="adv-debugging-hint-template-file" onmouseover="this.style.zIndex = 999;" onmouseout="this.style.zIndex = 'auto';" title="{$templateFile}">{$templateFile}</span>
{$blockHtml}
</span>
HTML;
        // @codingStandardsIgnoreEnd
    }

    /**
     * Insert block debugging hints into the rendered block contents
     *
     * @param string $blockHtml
     * @param \Magento\Framework\View\Element\BlockInterface $block
     * @return string
     */
    protected function _renderBlockHints($blockHtml, \Magento\Framework\View\Element\BlockInterface $block)
    {
        $blockClass = get_class($block);
        // @codingStandardsIgnoreStart
        return <<<HTML
<span class="adv-debugging-hint-block-class" onmouseover="this.style.zIndex = 999;" onmouseout="this.style.zIndex = 'auto';" title="{$blockClass}">{$blockClass}</span>
{$blockHtml}
HTML;
        // @codingStandardsIgnoreEnd
    }
}
