<?php

spl_autoload_register(function($class){
    $amqp = __DIR__ . '/../vendor/php-amqplib/php-amqplib/';
    $import = $amqp . str_replace(['\\'],['/'], $class) . '.php';
    $vendor = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($amqp));
    foreach ($vendor as $file) {
        if ('php' != $file->getExtension()) {
            continue;
        }
        require_once $import;
    }
});

// Rabbitmq
use PhpAmqpLib\Connection\AMQPConnection;
use PhpAmqpLib\Exchange\AMQPExchangeType;
use PhpAmqpLib\Message\AMQPMessage;

testPg();
testRedis();
testMq();

function testPg()
{ //php -f script/check.php
    $pg = new \PDO("pgsql:host=postgres;port=5432;dbname=pgdb", "user", "pass");
    $stmt = $pg->query("SELECT version()");
    $stmt->execute();
    $version = $stmt->fetchAll();
    echo $version[0]["version"]. "\n";
}

function testRedis()
{
    $redis = new Redis();
    $redis->connect('127.0.0.1', 6379);
    $redis->set('alpha', "Redis key-value OK");
    echo $redis->get('alpha')."\n";
}

// https://www.rabbitmq.com/tutorials/tutorial-one-php.html
function testMq()
{
    $connection = new AMQPConnection('rabbitmq', 5672, 'guest', 'guest');
    $channel = $connection->channel();
    
    $channel->queue_declare('email_queue', false, false, false, false);
    $data = "Hello world from Amqp!";
    
    $msg = new AMQPMessage($data, array('delivery_mode' => 2));
    $channel->basic_publish($msg, '', 'email_queue');

    $connection = new AMQPConnection('rabbitmq', 5672, 'guest', 'guest');
    $channel = $connection->channel();
    
    $channel->queue_declare('email_queue', false, false, false, false);
    
    $callback = function($msg){
        echo "Message received : ";
        $msg->delivery_info['channel']->basic_ack($msg->delivery_info['delivery_tag']);
        echo $msg->body."\n";
    };
    
    $channel->basic_qos(null, 1, null);
    $channel->basic_consume('email_queue', '', false, false, false, false, $callback);
    
    if(count($channel->callbacks)) {
        $channel->wait();
    }

    // close
    $channel->close();
    $connection->close();
}
