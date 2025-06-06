<?php
// classes/logic/DietCourseBuilder.php

namespace local_dietgenerator\logic;

defined('MOODLE_INTERNAL') || die();

use stdClass;
use context_course;

global $CFG;
require_once($CFG->dirroot . '/course/lib.php');
require_once($CFG->dirroot . '/course/modlib.php');

class DietCourseBuilder {

    public static function create_personal_diet_course(stdClass $user, string $dietResponse): int {
        global $DB;

        // 1. Kurs ayarları
        $course = new stdClass();
        $course->fullname = "Kişisel Diyet Programın ({$user->firstname})";
        $course->shortname = 'diyet_' . time();
        $course->category = 3; // Mevcut bir kategori ID olduğundan emin olun
        $course->format = 'topics';
        $course->visible = 1;
        $course->summary = 'Bu kurs, sana özel hazırlanmış 7 günlük bir diyet planını içerir.';

        // 2. Kurs oluştur (stdClass döner)
        $createdcourse = create_course($course);
        $courseid = $createdcourse->id;

        // 3. Kullanıcıyı öğrenci olarak kaydet
        self::enrol_user_as_student($user->id, $courseid);

        // 4. İçeriği oluştur
        self::create_sections_and_content($courseid, $dietResponse);

        return $courseid;
    }

    private static function enrol_user_as_student(int $userid, int $courseid): void {
        global $DB;

        $studentRole = $DB->get_record('role', ['shortname' => 'student'], '*', MUST_EXIST);
        enrol_try_internal_enrol($courseid, $userid, $studentRole->id);
    }

    private static function create_sections_and_content(int $courseid, string $dietResponse): void {
        global $DB;

        $course = get_course($courseid);
        $modinfo = get_fast_modinfo($course);

        $sections = explode("\n\n", trim($dietResponse));

        foreach ($sections as $index => $daytext) {
            $sectionnum = $index + 1;
            $sectionname = "{$sectionnum}. Gün";

            // Section oluştur ve ismini ayarla
            $sectionid = course_create_section($courseid, $sectionnum)->id;
            $DB->set_field('course_sections', 'name', $sectionname, ['id' => $sectionid]);

            // Öğünleri ayıkla
            preg_match('/Sabah:\s*(.*?)\s*Öğle:/s', $daytext, $matchMorning);
            preg_match('/Öğle:\s*(.*?)\s*Akşam:/s', $daytext, $matchLunch);
            preg_match('/Akşam:\s*(.*)/s', $daytext, $matchDinner);

            $meals = [
                'Sabah Kahvaltısı' => $matchMorning[1] ?? '',
                'Öğle Yemeği' => $matchLunch[1] ?? '',
                'Akşam Yemeği' => $matchDinner[1] ?? ''
            ];

            foreach ($meals as $title => $mealText) {
                $fromform = new stdClass();
                $fromform->course = $courseid;
                $fromform->section = $sectionnum;
                $fromform->module = $DB->get_field('modules', 'id', ['name' => 'label']);
                $fromform->modulename = 'label';
                $fromform->instance = 0;
                $fromform->visible = 1;
                $fromform->intro = "<strong>{$title}:</strong> {$mealText}";
                $fromform->introformat = FORMAT_HTML;
                $fromform->name = $title;
                $fromform->showdescription = 0;
                $fromform->completion = COMPLETION_TRACKING_MANUAL;

                add_moduleinfo($fromform, $course, $modinfo);
            }
        }
    }
}
