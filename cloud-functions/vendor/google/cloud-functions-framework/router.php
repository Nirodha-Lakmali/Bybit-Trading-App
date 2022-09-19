<?php

use Google\CloudFunctions\Emitter;
use Google\CloudFunctions\Invoker;
use Google\CloudFunctions\ProjectContext;

// ProjectContext finds the autoload file, so we must manually include it first
require_once __DIR__ . '/src/ProjectContext.php';

$projectContext = new ProjectContext();

if ($autoloadFile = $projectContext->locateAutoloadFile()) {
    require_once $autoloadFile;
}

/**
 * Determine the function source file to load
 */
$functionSourceEnv = getenv('FUNCTION_SOURCE', true);
if ($source = $projectContext->locateFunctionSource($functionSourceEnv)) {
    require_once $source;
}

// Register the "gs://" stream wrapper for Cloud Storage if the package
// "google/cloud-storage" is installed and the "gs" protocol has not been
// registered
$projectContext->registerCloudStorageStreamWrapperIfPossible();

/**
 * Invoke the function based on the function type.
 */
(function () {
    $target = getenv('FUNCTION_TARGET', true);
    if (false === $target) {
        throw new RuntimeException('FUNCTION_TARGET is not set');
    }

    $signatureType = getenv('FUNCTION_SIGNATURE_TYPE', true) ?: 'http';

    $invoker = new Invoker($target, $signatureType);
    $response = $invoker->handle();
    (new Emitter())->emit($response);
})();
