<?php

defined('MOODLE_INTERNAL') || die();

global $CFG;
require_once($CFG->libdir.'/formslib.php');

class userinfo_form extends moodleform {
    public function definition() {
        $mform = $this->_form;

        $mform->addElement('text', 'weight', 'Kilonuz (kg)');
        $mform->setType('weight', PARAM_FLOAT);
        $mform->addRule('weight', 'Gerekli', 'required');

        $mform->addElement('text', 'height', 'Boyunuz (cm)');
        $mform->setType('height', PARAM_FLOAT);
        $mform->addRule('height', 'Gerekli', 'required');

        $mform->addElement('text', 'age', 'Yaşınız');
        $mform->setType('age', PARAM_INT);
        $mform->addRule('age', 'Gerekli', 'required');

        $genderoptions = ['male' => 'Erkek', 'female' => 'Kadın'];
        $mform->addElement('select', 'gender', 'Cinsiyet', $genderoptions);
        $mform->addRule('gender', 'Gerekli', 'required');

        // Protein kaynakları (checkbox grup)
        $proteinOptions = [
            'Tavuk' => 'Tavuk',
            'Hindi' => 'Hindi',
            'Yumurta' => 'Yumurta',
            'Yoğurt' => 'Yoğurt',
            'Peynir' => 'Peynir',
            'Balık' => 'Balık',
            'Kırmızı Et' => 'Kırmızı Et',
            'Baklagiller' => 'Baklagiller'
        ];
        $mform->addElement('checkboxgroup', 'protein_sources', 'Protein Kaynakları', $proteinOptions);
        $mform->addRule('protein_sources', 'En az bir protein kaynağı seçiniz.', 'required', null, 'client');

        // Karbonhidrat kaynakları
        $carbOptions = [
            'Pirinç' => 'Pirinç',
            'Yulaf' => 'Yulaf',
            'Makarna' => 'Makarna',
            'Tam Buğday Ekmeği' => 'Tam Buğday Ekmeği',
            'Sebzeler' => 'Sebzeler',
            'Meyveler' => 'Meyveler',
            'Patates' => 'Patates',
            'Baklagiller' => 'Baklagiller'
        ];
        $mform->addElement('checkboxgroup', 'carb_sources', 'Karbonhidrat Kaynakları', $carbOptions);
        $mform->addRule('carb_sources', 'En az bir karbonhidrat kaynağı seçiniz.', 'required', null, 'client');

        // Yağ kaynakları
        $fatOptions = [
            'Zeytinyağı' => 'Zeytinyağı',
            'Avokado' => 'Avokado',
            'Kuruyemişler' => 'Kuruyemişler',
            'Tohumlar' => 'Tohumlar',
            'Tereyağı' => 'Tereyağı',
            'Hindistancevizi Yağı' => 'Hindistancevizi Yağı',
            'Balık Yağı' => 'Balık Yağı'
        ];
        $mform->addElement('checkboxgroup', 'fat_sources', 'Yağ Kaynakları', $fatOptions);
        $mform->addRule('fat_sources', 'En az bir yağ kaynağı seçiniz.', 'required', null, 'client');

        $mform->addElement('submit', 'submitbutton', 'Diyet Listesi Oluştur');
    }
}