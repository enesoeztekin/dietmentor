<?php

global $CFG;
require_once("$CFG->libdir/formslib.php");

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

        $mform->addElement('submit', 'submitbutton', 'Diyet Listesi Oluştur');
    }
}