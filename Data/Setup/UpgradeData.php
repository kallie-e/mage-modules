<?php

namespace KallieExperiments\Data\Setup;

use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;

class UpgradeData implements \Magento\Framework\Setup\UpgradeDataInterface
{
    public function __construct() {}

    /**
     * @inheritDoc
     */
    public function upgrade(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->startSetup();

        if (version_compare($context->getVersion(), '1.0.1') < 0) {
            /* See also Magento\Framework\DB\Adapter\AdapterInterface */

            /* update value in core_config_data */
            /* Scopes
                app/code/Magento/Store/Model/ScopeInterface.php defines these scope types:
                const SCOPE_STORES = 'stores';
                const SCOPE_WEBSITES = 'websites';
                const SCOPE_STORE   = 'store';
                const SCOPE_GROUP   = 'group';
                const SCOPE_WEBSITE = 'website';

                lib/internal/Magento/Framework/App/ScopeInterface.php defines the default global scope:
                const SCOPE_DEFAULT = 'default';
            */
            $data = ['scope' => \Magento\Framework\App\ScopeInterface::SCOPE_DEFAULT, 'scope_id' => 0, 'path' => 'checkout/options/guest_checkout', 'value' => '1'];
            $setup->getConnection()->insertOnDuplicate($setup->getTable('core_config_data'), $data, ['value']);

            /* Update CMS page */
            /* see also Magento\Cms\Model\PageFactory; */
            $data = [ 'row_id' => '2', 'page_id' => '2', 'title' => 'Home', 'content' => '<div>Content</div>' ];
            $setup->getConnection()->insertOnDuplicate($setup->getTable('cms_page'), $data, ['content']);

            /* Update CMS block */
            /* see also Magento\Cms\Model\BlockFactory; */
            $data = [ 'row_id' => '13', 'block_id' => '13', 'title' => 'Category - Shop', 'content' => '<div>Content</div>' ];
            $setup->getConnection()->insertOnDuplicate($setup->getTable('cms_block'), $data, ['content']);

            /* update a value in another table */
            $data = ['id' => '191', 'left_sidebar_html' => '<div>Content</div>'];
            $setup->getConnection()->insertOnDuplicate($setup->getTable('ves_megamenu_item'), $data, ['left_sidebar_html']);

            /* add an attribute */
            /* see also Magento\Eav\Setup\EavSetupFactory; */
            /* note: the below is untested and still in development */
            $eavSetup = $this->eavSetupFactory->create(['setup' => $setup]);
            $eavSetup->addAttribute(
                \Magento\Catalog\Model\Product::ENTITY,
                'getaway_short_description',
                [
                    'type' => 'text',
                    'backend' => '',
                    'frontend' => '',
                    'label' => 'Getaway Short Description',
                    'input' => 'textarea',
                    'class' => '',
                    'source' => '',
                    'visible' => true,
                    'required' => false,
                    'user_defined' => false,
                    'default' => 0,
                    'searchable' => false,
                    'filterable' => true,
                    'comparable' => false,
                    'visible_on_front' => true,
                    'used_in_product_listing' => true,
                    'is_wysiwyg_enabled' => true,
                    'is_html_allowed_on_front' => true,
                    'unique' => false,
                    'apply_to' => ''
                ]
            );
        }

        $setup->endSetup();
    }
}
