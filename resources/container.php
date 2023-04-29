<?php

use Fusio\Adapter\Mqtt\Action\MqttPublish;
use Fusio\Adapter\Mqtt\Connection\Mqtt;
use Fusio\Engine\Adapter\ServiceBuilder;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $container) {
    $services = ServiceBuilder::build($container);
    $services->set(Mqtt::class);
    $services->set(MqttPublish::class);
};
