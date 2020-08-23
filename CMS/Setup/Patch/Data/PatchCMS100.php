<?php
namespace KallieExperiments\CMS\Setup\Patch\Data;

use Magento\Framework\App\ScopeInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\Framework\Setup\Patch\PatchRevertableInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;

/**
 */
class PatchCMS100 implements DataPatchInterface, PatchRevertableInterface
{
    /**
     * @var \Magento\Framework\Setup\ModuleDataSetupInterface
     */
    private $_setup;

    /**
     * @param \Magento\Framework\Setup\ModuleDataSetupInterface $moduleDataSetup
     */
    public function __construct( ModuleDataSetupInterface $moduleDataSetup )
    {
        $this->_setup = $moduleDataSetup;
    }

    /**
     * {@inheritdoc}
     */
    public function apply()
    {
        $this->_setup->getConnection()->startSetup();

        // update values in some tables....

        $this->_setup->getConnection()->endSetup();
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
    }

    /**
     * {@inheritdoc}
     */
    public function getAliases() { return []; }

    /**
     * {@inheritdoc}
     */
    public static function getDependencies() { return []; }

    // in case i need it .....
    private function checkVar($setup, $value) {
        $data = ['scope' => ScopeInterface::SCOPE_DEFAULT, 'scope_id' => 0, 'path' => 'kallieexperments/var/check', 'value' => 'value: '. $value];
        $this->_setup->getConnection()->insertOnDuplicate( $this->_setup->getTable('core_config_data'), $data );
    }
}
