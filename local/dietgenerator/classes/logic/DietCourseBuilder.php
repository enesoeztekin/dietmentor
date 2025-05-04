<?php
// classes/logic/DietCourseBuilder.php

namespace local_dietgenerator\logic;

defined('MOODLE_INTERNAL') || die();

use stdClass;
use context_course;
use core\course\course_create;
use core\completion\cm_completion_details;

class DietCourseBuilder {

    public static function create_personal_diet_course(stdClass $user, string $dietResponse): int {
        global $DB;

        // 1. Kurs ayarları
        $course = new stdClass();
        $course->fullname = "Kişisel Diyet Programın ({$user->firstname})";
        $course->shortname = 'diyet_' . time();
        $course->category = 3; // Varsayılan kategori ID'si, gerektiğinde değiştir
        $course->format = 'topics';
        $course->numsections = 7;
        $course->visible = 1;
        $course->summary = 'Bu kurs, sana özel hazırlanmış 7 günlük bir diyet planını içerir.';

        // 2. Kurs oluştur
        $courseid = create_course($course)->id;

        // 3. Kullanıcıyı öğrenci olarak kaydet
        self::enrol_user_as_student($user->id, $courseid);

        // 4. OpenAI cevabını günlere böl ve kurs içeriklerini oluştur
        self::create_sections_and_content($courseid, $dietResponse);

        return $courseid;
    }

    private static function enrol_user_as_student(int $userid, int $courseid): void {
        global $DB;

        $studentRole = $DB->get_record('role', ['shortname' => 'student'], '*', MUST_EXIST);
        $context = context_course::instance($courseid);

        enrol_try_internal_enrol($courseid, $userid, $studentRole->id);
    }

    private static function create_sections_and_content(int $courseid, string $dietResponse): void {
        global $DB;

        // 7 gün için bölümleri ayır
        preg_match_all('/(?:Gün|\d+\. Gün)[:\s\n]*(.*?)\n*(Sabah|Öğle|Akşam)/si', $dietResponse, $matches, PREG_OFFSET_CAPTURE);

        $sections = explode("\n\n", $dietResponse); // OpenAI'dan gelen cevaba göre ayırma yapılabilir

        foreach ($sections as $index => $daytext) {
            $sectionnum = $index + 1;
            $sectionname = ($sectionnum) . ". Gün";

            // Section başlığı ve içeriği
            $sectionid = course_create_section($courseid, $sectionnum);
            $DB->set_field('course_sections', 'name', $sectionname, ['id' => $sectionid]);

            // Sabah, öğle, akşam yemeklerini ayır
            $meals = preg_split('/\n(?=Sabah|Öğle|Akşam)/', $daytext);

            foreach ($meals as $mealtext) {
                $moduleid = self::add_label_to_section($courseid, $sectionnum, trim($mealtext));
                // Otomatik tamamlama ayarı (isteğe bağlı)
                $DB->set_field('course_modules', 'completion', COMPLETION_TRACKING_MANUAL, ['id' => $moduleid]);
                $DB->set_field('course_modules', 'completionexpected', time() + 86400); // 1 gün sonra tamamlansın
            }
        }
    }

    private static function add_label_to_section(int $courseid, int $sectionnum, string $text): int {
        global $DB;

        $label = new stdClass();
        $label->course = $courseid;
        $label->name = "";
        $label->intro = format_text($text, FORMAT_HTML);
        $label->introformat = FORMAT_HTML;
        $label->visible = 1;

        $moduleid = add_moduleinfo((object) [
            'modulename' => 'label',
            'section' => $sectionnum,
            'course' => $courseid,
            'name' => '',
            'intro' => $label->intro,
            'introformat' => FORMAT_HTML,
            'visible' => 1,
            'completion' => COMPLETION_TRACKING_MANUAL,
        ], null);

        return $moduleid;
    }
}
