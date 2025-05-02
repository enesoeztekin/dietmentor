<?php
// local/dietgenerator/settings.php

defined('MOODLE_INTERNAL') || die();

if ($hassiteconfig) {
    $settings = new admin_settingpage('local_dietgenerator', 'Diyet Programı Ayarları');

    // Ayarları buraya ekleyin, örneğin:
    $settings->add(new admin_setting_configtext(
        'local_dietgenerator/openai_apikey',
        'OpenAI API Key',
        'Open AI anahtarını girin.',
        '',
        PARAM_TEXT
    ));

    // Settings sayfasını admin menüsüne ekle
    $ADMIN->add('localplugins', $settings);
}