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

namespace local_routing\route\controller;

use core\router\route_loader_interface;
use core\tests\router\route_testcase;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\CoversFunction;

/**
 * Sample controller test.
 *
 * @package     local_routing
 * @category    string
 * @copyright   2025 Laurent David <laurent.david@moodle.com>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 **/
#[CoversClass(\local_routing\route\controller\sample_controller::class)]
class sample_controller_test extends route_testcase {
    /**
     * Test the sample route.
     *
     * @return void
     */
    public function test_sample_route() {
        $this->resetAfterTest();
        $this->setAdminUser(); // Set the user to admin if not we are redirected to the login page.
        $request = $this->create_request('GET', 'local_routing/sample/hello', route_loader_interface::ROUTE_GROUP_PAGE);
        $response = $this->get_app()->handle($request);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertStringContainsString('hello', (string) $response->getBody());
    }
}