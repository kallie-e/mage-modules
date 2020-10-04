<?php
namespace KallieExperiments\CMS\Setup\Patch\Data;

use Magento\Catalog\Model\Product;
use Magento\Eav\Setup\EavSetup;
use Magento\Framework\App\ScopeInterface;
use Magento\Catalog\Model\CategoryFactory;
use Magento\Catalog\Model\ResourceModel\Category;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\Framework\Setup\Patch\PatchRevertableInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;

/**
 */
class PatchCMS101 implements DataPatchInterface, PatchRevertableInterface
{
    /**
     * @var \Magento\Framework\Setup\ModuleDataSetupInterface
     */
    private $_setup;

    /**
     * @var \Magento\Catalog\Model\CategoryFactory
     */
    private $_categoryFactory;

    /**
     * @var \Magento\Catalog\Model\ResourceModel\Category
     */
    private $_category;

    /**
     * @param \Magento\Framework\Setup\ModuleDataSetupInterface $moduleDataSetup
     */
    public function __construct(
        ModuleDataSetupInterface $moduleDataSetup,
        CategoryFactory $categoryFactory,
        Category $categoryResource
    )
    {
        $this->_setup = $moduleDataSetup;
        $this->_categoryFactory = $categoryFactory;
        $this->_category = $categoryResource;
    }

    /**
     * {@inheritdoc}
     */
    public function apply()
    {
        //The code that you want apply in the patch
        //Please note, that one patch is responsible only for one setup version
        //So one UpgradeData can consist of few data patches
        // Patches are save in patch_list table

        $this->_setup->getConnection()->startSetup();

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

        /*
         * // update values in core_config_data
        $data = [
            ['scope' => ScopeInterface::SCOPE_DEFAULT, 'scope_id' => 0, 'path' => 'checkout/options/guest_checkout', 'value' => '1'],
            ['scope' => ScopeInterface::SCOPE_DEFAULT, 'scope_id' => 0, 'path' => 'checkout/options/enable_me', 'value' => '1']
        ];
        $this->_setup->getConnection()->insertOnDuplicate( $this->_setup->getTable('core_config_data'), $data, ['value'] );

        // Update CMS page
        // note: identifier, if avaiilable, is preferred.  page/block_id good secondary, but dependent on db's being consitant.
        $data = [ 'page_id' => 2, 'identifier' => 'about-us', 'content' => '<div>Content</div>' ];
        $this->_setup->getConnection()->insertOnDuplicate($this->_setup->getTable('cms_page'), $data, ['content']);

        // Update CMS block
        $data = [ 'block_id' => 13, 'identifier' => 'footer_links_block', 'content' => '<div>Content</div>'];
        $this->_setup->getConnection()->insertOnDuplicate($this->_setup->getTable('cms_block'), $data, ['content']);

        // update a value in another table
        $data = ['id' => '191', 'left_sidebar_html' => '<div>Content</div>'];
        $this->_setup->getConnection()->insertOnDuplicate($this->_setup->getTable('ves_megamenu_item'), $data, ['left_sidebar_html']);

        // Update the definition of the attribute (not product specific, but overall)
        // @var EavSetup $eavSetup
        $eavSetup = $this->_eavSetupFactory->create(['setup' => $setup]);

        // table: eav_attribute
        // use Category::ENTITY for Category attributes
        $eavSetup->updateAttribute(
            Product::ENTITY,
            'country_of_manufacture',
            'frontend_label',
            'Country Where It is Manufactured'
        );

        // update individual category (or product) property/attribute
        $this->updateCategoryProperty(1, 3, '3');  // Models

        $this->_setup->getConnection()->endSetup();
        */
    }

    // in case i need it .....
    private function checkVar($setup, $value) {
        /*
        $data = ['scope' => ScopeInterface::SCOPE_DEFAULT, 'scope_id' => 0, 'path' => 'kallieexperments/var/check', 'value' => 'value: '. $value];
        $this->_setup->getConnection()->insertOnDuplicate( $this->_setup->getTable('core_config_data'), $data );
        */
    }

    /**
     * {@inheritdoc}
     */
    public function revert()
    {
        $this->_setup->getConnection()->startSetup();

        //Here should go code that will revert all operations from `apply` method
        //Please note, that some operations, like removing data from column, that is in role of foreign key reference
        //is dangerous, because it can trigger ON DELETE statement

        $this->_setup->getConnection()->endSetup();

        /*
        Reverting data patches
        Magento does not allow you to revert a particular module data patch. However, you can revert all composer installed or non-composer installed data patches using the module:uninstall command.

        Run the following command to revert all composer installed data patches:
        bin/magento module:uninstall Vendor_ModuleName

        Run the following command to revert all non-composer installed data patches:
        bin/magento module:uninstall --non-composer Vendor_ModuleName
        */
    }


    /**
     * {@inheritdoc}
     */
    public function getAliases()
    {
        /**
         * This internal Magento method, that means that some patches with time can change their names,
         * but changing name should not affect installation process, that's why if we will change name of the patch
         * we will add alias here
         */
        return [];
    }

    /**
     * {@inheritdoc}
     */
    public static function getDependencies()
    {
        /**
         * This is dependency to another patch. Dependency should be applied first
         * One patch can have few dependencies
         * Patches do not have versions, so if in old approach with Install/Ugrade data scripts you used
         * versions, right now you need to point from patch with higher version to patch with lower version
         * But please, note, that some of your patches can be independent and can be installed in any sequence
         * So use dependencies only if this important for you
         */
        return [
            //SomeDependency::class
        ];
    }


    /**
     * @param $storeId int
     * @param $categoryId int
     * @param $value int | string
     *
     * @throws NoSuchEntityException
     */
    function updateCategoryProperty($storeId, $categoryId, $value) {
        /*
        // create category model and set the store id
        $this->_category = $this->_categoryFactory->create();
        if (null !== $storeId) {
            $this->_category->setStoreId($storeId);
        }

        // load the category I want to work with
        $this->_category->load($categoryId);
        if (!$this->_category->getId()) {
            throw NoSuchEntityException::singleField('id', $categoryId);
        }

        // update the value
        // using attribute value property_name
        $this->_category->setPropertyName($value)->save();
        */
    }
}
