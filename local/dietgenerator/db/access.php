<?php

defined('MOODLE_INTERNAL') || die();

$capabilities = [
    'local/dietgenerator:view' => [
        'captype' => 'read',
        'contextlevel' => CONTEXT_SYSTEM,
        'archetypes' => [
            'user' => CAP_ALLOW,
        ],
    ],
];