<?php

// This file has been auto-generated by the Symfony Dependency Injection Component for internal use.

if (\class_exists(\Container5qSh0c5\srcApp_KernelDevDebugContainer::class, false)) {
    // no-op
} elseif (!include __DIR__.'/Container5qSh0c5/srcApp_KernelDevDebugContainer.php') {
    touch(__DIR__.'/Container5qSh0c5.legacy');

    return;
}

if (!\class_exists(srcApp_KernelDevDebugContainer::class, false)) {
    \class_alias(\Container5qSh0c5\srcApp_KernelDevDebugContainer::class, srcApp_KernelDevDebugContainer::class, false);
}

return new \Container5qSh0c5\srcApp_KernelDevDebugContainer([
    'container.build_hash' => '5qSh0c5',
    'container.build_id' => '25bf9198',
    'container.build_time' => 1565010871,
], __DIR__.\DIRECTORY_SEPARATOR.'Container5qSh0c5');
