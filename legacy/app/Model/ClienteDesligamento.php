<?php

App::uses('AppModel', 'Model');

class ClienteDesligamento extends AppModel
{
    public $name = 'ClienteDesligamento';
    public $useTable = 'cliente_desligamento';
    public $primaryKey = 'id';
}
