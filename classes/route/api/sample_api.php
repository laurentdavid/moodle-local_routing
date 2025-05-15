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

namespace local_routing\route\api;

use core\param;
use core\router\route;
use core\router\schema\objects\scalar_type;
use core\router\schema\response\payload_response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Sample API for the routing plugin.
 *
 * @package     local_routing
 * @category    string
 * @copyright   2025 Laurent David <laurent.david@moodle.com>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class sample_api {
    use \core\router\route_controller;

    /**
     * Fetch a single template for a component in a theme.
     *
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @param string $message
     * @return payload_response
     */
    #[route(
        title: 'Print a simple message',
        description: 'Fetch information about the current site and add it to a message',
        security: [],
        path: '/sampleapi/{message}',
        method: ['GET'],
        pathtypes: [
            new \core\router\schema\parameters\path_parameter(
                name: 'message',
                type: param::ALPHANUMEXT,
                description: 'The message to be printed',
                default: 'Hello world !',
                required: false,
            ),
        ],
        headerparams: [
            new \core\router\parameters\header_language(),
        ],
        responses: [
            new \core\router\schema\response\response(
                statuscode: 200,
                description: 'OK',
                content: [
                    new \core\router\schema\response\content\json_media_type(
                        schema: new \core\router\schema\objects\schema_object(
                            content: [
                                'message' => new scalar_type(
                                    type: param::ALPHANUMEXT,
                                    description: "The message with all information about the site.",
                                ),
                            ],
                        ),
                        examples: [
                            new \core\router\schema\example(
                                name: 'A simple message',
                                summary: 'A json response containing simple message',
                                value: [
                                    'message' => 'Hello world! This is a sample API.\
                                     The site name is Moodle and the site URL is https://moodle.example.com.',
                                ],
                            ),
                        ]
                    ),
                ],
            ),
        ],
    )]
    public function handler(
        ServerRequestInterface $request,
        ResponseInterface $response,
        string $message,
    ): payload_response {
        global $PAGE, $SITE, $CFG;

        $PAGE->set_context(\core\context\system::instance());

        $params = $request->getQueryParams();
        $messageprovided = $message ?? '';
        $sitefullname = $SITE->fullname;
        $siteurl = $CFG->wwwroot;
        $language = json_encode($request->getHeader('language'));
        $returnmessage = "$messageprovided - This is a sample API. The site name is $sitefullname  and the site URL is $siteurl.\
                Language is $language.";

        $result = [
            'message' => $returnmessage,
        ];

        return new payload_response(
            payload: $result,
            request: $request,
        );
    }
}
