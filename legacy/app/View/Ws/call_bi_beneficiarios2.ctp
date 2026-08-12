<?php

$this->response->type('application/json');
$this->response->body(json_encode($this->viewVars));