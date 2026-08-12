<?php

App::uses('AppModel', 'Model');

/**
 * Agendamento Model
 *
 */
class Agendamento extends AppModel
{

    public $useTable = 'agendamento';
    //    public $recursive = 2;

    public $belongsTo = array(
        'Atendimento' => array(
            'className' => 'Atendimento',
            'foreignKey' => 'atendimento_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        ),
        'Usuario' => array(
            'className' => 'Usuario',
            'foreignKey' => 'usuario_id',
            'conditions' => '',
            'fields' => 'id,nome,email',
            'order' => ''
        ),
        'UsuarioAgendamento' => array(
            'className' => 'Usuario',
            'foreignKey' => 'usuario_agendamento_id',
            'conditions' => '',
            'fields' => 'id,nome,email',
            'order' => ''
        )
    );

    public $hasMany = array();


    // Paginação custom com SQL cru
    public function paginate($conditions, $fields, $order, $limit, $page = 1, $recursive = null, $extra = array())
    {
        $db = $this->getDataSource();

        // Converte conditions do Cake para SQL (seguro e com quoting)
        $where = trim($db->conditions($conditions, true, true, $this));
        if ($where === '' || $where === null) {
            $where = '1 = 1';
        }

        // Converte order do Cake para ORDER BY
        $orderSql = trim($db->order($order, 'Agendamento'));
        if ($orderSql === '' || $orderSql === null) {
            $orderSql = 'ORDER BY Agendamento.status ASC, Agendamento.data_hora ASC';
        }

        $offset = max(0, ((int)$page - 1) * (int)$limit);

        // SQL com alias no padrão Model__campo para o Cake hidratar certinho
        $sql = "
            SELECT 
                Beneficiario.id,
                Beneficiario.nome,
                Beneficiario.cpf,
                Beneficiario.cliente_id,

                Cliente.id,
                Cliente.nome,

                Agendamento.id,
                Agendamento.usuario_id,
                Agendamento.usuario_agendamento_id,
                Agendamento.data_hora,
                Agendamento.data_cadastro,
                Agendamento.descricao,
                Agendamento.status,

                Atendimento.id,
                Atendimento.beneficiario_id,

                Usuario.id,
                Usuario.nome,

                UsuarioAgendamento.id,
                UsuarioAgendamento.nome
            FROM agendamento Agendamento
            LEFT JOIN atendimento Atendimento 
                   ON Agendamento.atendimento_id = Atendimento.id
            LEFT JOIN beneficiario Beneficiario 
                   ON Atendimento.beneficiario_id = Beneficiario.id
            LEFT JOIN usuario Usuario 
                   ON Agendamento.usuario_id = Usuario.id
            LEFT JOIN usuario UsuarioAgendamento 
                   ON Agendamento.usuario_agendamento_id = UsuarioAgendamento.id
            LEFT JOIN cliente Cliente 
                   ON Beneficiario.cliente_id = Cliente.id
            {$where}
            {$orderSql}
            LIMIT " . (int)$limit . " OFFSET " . (int)$offset . "
        ";

        return $this->query($sql);
    }

    public function paginateCount($conditions = null, $recursive = 0, $extra = array())
    {
        $db = $this->getDataSource();

        $where = trim($db->conditions($conditions, true, true, $this));
        if ($where === '' || $where === null) {
            $where = '1 = 1';
        }

        $sql = "
            SELECT COUNT(*) AS count
            FROM agendamento Agendamento
            LEFT JOIN atendimento Atendimento 
                   ON Agendamento.atendimento_id = Atendimento.id
            LEFT JOIN beneficiario Beneficiario 
                   ON Atendimento.beneficiario_id = Beneficiario.id
            LEFT JOIN cliente Cliente 
                   ON Beneficiario.cliente_id = Cliente.id
            {$where}
        ";

        $res = $this->query($sql);
        return (int)$res[0][0]['count'];
    }
}
