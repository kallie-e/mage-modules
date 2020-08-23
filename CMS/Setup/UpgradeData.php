<?php

namespace KallieExperiments\CMS\Setup;

/* Ok, yet another note ... this fiile is DEPRECATED
 * in favor of using data patches.  Keeping this on hand for a bit
 * to be sure I don't miss soomething.
 */


/* NOTE!!!!
 * This is an extensive list of all the classes used in this sample.
 * I strongly recommend for sake of efficiency and clarity only listing what you use.
 * Delete the rest.
 *
 * Coded with Magento 2.3 in mind.
 */
use Magento\Catalog\Model\Product;
use Magento\Catalog\Model\ProductFactory;
use Magento\Eav\Model\Config;
use Magento\Eav\Setup\EavSetup;
use Magento\Eav\Setup\EavSetupFactory;
use Magento\Framework\App\ScopeInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\UpgradeDataInterface;

// Probably not necessary here, but clickable for notes
use Magento\Framework\DB\Adapter\AdapterInterface;
use Magento\Framework\DB\Ddl\Table;
use Magento\Catalog\Model\ResourceModel\Category\Collection as CategoryCollection;
use Magento\Catalog\Model\ResourceModel\Product\Collection as ProductCollection;


class UpgradeData implements UpgradeDataInterface
{
    /*
     * @var Magento\Eav\Setup\EavSetupFactory $eavSetupFactory
     */
    private $_eavConfig;

    /*
     * @var Magento\Eav\Model\Config $eavConfig
     */
    private $_eavSetupFactory;

    /*
     * @var Magento\Catalog\Model\Product $product;
     */
    private $_product;

    /*
     * @var Magento\Catalog\Model\ProductFactory $productFactory;
     */
    private $_productFactory;

    /**
     * Initialize
     *
     * @param EavSetupFactory $eavSetupFactory
     * @param Config $eavConfig
     * @param Product $product;
     * @param ProductFactory $productFactory;
     * @param CollectionFactory $productCollection
     *
     * @return void
     */
    public function __construct(EavSetupFactory $eavSetupFactory, Config $eavConfig, Product $product, ProductFactory $productFactory)
    {
        $this->_eavSetupFactory = $eavSetupFactory;
        $this->_eavConfig = $eavConfig;
        $this->_product = $product;
        $this->_productFactory = $productFactory;
    }

    /**
     * Upgrades data for a module
     *
     * @param ModuleDataSetupInterface $setup
     * @param ModuleContextInterface $context
     *
     * @return void
     */
    public function upgrade(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->startSetup();

        if (version_compare($context->getVersion(), '1.0.1', '<')) {
            // naming convention $this-><runUpgradeXYZ($setup);  the secondary name is for labeling purpose for this demo only
            $this->runUpgrade101_coreConfigData($setup);
        }

        if (version_compare($context->getVersion(), '1.0.2') < 0) {
            $this->runUpgrade102_CmsContent($setup);
        }

//        if (version_compare($context->getVersion(), '1.0.3') < 0) {
//            $this->runUpgrade103_table($setup);
//        }

        // set the version value higher than module setup_version for testing
        if (version_compare($context->getVersion(), '1.0.4') < 0) {
            $this->runUpgrade104_attribute($setup);
        }

        $setup->endSetup();
    }

    /**
     * @param ModuleDataSetupInterface $setup
    */
    private function runUpgrade101_coreConfigData($setup) {
        /*  Scopes
            app/code/Magento/Store/Model/ScopeInterface.php defines these scope types:
            const SCOPE_STORES = 'stores';
            const SCOPE_WEBSITES = 'websites';
            const SCOPE_STORE   = 'store';
            const SCOPE_GROUP   = 'group';
            const SCOPE_WEBSITE = 'website';

            lib/internal/Magento/Framework/App/ScopeInterface.php defines the default global scope:
            const SCOPE_DEFAULT = 'default';
        */

        // update values in core_config_data
        $data = [
            ['scope' => ScopeInterface::SCOPE_DEFAULT, 'scope_id' => 0, 'path' => 'checkout/options/guest_checkout', 'value' => '1'],
            ['scope' => ScopeInterface::SCOPE_DEFAULT, 'scope_id' => 0, 'path' => 'checkout/options/enable_me', 'value' => '1']
        ];
        $setup->getConnection()->insertOnDuplicate( $setup->getTable('core_config_data'), $data, ['value'] );

    }

/**
* @param ModuleDataSetupInterface $setup
*/
    private function runUpgrade102_CmsContent($setup) {
        // Update CMS page
        $data = [ 'page_id' => 2, 'title' => 'Home', 'content' => '<div>Content</div>' ];
        $setup->getConnection()->insertOnDuplicate($setup->getTable('cms_page'), $data, ['content']);

        // Update CMS block
        $data = [ 'block_id' => 13, 'title' => 'Category - Shop', 'content' => '<div>Content</div>' ];
        $setup->getConnection()->insertOnDuplicate($setup->getTable('cms_block'), $data, ['content']);
    }

    /**
     * @param ModuleDataSetupInterface $setup
     */
    private function runUpgrade103_table($setup) {
        // update a value in another table */
        $data = ['id' => '191', 'left_sidebar_html' => '<div>Content</div>'];
        $setup->getConnection()->insertOnDuplicate($setup->getTable('ves_megamenu_item'), $data, ['left_sidebar_html']);
    }

    /**
     * @param ModuleDataSetupInterface $setup
     */
    private function runUpgrade104_attribute($setup) {
//        // rare instance of me using object manager.  DELETE THIS BEFORE USINIG!!!!
//        // i was testing wiith sample data, and Google searches revealed
//        // there were issues with that (facepalm).  The following is to alleviatee.
//        //  But for the love of it ...
//        // DELETE BEFORE USING IN YOUR CODE!!!
//        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
//        $state = $objectManager->create('Magento\Framework\App\State');
//        $state->setAreaCode(\Magento\Framework\App\Area::AREA_ADMINHTML);

        /** @var EavSetup $eavSetup */
        $eavSetup = $this->_eavSetupFactory->create(['setup' => $setup]);

        // Update the definition of the attribute (not product specific, but overall)
        // table: eav_attribute
        // use Category::ENTITY for Category attributes
        $eavSetup->updateAttribute(
            Product::ENTITY,
            'country_of_manufacture',
            'frontend_label',
            'Country Where It is Manufactured'
        );

        // no idea if this works or not ... did not work on my local testing.  place to test elsewhere to find out.
        $product1 = $this->_productFactory->create()->setStoreId(0)->load( $this->_product->getIdBySku('24-MB01') );
        $product1
            ->setName('My Joust Duffle Bag Global')
            ->setShortDescription('The sporty Joust Duffle Bag cannot be beat!')
            ->save();
    }

    private function checkVar($setup, $value) {
        $data = ['scope' => ScopeInterface::SCOPE_DEFAULT, 'scope_id' => 0, 'path' => 'kallieexperments/var/check', 'value' => 'value: '. $value];
        $setup->getConnection()->insertOnDuplicate( $setup->getTable('core_config_data'), $data );
    }
}


