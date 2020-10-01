<?php

namespace PrestaShop\Module\Kb_Popup\Manager;

use Tab;
use Language;

class TabManager
{
    public static function installTab($module, $tabNameDisplay)
    {
        $response = true;

        // First check for parent tab
        $parentTab = new Tab(Tab::getIdFromClassName('CONFIGURE'));

        // Check for parent tab2
        $parentTab_2ID = Tab::getIdFromClassName('AdminMenuSecond');
        if ($parentTab_2ID) {
            $parentTab_2 = new Tab($parentTab_2ID);
        } else {
            $parentTab_2 = new Tab();
            $parentTab_2->active = 1;
            $parentTab_2->name = array();
            $parentTab_2->class_name = "AdminMenuSecond";
            foreach (Language::getLanguages() as $lang) {
                $parentTab_2->name[$lang['id_lang']] = "Theme config";
            }
            $parentTab_2->id_parent = $parentTab->id;
            $parentTab_2->module = '';
            $response &= $parentTab_2->add();
        }
        // Created tab
        $tabId = Tab::getIdFromClassName('Kb_PopupController');
        
        if (!$tabId) {
            $tabId = null;
        }

        $tab = new Tab($tabId);
        $tab->active = 1;
        $tab->class_name = "Kb_PopupController";
        $tab->name = array();
        foreach (Language::getLanguages() as $lang) {
            $tab->name[$lang['id_lang']] = $tabNameDisplay;
        }
        $tab->id_parent = $parentTab_2->id;
        $tab->module = $module->name;
        $response &= $tab->add();

        return $response;
    }

    public static function uninstallTab()
    {
        $id_tab = Tab::getIdFromClassName('Kb_PopupController');
        $parentTabID = Tab::getIdFromClassName('CONFIGURE');
        $tab = new Tab($id_tab);
        $tab->delete();

        // Get the number of tabs inside our parent tab
        // If there is no tabs, remove the parent
        $parentTab_2ID = Tab::getIdFromClassName('AdminMenuSecond');
        $tabCount_2 = Tab::getNbTabs($parentTab_2ID);
        if ($tabCount_2 == 0) {
            $parentTab_2 = new Tab($parentTab_2ID);
            $parentTab_2->delete();
        }
        // Get the number of tabs inside our parent tab
        // If there is no tabs, remove the parent
        $tabCount = Tab::getNbTabs($parentTabID);
        if ($tabCount == 0) {
            $parentTab = new Tab($parentTabID);
            $parentTab->delete();
        }

        return true;
    }
}
