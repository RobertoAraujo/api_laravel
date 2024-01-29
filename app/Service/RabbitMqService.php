<?php

namespace App\Service;

use Illuminate\Support\Facades\Log;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;
use Exception;

class RabbitMqService
{
    private $host;
    private $port;
    private $user;
    private $pass;

    public function __construct()
    {
        $this->host = env('RABBITMQ_HOST');
        $this->port = env('RABBITMQ_PORT');
        $this->user = env('RABBITMQ_LOGIN');
        $this->pass = env('RABBITMQ_PASSWORD');
    }

    public function enviarMensagem(array $dados)
    {
        try {
            $connection = new AMQPStreamConnection($this->host, $this->port, $this->user, $this->pass);

            $queue = 'qdd-api';
            $channel = $connection->channel();
            $channel->queue_declare($queue, 'fonte', true, false, false);

            $jsonMessage = json_encode($dados);

            $rabbitmqMsg = new AMQPMessage($jsonMessage);
            $channel->basic_publish($rabbitmqMsg, '', 'qdd-api');
            // $canal -> queue_bind ( $queue , 'logs' );

            $channel->close();
            $connection->close();

            Log::info('Mensagem enviada com sucesso para o RabbitMQ.');
        } catch (Exception $ex) {
            Log::error('Erro ao enviar mensagem para o RabbitMQ: ' . $ex->getMessage());
        }
    }
}
