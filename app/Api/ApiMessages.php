<?php

namespace App\Api;

class ApiMessages{

    private $message = [];

    public function __construct(string $message,array $data = []){ //mensagem e outra informação

        $this->message['message'] = $message;
        $this->message['errors'] = $data;

    }

    public function getMessage(){
        return $this->message;
    }

}