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

        // === Protein Kaynakları ===
        $proteinoptions = ['tavuk' => 'Tavuk', 'balik' => 'Balık', 'yumurta' => 'Yumurta', 'kirmizi_et' => 'Kırmızı Et', 'peynir' => 'Peynir'];
        $proteincheckboxes = [];
        foreach ($proteinoptions as $key => $label) {
            $proteincheckboxes[] = $mform->createElement('advcheckbox', $key, '', $label);
        }
        $mform->addGroup($proteincheckboxes, 'protein_sources', 'Protein Kaynakları', '<br>', false);
        $mform->addRule('protein_sources', 'En az bir protein kaynağı seçmelisiniz.', 'required', null, 'client');

        // === Karbonhidrat Kaynakları ===
        $carboptions = ['pirinc' => 'Pirinç', 'bulgur' => 'Bulgur', 'patates' => 'Patates', 'yulaf' => 'Yulaf', 'makarna' => 'Makarna'];
        $carbcheckboxes = [];
        foreach ($carboptions as $key => $label) {
            $carbcheckboxes[] = $mform->createElement('advcheckbox', $key, '', $label);
        }
        $mform->addGroup($carbcheckboxes, 'carb_sources', 'Karbonhidrat Kaynakları', '<br>', false);
        $mform->addRule('carb_sources', 'En az bir karbonhidrat kaynağı seçmelisiniz.', 'required', null, 'client');

        // === Yağ Kaynakları ===
        $fatoptions = ['zeytinyagi' => 'Zeytinyağı', 'avokado' => 'Avokado', 'ceviz' => 'Ceviz', 'fistik' => 'Fıstık', 'badem' => 'Badem'];
        $fatcheckboxes = [];
        foreach ($fatoptions as $key => $label) {
            $fatcheckboxes[] = $mform->createElement('advcheckbox', $key, '', $label);
        }
        $mform->addGroup($fatcheckboxes, 'fat_sources', 'Yağ Kaynakları', '<br>', false);
        $mform->addRule('fat_sources', 'En az bir yağ kaynağı seçmelisiniz.', 'required', null, 'client');

        $mform->addElement('submit', 'submitbutton', 'Diyet Listesi Oluştur');
    }
}