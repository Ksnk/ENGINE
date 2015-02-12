<?php
/**
 * модуль становки-деустановки модулей в станции
 */

class installManager extends engine_plugin
{

    static $installedModules = array();

    static function get_install_info()
    {
        return array(
            'aliace' => 'Install',
        );
    }

    /**
     * установлен или нет модль в системе
     * @param $module
     * @return bool
     */
    function class_status($module)
    {
        $modules = $this->getModuleList();
        if (array_key_exists($module, $modules)) {
            return $modules[$module];
        } else
            return false;
    }

    /**
     * получить список всех инсталлированных модулей
     * @return array
     */
    function getModuleList()
    {
        if (empty(self::$installedModules)) {
            self::$installedModules = ENGINE::option('engine.modules',array());
        }
        return self::$installedModules;
    }

    /**
     * @param $module
     * @return mixed
     */
    function installModule($module)
    {
        if ($this->has_rights('admin'))
            ENGINE::error('have no rights to install module', null, 'relogin');
        if ('on'!=$this->class_status($module)) {
            $options = ENGINE::exec($module, 'get_options');
            // внедряем опции в массив опций
            ENGINE::set_option($options);
            ENGINE::exec($module, 'install', true);
        } else {
            ENGINE::error('module `:module` already installed!', array(':module' => $module));
        }
    }

    /**
     * @param $module
     * @return mixed
     */
    function uninstall($module)
    {
        if ($this->has_rights('admin'))
            ENGINE::error('have no rights to uninstall module', null, 'relogin');
        if ('on'!=$this->class_status($module)) {
            $options = ENGINE::exec($module, 'get_options');
            // внедряем опции в массив опций
            ENGINE::set_option($options);
            ENGINE::exec($module, 'install', true);
        } else {
            ENGINE::error('module `:module` already installed!', array(':module' => $module));
        }
    }

    function __call($method, $param)
    {
        return ENGINE::exec(array(str_replace('do_', '', $method), 'admin'), $param);
    }

}