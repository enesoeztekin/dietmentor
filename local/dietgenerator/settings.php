<?php

defined('MOODLE_INTERNAL') || die();

if ($hassiteconfig) {
    $settings->add(new admin_setting_configtext(
        'local_dietgenerator/openai_apikey',
        'OpenAI API Key',
        'Enter your OpenAI API key here.',
        '',
        PARAM_TEXT
    ));
}