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

namespace Fusio\Adapter\Mqtt\Connection;

use Fusio\Engine\Connection\PingableInterface;
use Fusio\Engine\ConnectionInterface;
use Fusio\Engine\Form\BuilderInterface;
use Fusio\Engine\Form\ElementFactoryInterface;
use Fusio\Engine\ParametersInterface;
use PhpMqtt\Client\ConnectionSettings;
use PhpMqtt\Client\Contracts\MqttClient as MqttClientInterface;
use PhpMqtt\Client\Exceptions;
use PhpMqtt\Client\MqttClient;

/**
 * Mqtt
 *
 * @author  Tobias Soltermann <tobias.soltermann@gmail.com>
 * @license http://www.gnu.org/licenses/agpl-3.0
 * @link    https://www.fusio-project.org/
 */
class Mqtt implements ConnectionInterface, PingableInterface
{
    public function getName(): string
    {
        return 'MQTT';
    }

    /**
     * @throws Exceptions\ConnectingToBrokerFailedException
     * @throws Exceptions\ProtocolNotSupportedException
     * @throws Exceptions\ConfigurationInvalidException
     */
    public function getConnection(ParametersInterface $config): MqttClientInterface
    {
        $connectionSettings = (new ConnectionSettings())
            ->setUsername($config->get('user') ?: null)
            ->setPassword($config->get('password') ?: null)
        ;

        $clientId = $config->get('clientid');
        if (empty($clientId)) {
            $clientId = null;
        }

        $client = new MqttClient(
            $config->get('host'),
            $config->get('port') ?: 1883,
            $clientId
        );
        $client->connect($connectionSettings, true);

        return $client;
    }

    public function configure(BuilderInterface $builder, ElementFactoryInterface $elementFactory): void
    {
        $builder->add($elementFactory->newInput('host', 'Host', 'text', 'The IP or hostname of the MQTT server'));
        $builder->add($elementFactory->newInput('port', 'Port', 'number', 'The port used to connect to the MQTT broker. The port default is 1883'));
        $builder->add($elementFactory->newInput('user', 'User', 'text', 'The login string used to authenticate with the MQTT broker'));
        $builder->add($elementFactory->newInput('password', 'Password', 'password', 'The password string used to authenticate with the MQTT broker'));
        $builder->add($elementFactory->newInput('clientid', 'Client ID', 'text', 'The client id to supply to the MQTT broker'));
    }

    public function ping(mixed $connection): bool
    {
        if ($connection instanceof MqttClient) {
            return $connection->isConnected();
        } else {
            return false;
        }
    }
}
