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

        // Protein kaynakları
        $proteinoptions = [
            'tavuk' => 'Tavuk',
            'balik' => 'Balık',
            'yumurta' => 'Yumurta',
            'kirmizi_et' => 'Kırmızı Et',
            'peynir' => 'Peynir'
        ];
        $mform->addElement('select', 'protein_sources', 'Protein Kaynakları', $proteinoptions, ['multiple' => 'multiple']);
        $mform->addRule('protein_sources', 'En az bir protein kaynağı seçiniz.', 'required');

        // Karbonhidrat kaynakları
        $carboptions = [
            'pirinc' => 'Pirinç',
            'bulgur' => 'Bulgur',
            'patates' => 'Patates',
            'yulaf' => 'Yulaf',
            'makarna' => 'Makarna'
        ];
        $mform->addElement('select', 'carb_sources', 'Karbonhidrat Kaynakları', $carboptions, ['multiple' => 'multiple']);
        $mform->addRule('carb_sources', 'En az bir karbonhidrat kaynağı seçiniz.', 'required');

        // Yağ kaynakları
        $fatoptions = [
            'zeytinyagi' => 'Zeytinyağı',
            'avokado' => 'Avokado',
            'ceviz' => 'Ceviz',
            'fistik' => 'Fıstık',
            'badem' => 'Badem'
        ];
        $mform->addElement('select', 'fat_sources', 'Yağ Kaynakları', $fatoptions, ['multiple' => 'multiple']);
        $mform->addRule('fat_sources', 'En az bir yağ kaynağı seçiniz.', 'required');

        $mform->addElement('submit', 'submitbutton', 'Diyet Listesi Oluştur');
    }
}