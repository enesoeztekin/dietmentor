<?php
// local/dietgenerator/settings.php

defined('MOODLE_INTERNAL') || die();

if ($hassiteconfig) {
    $settings = new admin_settingpage('local_dietgenerator', 'Diyet Programı Ayarları');

    // Ayarları buraya ekleyin, örneğin:
    $settings->add(new admin_setting_configtext(
        'local_dietgenerator/weight',
        'Kilo Ayarı',
        'Kullanıcıların kilolarını girip diyet alabilmesi için gerekli ayar.',
        '',
        PARAM_INT
    ));

    // Settings sayfasını admin menüsüne ekle
    $ADMIN->add('localplugins', $settings);
}