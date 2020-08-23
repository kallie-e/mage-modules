<?php
namespace KallieExperiments\AdvTemplateHints\Setup\Patch\Data;

use Magento\Framework\App\ScopeInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\Framework\Setup\Patch\PatchRevertableInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;

/**
 */
class InstallData implements DataPatchInterface, PatchRevertableInterface
{
    /**
     * Path to the debug_advanced_template_hints value in the config.
     */
    const CONFIG_PATH_DEBUG_ADV_HINTS = 'dev/debug/advanced_template_hints';

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
        $connection = $this->_setup->getConnection();
        $connection->startSetup();

        // add default value in core_config_data
        $data = ['scope' => ScopeInterface::SCOPE_DEFAULT, 'scope_id' => 0, 'path' => self::CONFIG_PATH_DEBUG_ADV_HINTS, 'value' => '0'];
        $connection->insertOnDuplicate( $this->_setup->getTable('core_config_data'), $data, ['value'] );

        $connection->endSetup();
    }

    /**
     * {@inheritdoc}
     */
    public function revert()
    {
        $connection = $this->_setup->getConnection();
        $connection->startSetup();

        // remove record from database
        $connection->delete( $this->_setup->getTable('core_config_data'), $where = 'path = ' . self::CONFIG_PATH_DEBUG_ADV_HINTS);

        $connection->endSetup();
    }

    /**
     * {@inheritdoc}
     */
    public function getAliases() { return []; }

    /**
     * {@inheritdoc}
     */
    public static function getDependencies() { return []; }
}
