<?php

require('../../config.php');
require_once('forms/userinfo_form.php');
require_once($CFG->dirroot . '/local/dietgenerator/classes/api/OpenAIClient.php');
require_once($CFG->dirroot . '/local/dietgenerator/classes/logic/DietCourseBuilder.php');

require_login();

$PAGE->set_context(context_system::instance());
$PAGE->set_url(new moodle_url('/local/dietgenerator/index.php'));
$PAGE->set_title('Diyet Programı Oluşturucu');
$PAGE->set_heading('Diyet Programı Oluşturucu');

echo $OUTPUT->header();
echo $OUTPUT->heading('Diyet Programı Oluşturucu');

$form = new userinfo_form();

if ($form->is_cancelled()) {
    redirect(new moodle_url('/'));
} else if ($data = $form->get_data()) {
    $client = new \local_dietgenerator\api\OpenAIClient();
    $prompt = "Kullanıcının yaşı: {$data->age}, cinsiyeti: {$data->gender}, boyu: {$data->height} cm, kilosu: {$data->weight} kg. Bu bilgilere göre 7 günlük bir diyet listesi hazırla. Her gün için sabah kahvaltısı, öğle ve akşam yemeği olacak şekilde belirt.";

    $response = $client->generate($prompt);

    // Şimdilik cevabı yazdıralım
    echo html_writer::div('<strong>OpenAI Yanıtı:</strong><br>' . nl2br($response), 'box generalbox');

    // İleride course builder'a aktaracağız

} else {
    $form->display();
}

echo $OUTPUT->footer();