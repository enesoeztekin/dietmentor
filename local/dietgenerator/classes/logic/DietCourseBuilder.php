<?php
// classes/logic/DietCourseBuilder.php

namespace local_dietgenerator\logic;

defined('MOODLE_INTERNAL') || die();

use stdClass;
use context_course;

global $CFG;
require_once($CFG->dirroot . '/course/lib.php');

class DietCourseBuilder {

    public static function create_personal_diet_course(stdClass $user, string $dietResponse): int {
        global $DB;

        // 1. Kurs ayarları
        $course = new stdClass();
        $course->fullname = "Kişisel Diyet Programın ({$user->firstname})";
        $course->shortname = 'diyet_' . time();
        $course->category = 3;
        $course->format = 'topics';
        $course->numsections = 7;
        $course->visible = 1;
        $course->summary = 'Bu kurs, sana özel hazırlanmış 7 günlük bir diyet planını içerir.';

        // 2. Kursu oluştur
        $createdcourse = create_course($course); // stdClass döner
        $courseid = $createdcourse->id;

        // 3. Kullanıcıyı öğrenci olarak kaydet
        self::enrol_user_as_student($user->id, $courseid);

        // 4. İçerik oluştur
        self::create_sections_and_content($createdcourse, $dietResponse);

        return $courseid;
    }

    private static function enrol_user_as_student(int $userid, int $courseid): void {
        global $DB;
        $studentRole = $DB->get_record('role', ['shortname' => 'student'], '*', MUST_EXIST);
        enrol_try_internal_enrol($courseid, $userid, $studentRole->id);
    }

    private static function create_sections_and_content(stdClass $course, string $dietResponse): void {
        global $DB;

        $sections = explode("\n\n", trim($dietResponse));

        foreach ($sections as $index => $daytext) {
            $sectionnum = $index + 1;
            $sectionname = "{$sectionnum}. Gün";

            // Section oluştur
            $sectionid = course_create_section($course->id, $sectionnum)->id;
            $DB->set_field('course_sections', 'name', $sectionname, ['id' => $sectionid]);

            // Öğünleri ayır
            $meals = preg_split('/\n(?=Sabah|Öğle|Akşam)/', $daytext);

            foreach ($meals as $mealtext) {
                $moduleid = self::add_label_to_section($course, $sectionnum, trim($mealtext));
                $DB->set_field('course_modules', 'completion', COMPLETION_TRACKING_MANUAL, ['id' => $moduleid]);
            }
        }
    }

    private static function add_label_to_section(stdClass $course, int $sectionnum, string $text): int {
        $moduleinfo = new stdClass();
        $moduleinfo->modulename = 'label';
        $moduleinfo->course = $course->id;
        $moduleinfo->section = $sectionnum;
        $moduleinfo->name = '';
        $moduleinfo->intro = format_text($text, FORMAT_HTML);
        $moduleinfo->introformat = FORMAT_HTML;
        $moduleinfo->visible = 1;
        $moduleinfo->completion = COMPLETION_TRACKING_MANUAL;

        // Global namespace'teki fonksiyonu çağırıyoruz
        $labelmodule = \add_moduleinfo($moduleinfo, $course);

        return $labelmodule->coursemodule;
    }
}
