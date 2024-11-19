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
 * Funciones externas para devolver información sobre la dedicación de usuarios.
 *
 * @package    local_dedicacion_gfw
 * @copyright  2020 Departamento de Formación. Diputación de Alicante.
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die;

require_once("$CFG->dirroot/enrol/externallib.php");

/**
 * Returns a user's dedication log.
 *
 * @package    local_dedicacion_gfw
 * @copyright  2020 Departamento de Formación. Diputación de Alicante.
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class local_dedicacion_gfw_external extends external_api {
    /**
     * Returns description of method parameters
     *
     * @return external_function_parameters
     */
    public static function get_user_log_parameters() {
        return new external_function_parameters(
                array(
                    'userid' => new external_value(PARAM_INT, 'Id del usuario'),
                    'courseid' => new external_value(PARAM_INT, 'Id del curso'),
                    )
        );
    }
    
    /**
     * Get a user's log.
     *
     * @param int $userid
     * @param int $courseid
     * @return array
     */
    public static function get_user_log($userid, $courseid) {
        global $DB;

        // Validate parameters passed from webservice.
        $params = self::validate_parameters(self::get_user_log_parameters(), array('userid' => $userid, 'courseid' => $courseid));

        // https://github.com/moodle/moodle/blob/master/report/log/classes/table_log.php
        $joins = array();
        $paramsSelect = array();

        // Filtrar por user Id.
        if (!empty($params['userid'])) {
            $joins[] = "userid = :userid";
            $paramsSelect['userid'] = $params['userid'];
        }

        // Filtrar por course Id.
        if (!empty($params['courseid']) && $params['courseid'] != SITEID) {
            $joins[] = "courseid = :courseid";
            $paramsSelect['courseid'] = $params['courseid'];
        }      

        $selectwhere = implode(' AND ', $joins);

        $sort = "id ASC";  // https://github.com/moodle/moodle/blob/MOODLE_31_STABLE/admin/tool/log/classes/helper/reader.php
        $records = $DB->get_recordset_select('logstore_standard_log', $selectwhere, $paramsSelect, $sort, '*', '', '');  // https://github.com/moodle/moodle/blob/MOODLE_31_STABLE/admin/tool/log/store/standard/classes/log/store.php

        // Recorremos el recordset
        $events = array();
        foreach ($records as $data) {
            if ($event = self::get_log_event($data)) {

                $events[] = array(
                    'timecreated' => $event->timecreated,
                    'component' => $event->component,
                    'userid' => $event->userid,
                    'courseid' => $event->courseid,
                    'ip' => $event->get_logextra()['ip'],
                );
            }
        }
        
        $records->close();

        return $events;
    }

    /**
     * Returns an event from the log data. https://github.com/moodle/moodle/blob/MOODLE_31_STABLE/admin/tool/log/store/standard/classes/log/store.php
     *
     * @param stdClass $data Log data
     * @return \core\event\base
     */
    protected static function get_log_event($data) {
        $extra = array('origin' => $data->origin, 'ip' => $data->ip, 'realuserid' => $data->realuserid);
        $data = (array)$data;
        $id = $data['id'];
        $data['other'] = unserialize($data['other']);
        if ($data['other'] === false) {
            $data['other'] = array();
        }
        unset($data['origin']);
        unset($data['ip']);
        unset($data['realuserid']);
        unset($data['id']);
        if (!$event = \core\event\base::restore($data, $extra)) {
            return null;
        }
        return $event;
    }

    /**
     * Returns description of get_courses_by_username_returns() result value.
     *
     * @return \external_description
     */
    public static function get_user_log_returns() {
        return new external_multiple_structure(
            new external_single_structure(
                array(
                    'timecreated'  => new external_value(PARAM_INT, 'tiempo del evento'),
                    'component'    => new external_value(PARAM_RAW, 'componente'),
                    'userid'       => new external_value(PARAM_INT, 'id de usuario'),
                    'courseid'     => new external_value(PARAM_INT, 'id curso'),
                    'ip'           => new external_value(PARAM_RAW, 'ip'),
                    )
            )
        );
    }
}
