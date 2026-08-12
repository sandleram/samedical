<?php

App::uses('AppShell', 'Console');

class ImportShell extends AppShell
{

    public $uses = ['ImportacaoNova'];
    public function main()
    {
        if (!isset($this->args[0])) {
            $this->out('ID NAO informado');
            return;
        }

        $id = isset($this->args[0]) ? $this->args[0] : null;

        $importacao = $this->ImportacaoNova->findById($id);
        if (!$importacao) {
            $this->out('Importacao NAO encontrada!');
            return;
        }
        # 0- pending, 1-proc+essing, 2-done, 3-done_whitch_error, 4-error

        $this->out('Importacao iniciada. ID: ' . $id);
        $this->ImportacaoNova->id = $id;
        $this->ImportacaoNova->save([
            'avisos' => 'Importação iniciada via Shell!',
            'status_processo' => 0,
            'linhas_processadas' => 0,
            'data_atualizacao' => date('Y-m-d H:i:s', time())
        ]);
    }


    private function _notificar($importacao)
    {

        App::uses('CakeEmail', 'Network/Email');

        $email = new CakeEmail();
        $email->to('sandleram@gmail.com')
            ->subject('Importação concluída')
            ->send('Seu arquivo foi processado com sucesso.');
    }
}



// class ImportacaoNovaShell extends AppShell
// {

//     public $uses = ['ImportacaoNova'];

//     public function main()
//     {


//         $id = isset($this->args[0]) ? $this->args[0] : null;
//         echo $id;
//         exit;
//         if (!$id) {
//             return;
//         }

//         $importacao = $this->ImportacaoNova->findById($id);
//         if (!$importacao) {
//             return;
//         }

//         $arquivo = $importacao['ImportacaoNova']['arquivo'];

//         $origem = WWW_ROOT . 'files/uploads/importacao_nova/aguardando/' . $arquivo;
//         $processando = WWW_ROOT . 'files/uploads/importacao_nova/processando/' . $arquivo;

//         rename($origem, $processando);

//         $this->ImportacaoNova->id = $id;
//         $this->ImportacaoNova->save([
//             'status' => 1,
//             'linhas_processadas' => 0
//         ]);

//         try {

//             App::import('Vendor', 'PhpSpreadsheet', [
//                 'file' => 'PhpSpreadsheet/autoload.php'
//             ]);

//             $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($processando);
//             $sheet = $spreadsheet->getActiveSheet();
//             $rows = $sheet->toArray();

//             $total = count($rows);
//             $this->ImportacaoNova->saveField('total_linhas', $total);



//             exit;
//             $count = 0;
//             foreach ($rows as $i => $row) {

//                 if ($i === 0) continue; // pula header

//                 // 👉 regra de negócio
//                 // $this->Model->save(...)

//                 $count++;
//                 $this->ImportacaoNova->saveField('linhas_processadas', $count);
//             }

//             rename(
//                 $processando,
//                 WWW_ROOT . 'files/uploads/importacao_nova/finalizado/' . $arquivo
//             );

//             $this->ImportacaoNova->saveField('status', 'finalizado');

//             $this->_notificar($importacao);
//         } catch (Exception $e) {

//             krumo('erro');
//             exit;
//             rename(
//                 $processando,
//                 WWW_ROOT . 'files/uploads/importacao_nova/erro/' . $arquivo
//             );

//             $this->ImportacaoNova->save([
//                 'status' => 'erro',
//                 'mensagem_erro' => $e->getMessage()
//             ]);
//         }
//     }

//     private function _notificar($importacao)
//     {

//         App::uses('CakeEmail', 'Network/Email');

//         $email = new CakeEmail();
//         $email->to('sandleram@gmail.com')
//             ->subject('Importação concluída')
//             ->send('Seu arquivo foi processado com sucesso.');
//     }
// }
