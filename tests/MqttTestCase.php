<?php
/*
 * Fusio
 * A web-application to create dynamically RESTful APIs
 *
 * Copyright (C) 2015-2022 Christoph Kappestein <christoph.kappestein@gmail.com>
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

namespace Fusio\Adapter\Mqtt\Tests;

use Fusio\Adapter\Mqtt\Connection\Mqtt;
use Fusio\Engine\Model\Connection;
use Fusio\Engine\Parameters;
use Fusio\Engine\Test\CallbackConnection;
use Fusio\Engine\Test\EngineTestCaseTrait;
use PhpMqtt\Client\MqttClient;
use PHPUnit\Framework\TestCase;

/**
 * MqttTestCase
 *
 * @author  Christoph Kappestein <christoph.kappestein@gmail.com>
 * @license http://www.gnu.org/licenses/agpl-3.0
 * @link    https://www.fusio-project.org/
 */
abstract class MqttTestCase extends TestCase
{
    use EngineTestCaseTrait;

    protected MqttClient $connection;

    protected function setUp(): void
    {
        if (!$this->connection) {
            $this->connection = $this->newConnection();
        }

        $connection = new Connection(1, 'foo', CallbackConnection::class, [
            'callback' => function(){
                return $this->connection;
            },
        ]);

        $this->getConnectionRepository()->add($connection);
    }

    protected function newConnection(): MqttClient
    {
        $connector = new Mqtt();

        try {
            $connection = $connector->getConnection(new Parameters([
                'host'     => '127.0.0.1',
                'port'     => 1883,
                'user'     => 'guest',
                'password' => 'guest',
                'topic'    => 'mytopic/test'
            ]));

            return $connection;
        } catch (\Exception $e) {
            $this->markTestSkipped('Memcache connection not available');
        }
    }
}
