<?php

$loader = new \Phalcon\Loader();

/**
 * We're a registering a set of directories taken from the configuration file
 */
$loader->registerDirs(
    array(
        $config->application->controllersDir,
        $config->application->modelsDir,
        $config->application->componentsDir,
        $config->application->libraryDir,
        $config->application->validationsDir,
        $config->application->traitsDir,
        $config->application->workersDir,
        $config->application->jobsDir
    )
)->register();
