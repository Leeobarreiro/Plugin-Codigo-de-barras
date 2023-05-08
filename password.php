<?php

// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Displays help via AJAX call or in a new page
 *
 * Use {@see core_renderer::help_icon()} or {@see addHelpButton()} to display
 * the help icon.
 *
 * @copyright  2017 Dan Marsden
 * @package    mod_attendance
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once(dirname(__FILE__).'/../../config.php');
require_once(dirname(__FILE__).'/locallib.php');
require_once($CFG->libdir.'/tcpdf/tcpdf_barcodes_2d.php'); // Used for generating qrcode.

function generate_unique_password($sessionid, $userid) {
    // Concatenate the session ID and user ID to create a unique password.
    // You could use other methods to generate unique passwords as well.
    return md5($sessionid . $userid);
}

$sessionid = required_param('session', PARAM_INT);
$userid = $USER->id;

$session = $DB->get_record('attendance_sessions', array('id' => $sessionid), '*', MUST_EXIST);

$cm = get_coursemodule_from_instance('attendance', $session->attendanceid);
require_login($cm->course, $cm);

$context = context_module::instance($cm->id);
$capabilities = array('mod/attendance:manageattendances', 'mod/attendance:takeattendances', 'mod/attendance:changeattendances');
if (!has_any_capability($capabilities, $context)) {
    exit;
}

// Generate a unique password and QR code for the current user and session.
$password = generate_unique_password($sessionid, $userid);
$qr_code = md5($password);

// Update the attendance log with the generated password and QR code.
$log = $DB->get_record('attendance_log', array('sessionid' => $sessionid, 'studentid' => $userid), '*', MUST_EXIST);
$log->password = $password;
$log->qrcode = $qr_code;
$DB->update_record('attendance_log', $log);

$PAGE->set_url('/mod/attendance/password.php');
$PAGE->set_pagelayout('popup');
$PAGE->set_context(context_system::instance());
$PAGE->set_title(get_string('password', 'attendance'));

echo $OUTPUT->header();

// Display the QR code to the user.
echo '<img src="https://chart.googleapis.com/chart?cht=qr&chs=300x300&chl='.$qr_code.'&choe=UTF-8" alt="'.get_string('qrcode', 'attendance').'"/>';

// Display the password to the user.
echo '<p>'.get_string('password', 'attendance').': '.$password.'</p>';

echo $OUTPUT->footer();
