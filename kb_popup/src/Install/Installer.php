<?php

declare(strict_types=1);

namespace PrestaShop\Module\Kb_Popup\Install;

use Db;
use Module;

/**
 * Class responsible for modifications needed during installation/uninstallation of the module.
 */
class Installer
{

    /**
     * Module's installation entry point.
     *
     * @param Module $module
     *
     * @return bool
     */
    public function install(Module $module): bool
    {
        if (!$this->registerHooks($module)) {
            return false;
        }

        if (!$this->installDatabase()) {
            return false;
        }

        return true;
    }

    /**
     * Module's uninstallation entry point.
     *
     * @return bool
     */
    public function uninstall(): bool
    {
        return $this->uninstallDatabase();
    }

    /**
     * Install the database modifications required for this module.
     *
     * @return bool
     */
    private function installDatabase(): bool
    {
        $queries = [
            'CREATE TABLE IF NOT EXISTS `kb_popup` (
              `id` int(11) NOT NULL AUTO_INCREMENT,
              `id_lang` int(11) NOT NULL,
              `id_shop` int(11) NOT NULL,
              `is_image` BOOLEAN NULL,
              `id_product` int(11) NULL,
              `id_category` int(11) NULL,
              `starts_at` DATETIME NOT NULL,
              `ends_at` DATETIME NOT NULL,
              `background_color` VARCHAR(255) NULL,
              `page_select` VARCHAR(255) NULL,
              `text` text NULL,
              `link` VARCHAR(255) NULL,
              `image` VARCHAR(255) NULL,
              PRIMARY KEY (`id`, `id_lang`, `id_shop`)
            ) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8;',
        ];

        return $this->executeQueries($queries);
    }

    /**
     * Uninstall database modifications.
     *
     * @return bool
     */
    private function uninstallDatabase(): bool
    {
        $queries = [
            'DROP TABLE IF EXISTS `kb_popup`',
        ];

        return $this->executeQueries($queries);
    }

    /**
     * Register hooks for the module.
     *
     * @param Module $module
     *
     * @return bool
     */
    private function registerHooks(Module $module): bool
    {
        // Hooks available in the order view page.
        $hooks = [
            'ActionFrontControllerSetMedia',
            'displayBeforeBodyClosingTag',
            'displayFooterProduct',
            'displayAdminNavBarBeforeEnd',
            'displayBackOfficeHeader',
        ];

        return (bool) $module->registerHook($hooks);
    }

    /**
     * A helper that executes multiple database queries.
     *
     * @param array $queries
     *
     * @return bool
     */
    private function executeQueries(array $queries): bool
    {
        foreach ($queries as $query) {
            if (!Db::getInstance()->execute($query)) {
                return false;
            }
        }

        return true;
    }
}
