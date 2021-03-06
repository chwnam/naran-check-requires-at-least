<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit0b06c37a7eee12fd8824e2ae397165a1
{
    public static $files = array (
        '9a4b80dc55ace70fd2fa465958a0daf5' => __DIR__ . '/../..' . '/includes/functions/function-ncral-module.php',
        '3689b6e6b16e854eabc7a8b44c0a8d02' => __DIR__ . '/../..' . '/includes/functions/function-ncral-token.php',
        'ff3dc72cad280fee994f26b3d50438d2' => __DIR__ . '/../..' . '/includes/functions/function-ncral-util.php',
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
        'NCRAL_Admin_Module' => __DIR__ . '/../..' . '/includes/interfaces/interface-ncral-admin-module.php',
        'NCRAL_Core_Function_Data_Collector' => __DIR__ . '/../..' . '/includes/interfaces/interface-ncral-core-function-data-collector.php',
        'NCRAL_Core_Function_Info' => __DIR__ . '/../..' . '/includes/objects/class-ncral-core-function-info.php',
        'NCRAL_Function_Call_Info' => __DIR__ . '/../..' . '/includes/objects/class-ncral-function-call-info.php',
        'NCRAL_Function_Checker' => __DIR__ . '/../..' . '/includes/interfaces/interface-ncral-function-checker.php',
        'NCRAL_Hook_Impl' => __DIR__ . '/../..' . '/includes/traits/trait-ncral-hook-impl.php',
        'NCRAL_Main' => __DIR__ . '/../..' . '/includes/class-ncral-main.php',
        'NCRAL_Module' => __DIR__ . '/../..' . '/includes/interfaces/interface-ncral-module.php',
        'NCRAL_Module_Admin' => __DIR__ . '/../..' . '/includes/admin/class-ncral-module-admin.php',
        'NCRAL_Module_Admin_Tools' => __DIR__ . '/../..' . '/includes/admin/class-ncral-module-admin-tools.php',
        'NCRAL_Module_Register' => __DIR__ . '/../..' . '/includes/class-ncral-module-register.php',
        'NCRAL_Submodule_Impl' => __DIR__ . '/../..' . '/includes/traits/trait-ncral-submodule-impl.php',
        'NCRAL_Template_Impl' => __DIR__ . '/../..' . '/includes/traits/trait-ncral-template-impl.php',
        'NCRAL_Token_Get_All' => __DIR__ . '/../..' . '/includes/scanners/class-ncral-token-get-all.php',
        'NCRAL_Token_Get_All_Core_Function_Data_Collector' => __DIR__ . '/../..' . '/includes/scanners/class-ncral-token-get-all-core-function-data-collector.php',
        'NCRAL_Token_Get_All_Function_Checker' => __DIR__ . '/../..' . '/includes/scanners/class-ncral-token-get-all-function-checker.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->classMap = ComposerStaticInit0b06c37a7eee12fd8824e2ae397165a1::$classMap;

        }, null, ClassLoader::class);
    }
}
