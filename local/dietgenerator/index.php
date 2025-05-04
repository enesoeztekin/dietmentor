<?php

require('../../config.php');
require_once('forms/userinfo_form.php');
require_once('classes/api/OpenAIClient.php');
require_once('classes/logic/DietCourseBuilder.php');

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

    $prompt = "Kullanıcının yaşı: {$data->age}, cinsiyeti: {$data->gender}, boyu: {$data->height} cm, kilosu: {$data->weight} kg. Bu bilgilere sahip bir bireyin günlük makro ihtiyacına göre 7 günlük detaylı bir diyet listesi hazırla. Her gün için sabah kahvaltısı, öğle ve akşam yemeği olacak şekilde belirt. Her besinin gramajını veya adetini belirt. Format:\n\nGün 1:\nSabah: ...\nÖğle: ...\nAkşam: ...\n\nGün 2:\n... (böyle devam et)";

    $response = $client->generate($prompt);

    if (!$response) {
        echo $OUTPUT->notification('OpenAI yanıtı alınamadı.', 'error');
    } else {
        echo html_writer::div('Merhaba <strong>'.$USER->firstname.',</strong> girmiş olduğun kişisel bilgilere göre 7 günlük kişisel beslenme programın <strong>yapay zeka</strong> tarafından oluşturuldu. Sağlıklı günler! <br>', 'box boxaligncenter');

        $builder = new \local_dietgenerator\logic\DietCourseBuilder();
        try {
            $courseid = $builder->create_personal_diet_course($USER, $response);
            $courselink = new moodle_url('/course/view.php', ['id' => $courseid]);
            echo $OUTPUT->notification('Kişisel programına ulaşmak için aşağıdaki bağlantıyı kullanabilirsin: <br><a href="' . $courselink . '">Programı Görüntüle</a>', 'notifysuccess');
        } catch (Exception $e) {
            echo $OUTPUT->notification('Kurs oluşturulurken hata oluştu: ' . $e->getMessage(), 'error');
        }
    }
} else {
    $form->display();
}

echo $OUTPUT->footer();