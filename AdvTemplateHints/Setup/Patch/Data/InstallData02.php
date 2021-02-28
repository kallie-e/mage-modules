<?php
namespace KallieExperiments\AdvTemplateHints\Setup\Patch\Data;

use Magento\Framework\App\ScopeInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\Framework\Setup\Patch\PatchRevertableInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;

/**
 */
class InstallData02 implements DataPatchInterface, PatchRevertableInterface
{
    /**
     * Path to the debug_advanced_template_hints value in the config.
     */
    const CONFIG_PATH_DEBUG_ADV_HINTS = 'dev/debug/advanced_template_hints';
    const CONFIG_PATH_DEBUG_ADV_HINTS_SHOW = 'dev/debug/advanced_template_hints_show';

    /**
     * @var ModuleDataSetupInterface
     */
    private $_setup;

    /**
     * @param ModuleDataSetupInterface $moduleDataSetup
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
        // todo using previous efforts as demo, update this to proper methods if needed
        $data = ['scope' => ScopeInterface::SCOPE_DEFAULT, 'scope_id' => 0, 'path' => self::CONFIG_PATH_DEBUG_ADV_HINTS, 'value' => '0'];
        $connection->insertOnDuplicate( $this->_setup->getTable('core_config_data'), $data, ['value'] );

        $data = ['scope' => ScopeInterface::SCOPE_DEFAULT, 'scope_id' => 0, 'path' => self::CONFIG_PATH_DEBUG_ADV_HINTS_SHOW, 'value' => '1'];
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
        $connection->delete( $this->_setup->getTable('core_config_data'), $where = 'path = ' . self::CONFIG_PATH_DEBUG_ADV_HINTS_SHOW);

        $connection->endSetup();
    }

    /**
     * {@inheritdoc}
     */
    public function getAliases(): array { return []; }

    /**
     * {@inheritdoc}
     */
    public static function getDependencies(): array { return []; }
}
