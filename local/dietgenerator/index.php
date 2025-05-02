<?php


global $CFG, $PAGE, $OUTPUT;
echo $CFG->dirroot;
require('../../config.php');
require_once($CFG->dirroot . '/local/dietgenerator/forms/userinfo_form.php');
require_once($CFG->dirroot . '/local/dietgenerator/classes/api/OpenAIClient.php');
require_once($CFG->dirroot . '/local/dietgenerator/classes/logic/DietCourseBuilder.php');

require_login();

$PAGE->set_context(context_system::instance());
$PAGE->set_url(new moodle_url('/local/dietgenerator/index.php'));
$PAGE->set_title('Diyet Programı Oluşturucu');
$PAGE->set_heading('Diyet Programı Oluşturucu');

// Header ve başlık yazdırma
echo $OUTPUT->header();
echo $OUTPUT->heading('Diyet Programı Oluşturucu');

// Formu oluşturma ve işleme
$form = new userinfo_form();

if ($form->is_cancelled()) {
    // Form iptal edilirse anasayfaya yönlendir
    redirect(new moodle_url('/'));
} else if ($data = $form->get_data()) {
    // Formdan gelen veriler alındı
    // OpenAI API istemcisini oluştur
    $client = new \local_dietgenerator\api\OpenAIClient();

    // Kullanıcıdan alınan verileri kullanarak bir prompt oluştur
    $prompt = "Kullanıcının yaşı: {$data->age}, cinsiyeti: {$data->gender}, boyu: {$data->height} cm, kilosu: {$data->weight} kg. Bu bilgilere göre 7 günlük bir diyet listesi hazırla. Her gün için sabah kahvaltısı, öğle ve akşam yemeği olacak şekilde belirt.";

    // OpenAI API'sine istek gönder
    $response = $client->generate($prompt);

    // Şimdilik cevabı ekrana yazdırıyoruz
    echo html_writer::div('<strong>OpenAI Yanıtı:</strong><br>' . nl2br($response), 'box generalbox');

    // Burada kurs oluşturma kısmını ileride ekleyeceğiz
    // DietCourseBuilder ile alınan yanıtı kursa aktarabilirsiniz

} else {
    // Formu göster
    $form->display();
}

// Footer'ı yazdır
echo $OUTPUT->footer();
