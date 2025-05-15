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

namespace local_routing\route\shim;

use core\param;
use core\router\require_login;
use core\router\route;
use core\router\route_controller;
use core\router\schema\parameters\query_parameter;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * A shim for local/routing/index.php routes.
 *
 * @package     local_routing
 * @category    string
 * @copyright   2025 Laurent David <laurent.david@moodle.com>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
final class sample_shim {
    use route_controller;

    /**
     * Shim /course/admin.php to the course management controller.
     *
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @return ResponseInterface
     */
    #[route(
        path: '/index.php', // Important here: the base route is supposed to be in /local/routing.
        queryparams: [
            new query_parameter(
                name: 'message',
                type: param::ALPHANUMEXT,
                description: 'Message to be printed',
                required: false,
                default: "Hello !",
            ),
        ],
        requirelogin: new require_login(
            requirelogin: true,
            courseattributename: 'course',
        ),
    )]
    public function handle_my_index(
        ServerRequestInterface $request,
        ResponseInterface $response,
    ): ResponseInterface {
        global $OUTPUT, $PAGE;
        $PAGE->set_context(\context_system::instance());
        $PAGE->set_pagelayout('standard');
        $PAGE->set_title(get_string('pluginname', 'local_routing'));
        $PAGE->set_heading(get_string('pluginname', 'local_routing'));

        $params = $request->getQueryParams();
        $message = $params['message'] ?? "Hello !";
        $response = $response->withStatus(200);
        $response->getBody()->write($OUTPUT->header());
        $response->getBody()->write($OUTPUT->heading(get_string('welcomeshimmed', 'local_routing')));
        $response->getBody()->write($OUTPUT->box($message));
        $response->getBody()->write($OUTPUT->footer());
        return $response;
    }
}
