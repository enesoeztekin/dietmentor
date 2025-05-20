<?php
// This file is part of Moodle theme - Moove.

defined('MOODLE_INTERNAL') || die();

echo $OUTPUT->doctype();
?>
<html {{ $OUTPUT->htmlattributes() }}>
<head>
    <title><?php echo $OUTPUT->page_title(); ?></title>
    <?php echo $OUTPUT->standard_head_html(); ?>
</head>
<body <?php echo $OUTPUT->body_attributes(); ?>>
<?php echo $OUTPUT->standard_top_of_body_html(); ?>

<div id="page">
    <div id="page-content" class="container">
        <?php echo $OUTPUT->main_content(); ?>
    </div>
</div>

<?php echo $OUTPUT->standard_end_of_body_html(); ?>
</body>
</html>