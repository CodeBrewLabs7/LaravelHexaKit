<?php
use Nwidart\Modules\Facades\Module;
if (!function_exists('isModuleEnabled')) {
    /**
     * Checks if given module is enabled
     *
     * @param $moduleName
     * @return bool
     */
    function isModuleEnabled($moduleName)
    {
        if(Module::has($moduleName)) {
            $status = Module::isEnabled($moduleName);
            if($status) {
                return true;
            }
        }
        return false;
    }
}
