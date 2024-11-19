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
 * Custom web services for this plugin.
 *
 * @package    local_dedicacion_gfw
 * @copyright  2020 Departamento de Formación. Diputación de Alicante.
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die;

$functions = array(
    'local_dedicacion_gfw_get_user_log' => array(
        'classname'    => 'local_dedicacion_gfw_external',
        'methodname'   => 'get_user_log',
        'classpath'    => 'local/dedicacion_gfw/externallib.php',
        'description'  => 'Get user\'s log by id.',
        'type'         => 'read',
        'capabilities' => 'report/log:view, report/log:viewtoday',   // Extraidas de https://github.com/moodle/moodle/blob/MOODLE_31_STABLE/report/log/db/access.php
    ),
);
