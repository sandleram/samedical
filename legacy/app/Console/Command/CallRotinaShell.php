<?php
class CallRotinaShell extends AppShell {
	
	
    public function main() {
        $this->autoRender = false;
		
		$toEmail = ['sandleram@gmail.com','sandler.matos@gmail.com'];
		$subject = 'SAMed - Relatório Teste';
		$msg = "Olá <b> teste<br><br>Enviado:".date('d/m/Y')." às ".date('H:i');

		
		if(!parent::envio_email($toEmail,$subject,$msg)){
			#BEGIN - CRIANDO LOG
			$this->loadModel('Log');
			$this->Log->create();
			$data_log = array('id' =>'',
							'log'=>'Erro - Envio Emial teste',
							'mensagem'            =>  $subject,
							'description'         =>  '',
							'server_description'  =>  '',
							'data_cadastro'       =>  date('Y/m/d H:i:s'),
							'usuario_id'          =>  '1'
					);
			$this->Log->save($data_log);
			#END - CRIANDO LOG
		}else{
		   #BEGIN - CRIANDO LOG
		   $this->loadModel('Log');
		   $this->Log->create();
		   $data_log = array('id' =>'',
						   'log'=>'Envio Emial teste',
						   'mensagem'            =>  $subject,
						   'description'         =>  '',
						   'server_description'  =>  '',
						   'data_cadastro'       =>  date('Y/m/d H:i:s'),
						   'usuario_id'          =>  '1'
						);
		   $this->Log->save($data_log);
		   #END - CRIANDO LOG
		}

		echo 1;
		exit;
    }
}