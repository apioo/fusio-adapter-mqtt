<?php
/*
 * Fusio
 * A web-application to create dynamically RESTful APIs
 *
 * Copyright (C) 2015 Christoph Kappestein <christoph.kappestein@gmail.com>
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

namespace Fusio\Adapter\Mqtt\Tests\Connection;

use Fusio\Adapter\Mqtt\Connection\Mqtt;
use Fusio\Adapter\Mqtt\Tests\MqttTestCase;
use Fusio\Engine\Form\Builder;
use Fusio\Engine\Form\Container;
use Fusio\Engine\Form\Element\Input;
use Fusio\Engine\Parameters;
use PhpMqtt\Client\Contracts\MqttClient as MqttClientInterface;

/**
 * MqttTest
 *
 * @author  Christoph Kappestein <christoph.kappestein@gmail.com>
 * @license http://www.apache.org/licenses/LICENSE-2.0
 * @link    https://www.fusio-project.org/
 */
class MqttTest extends MqttTestCase
{
    public function testGetConnection()
    {
        $connectionFactory = $this->getConnectionFactory()->factory(Mqtt::class);

        $config = new Parameters([
            'host'     => '127.0.0.1',
            'port'     => 1883,
            'user'     => 'guest',
            'password' => 'guest',
            'clientid' => ''
        ]);

        $connection = $connectionFactory->getConnection($config);

        $this->assertInstanceOf(MqttClientInterface::class, $connection);
    }

    public function testConfigure()
    {
        $connection = $this->getConnectionFactory()->factory(Mqtt::class);
        $builder    = new Builder();
        $factory    = $this->getFormElementFactory();

        $connection->configure($builder, $factory);

        $this->assertInstanceOf(Container::class, $builder->getForm());

        $elements = $builder->getForm()->getElements();
        $this->assertEquals(5, count($elements));
        $this->assertInstanceOf(Input::class, $elements[0]);
        $this->assertInstanceOf(Input::class, $elements[1]);
        $this->assertInstanceOf(Input::class, $elements[2]);
        $this->assertInstanceOf(Input::class, $elements[3]);
        $this->assertInstanceOf(Input::class, $elements[4]);
    }

    public function testPing()
    {
        /** @var Mqtt $connectionFactory */
        $connectionFactory = $this->getConnectionFactory()->factory(Mqtt::class);

        $config = new Parameters([
            'host'     => '127.0.0.1',
            'port'     => 1883,
            'user'     => 'guest',
            'password' => 'guest',
            'clientid' => ''
        ]);

        $connection = $connectionFactory->getConnection($config);

        $this->assertTrue($connectionFactory->ping($connection));
    }
}
