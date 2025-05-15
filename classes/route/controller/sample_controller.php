<?php
// This file is part of Moodle - https://moodle.org/
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
// along with Moodle.  If not, see <https://www.gnu.org/licenses/>.

namespace local_routing\route\controller;

use core\context\system;
use core\router;
use core\router\require_login;
use core\router\route;
use core\router\schema\parameters\query_parameter;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Sample controller for the routing plugin.
 *
 * @package     local_routing
 * @category    string
 * @copyright   2025 Laurent David <laurent.david@moodle.com>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class sample_controller {
    use \core\router\route_controller;

    /**
     * Constructor for the sample controller handler.
     *
     * @param \core\router $router The router.
     */
    public function __construct(
        /** @var router The routing engine */
        private router $router,
    ) {
    }

    /**
     * Handle the sample controller page.
     *
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @return ResponseInterface
     */
    #[route(
        path: '/sample',
        method: ['GET', 'POST'],
        queryparams: [
            new query_parameter(
                name: 'message',
                type: \core\param::ALPHANUMEXT,
                default: 'Hello world!',
            ),
        ],
        requirelogin: new require_login(
            requirelogin: true,
            courseattributename: 'course',
        ),
    )]
    public function handler(
        ServerRequestInterface $request,
        ResponseInterface $response,
    ): ResponseInterface {
        global $PAGE, $OUTPUT;

        $context = system::instance();

        $message = $request->getQueryParams()['message'];
        $title = get_string('pluginname', 'local_routing');
        $PAGE->set_url('/sample');
        $PAGE->set_context($context);
        $PAGE->set_title($title);
        $PAGE->set_heading($title);
        $code = 200;
        $response = $response->withStatus($code);
        $response->getBody()->write($OUTPUT->header());
        $response->getBody()->write($OUTPUT->heading($title));
        $response->getBody()->write(
            $OUTPUT->box($message)
        );
        $response->getBody()->write($OUTPUT->footer());

        return $response;
    }
}
