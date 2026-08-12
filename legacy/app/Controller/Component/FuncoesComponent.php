<?php

class FuncoesComponent extends Component {
//     public $Funcoes;

        var $palavrao = array(
        " cu ",
        " cú ",
        " porra ",
        " caralio ",
        " caralho ",
        " buceta ",
        " fuder ",
        " fodeu ",
        " puta ",
        " putinha ",
        " bicha ",
        " boiola ",
        " baitola ",
        " bichinha "

    );

    function __construct(ComponentCollection $collection, $settings = array())  {
        parent::__construct($collection, $settings);
//        $this->Funcoes = new FuncoesComponent(new ComponentCollection());
       

    }
    
    
    public function select_merge(array $array, $default = 'Selecione...') {
        $array_selecione = array('' => $default);
        return $array_selecione + $array;
        
        
    }

    public function delete_all_between($beginning, $end, $string) {
        $beginningPos = strpos($string, $beginning);
        $endPos = strpos($string, $end);
        if ($beginningPos === false || $endPos === false) {
            return $string;
        }

        $textToDelete = substr($string, $beginningPos, ($endPos + strlen($end)) - $beginningPos);

        return str_replace($textToDelete, '', $string);
    }
    
    /**
     * Utilizado para leitura de colunas de tabela (leitura mail i9jobs)
     * @param type $name_tag
     * @param type $string
     * @return type
     */
    public function return_between_tag($name_tag,$string){
        preg_match_all("#<".$name_tag."[^>]*>(.*?)</".$name_tag.">#is", $string, $return);
        return $return[1];
    }
    

    public function return_between($beginning, $end, $string) {
        $beginningPos = strpos($string, $beginning);
        $endPos = strpos($string, $end);
        if ($beginningPos === false || $endPos === false) {
            return $string;
        }
        $textToDelete = substr($string, $beginningPos, ($endPos + strlen($end)) - $beginningPos);
        
        $textToDelete = str_replace($beginning,'',$textToDelete);
        $textToDelete = str_replace($end,'',$textToDelete);
        return trim($textToDelete);
    }
    
    /**
     * Faz o tratamento para gravação do conteúdo da mensagem sem tags 
     * Exmeplo: (usuário erro login) $this->Funcoes->trata_msg_erro_log($this->Auth->loginError)
     * @param type $msg
     * @return string
     */
    public function trata_msg_erro_log($msg){
        $msg = $this->delete_all_between('<button','</i>',$msg);
        $msg1 = $this->return_between('<strong>','</strong>',$msg);
        $msg = $this->delete_all_between('<strong>','</strong>',$msg);
        $msg = $this->delete_all_between('<strong>','</strong>',$msg);
        $msg2Arr = $this->return_between_tag('div',$msg);
        $msg2 = @$msg2Arr[0];
        $msg = trim($msg1).' '.trim($msg2);
        return $msg;
    }

    public function clear_text($retirar, $string) {
        $string = str_replace($retirar, '', $string);
        $string = strip_tags($string, '<(.*?)>');
        $string = utf8_encode(trim($string));
        $string = $this->tirar_html_code($string);
        return $string;
    }

    public function clear_each($string) {
        $string = trim($string);
        $string = str_replace("\r", "", $string);
        $string = str_replace("\n", "", $string);
        $string = str_replace("\r\n", "", $string);
        $string = str_replace("\t", "", $string);
//        $string = str_replace(" ", "", $string);
        $string = preg_replace("/(<br.*?>)/i", "", $string);
        return trim($string);
    }

    public function tirar_acentos($dado) {
        // $dado = trim( str_replace( "\'", "", $dado) );
        // $dado = str_replace( "'", "", $dado );
        $dado = str_replace("–", "-", $dado);
        $dado = str_replace("ç", "c", $dado);
        $dado = str_replace("Ç", "C", $dado);
        $dado = ereg_replace("[áàâã]", "a", $dado);
        $dado = ereg_replace("[ÁÀÂÃ]", "A", $dado);
        $dado = ereg_replace("[éèê]", "e", $dado);
        $dado = ereg_replace("[ÉÈÊ]", "E", $dado);
        $dado = ereg_replace("[íìîï]", "i", $dado);
        $dado = ereg_replace("[ÍÌÎ]", "I", $dado);
        $dado = ereg_replace("[óòôõ]", "o", $dado);
        $dado = ereg_replace("[ÓÒÔÕ]", "O", $dado);
        $dado = ereg_replace("[úùû]", "u", $dado);
        $dado = ereg_replace("[ÚÙÛ]", "U", $dado);
        return $dado;
    }

    public function tirar_html_code($dado, $paraHtml = false) {
        if ($paraHtml) {
            $dado = str_replace("&", "&amp;", $dado);
            $dado = str_replace("Á", "&Aacute;", $dado);
            $dado = str_replace("á", "&aacute;", $dado);
            $dado = str_replace("Â", "&Acirc;", $dado);
            $dado = str_replace("â", "&acirc;", $dado);
            $dado = str_replace("À", "&Agrave;", $dado);
            $dado = str_replace("à", "&agrave;", $dado);
            $dado = str_replace("Å", "&Aring;", $dado);
            $dado = str_replace("å", "&aring;", $dado);
            $dado = str_replace("Ã", "&Atilde;", $dado);
            $dado = str_replace("ã", "&atilde;", $dado);
            $dado = str_replace("Ä", "&Auml;", $dado);
            $dado = str_replace("ä", "&auml;", $dado);
            $dado = str_replace("Æ", "&AElig;", $dado);
            $dado = str_replace("æ", "&aelig;", $dado);
            $dado = str_replace("É", "&Eacute;", $dado);
            $dado = str_replace("é", "&eacute;", $dado);
            $dado = str_replace("Ê", "&Ecirc;", $dado);
            $dado = str_replace("ê", "&ecirc;", $dado);
            $dado = str_replace("È", "&Egrave;", $dado);
            $dado = str_replace("è", "&egrave;", $dado);
            $dado = str_replace("Ë", "&Euml;", $dado);
            $dado = str_replace("ë", "&euml;", $dado);
            $dado = str_replace("Ð", "&ETH;", $dado);
            $dado = str_replace("ð", "&eth;", $dado);
            $dado = str_replace("Í", "&Iacute;", $dado);
            $dado = str_replace("í", "&iacute;", $dado);
            $dado = str_replace("Î", "&Icirc;", $dado);
            $dado = str_replace("î", "&icirc;", $dado);
            $dado = str_replace("Ì", "&Igrave;", $dado);
            $dado = str_replace("ì", "&igrave;", $dado);
            $dado = str_replace("Ï", "&Iuml;", $dado);
            $dado = str_replace("ï", "&iuml;", $dado);
            $dado = str_replace("Ó", "&Oacute;", $dado);
            $dado = str_replace("ó", "&oacute;", $dado);
            $dado = str_replace("Ô", "&Ocirc;", $dado);
            $dado = str_replace("ô", "&ocirc;", $dado);
            $dado = str_replace("Ò", "&Ograve;", $dado);
            $dado = str_replace("ò", "&ograve;", $dado);
            $dado = str_replace("Ø", "&Oslash;", $dado);
            $dado = str_replace("ø", "&oslash;", $dado);
            $dado = str_replace("Õ", "&Otilde;", $dado);
            $dado = str_replace("õ", "&otilde;", $dado);
            $dado = str_replace("Ö", "&Ouml;", $dado);
            $dado = str_replace("ö", "&ouml;", $dado);
            $dado = str_replace("Ú", "&Uacute;", $dado);
            $dado = str_replace("ú", "&uacute;", $dado);
            $dado = str_replace("Û", "&Ucirc;", $dado);
            $dado = str_replace("û", "&ucirc;", $dado);
            $dado = str_replace("Ù", "&Ugrave;", $dado);
            $dado = str_replace("ù", "&ugrave;", $dado);
            $dado = str_replace("Ü", "&Uuml;", $dado);
            $dado = str_replace("ü", "&uuml;", $dado);
            $dado = str_replace("Ç", "&Ccedil;", $dado);
            $dado = str_replace("ç", "&ccedil;", $dado);
            $dado = str_replace("Ñ", "&Ntilde;", $dado);
            $dado = str_replace("ñ", "&ntilde;", $dado);
            $dado = str_replace("<", "&lt;", $dado);
            $dado = str_replace(">", "&gt;", $dado);
            $dado = str_replace("®", "&reg;", $dado);
            $dado = str_replace("©", "&copy;", $dado);
            $dado = str_replace("Ý", "&Yacute;", $dado);
            $dado = str_replace("ý", "&yacute;", $dado);
            $dado = str_replace("Þ", "&THORN;", $dado);
            $dado = str_replace("þ", "&thorn;", $dado);
            $dado = str_replace("ß", "&szlig;", $dado);
            $dado = str_replace("/", "&jsonb;", $dado);
        } else {
            $dado = str_replace("&Aacute;", "Á", $dado);
            $dado = str_replace("&aacute;", "á", $dado);
            $dado = str_replace("&Acirc;", "Â", $dado);
            $dado = str_replace("&acirc;", "â", $dado);
            $dado = str_replace("&Agrave;", "À", $dado);
            $dado = str_replace("&agrave;", "à", $dado);
            $dado = str_replace("&Aring;", "Å", $dado);
            $dado = str_replace("&aring;", "å", $dado);
            $dado = str_replace("&Atilde;", "Ã", $dado);
            $dado = str_replace("&atilde;", "ã", $dado);
            $dado = str_replace("&Auml;", "Ä", $dado);
            $dado = str_replace("&auml;", "ä", $dado);
            $dado = str_replace("&AElig;", "Æ", $dado);
            $dado = str_replace("&aelig;", "æ", $dado);
            $dado = str_replace("&Eacute;", "É", $dado);
            $dado = str_replace("&eacute;", "é", $dado);
            $dado = str_replace("&Ecirc;", "Ê", $dado);
            $dado = str_replace("&ecirc;", "ê", $dado);
            $dado = str_replace("&Egrave;", "È", $dado);
            $dado = str_replace("&egrave;", "è", $dado);
            $dado = str_replace("&Euml;", "Ë", $dado);
            $dado = str_replace("&euml;", "ë", $dado);
            $dado = str_replace("&ETH;", "Ð", $dado);
            $dado = str_replace("&eth;", "ð", $dado);
            $dado = str_replace("&Iacute;", "Í", $dado);
            $dado = str_replace("&iacute;", "í", $dado);
            $dado = str_replace("&Icirc;", "Î", $dado);
            $dado = str_replace("&icirc;", "î", $dado);
            $dado = str_replace("&Igrave;", "Ì", $dado);
            $dado = str_replace("&igrave;", "ì", $dado);
            $dado = str_replace("&Iuml;", "Ï", $dado);
            $dado = str_replace("&iuml;", "ï", $dado);
            $dado = str_replace("&Oacute;", "Ó", $dado);
            $dado = str_replace("&oacute;", "ó", $dado);
            $dado = str_replace("&Ocirc;", "Ô", $dado);
            $dado = str_replace("&ocirc;", "ô", $dado);
            $dado = str_replace("&Ograve;", "Ò", $dado);
            $dado = str_replace("&ograve;", "ò", $dado);
            $dado = str_replace("&Oslash;", "Ø", $dado);
            $dado = str_replace("&oslash;", "ø", $dado);
            $dado = str_replace("&Otilde;", "Õ", $dado);
            $dado = str_replace("&otilde;", "õ", $dado);
            $dado = str_replace("&Ouml;", "Ö", $dado);
            $dado = str_replace("&ouml;", "ö", $dado);
            $dado = str_replace("&Uacute;", "Ú", $dado);
            $dado = str_replace("&uacute;", "ú", $dado);
            $dado = str_replace("&Ucirc;", "Û", $dado);
            $dado = str_replace("&ucirc;", "û", $dado);
            $dado = str_replace("&Ugrave;", "Ù", $dado);
            $dado = str_replace("&ugrave;", "ù", $dado);
            $dado = str_replace("&Uuml;", "Ü", $dado);
            $dado = str_replace("&uuml;", "ü", $dado);
            $dado = str_replace("&Ccedil;", "Ç", $dado);
            $dado = str_replace("&ccedil;", "ç", $dado);
            $dado = str_replace("&Ntilde;", "Ñ", $dado);
            $dado = str_replace("&ntilde;", "ñ", $dado);
            $dado = str_replace("&lt;", "<", $dado);
            $dado = str_replace("&gt;", ">", $dado);
            $dado = str_replace("&quot;", '"', $dado);
            $dado = str_replace("&reg;", "®", $dado);
            $dado = str_replace("&copy;", "©", $dado);
            $dado = str_replace("&Yacute;", "Ý", $dado);
            $dado = str_replace("&yacute;", "ý", $dado);
            $dado = str_replace("&THORN;", "Þ", $dado);
            $dado = str_replace("&thorn;", "þ", $dado);
            $dado = str_replace("&szlig;", "ß", $dado);
            $dado = str_replace("&jsonb;", "/", $dado);
            $dado = str_replace("&amp;", "&", $dado);
        }

        return $dado;
    }

    /**
     * BUSCA DA BASE DE DADOS ATRAVÉS DO TIPO E RETORNA UMA LISTA
     * @param type $tipo (sn,porte,faturamento,moeda,tipo)
     * @param type $exibir 
     * @return type
     * 
     * (modo normal)
     * $contatoRealizadoArr = $this->Funcoes->parametros('Contato_Realizado','list',NULL,true,'Contato Realizado...');
     * (modo array)
        $contatoRealizadoArr = $this->Funcoes->parametros('Contato_Realizado',array( 'exibir'=>'list',
                                                                                     'fields'=>NULL,
                                                                                     'selecione'=>true,
                                                                                     'defaultSelect'=>'Selecione...'));
        );
     */
    public function parametros($tipo = '', $exibir = 'list' , $fields = NULL, $selecione = true, $defaultSelect = 'Selecione...',$potencial = true) {
//        $this->loadModel('Parameter');
        static $parameter;
        
        
        
        
        #DESENVOLVER - MODIFICAR PARA DEIXAR SOMENTE COMO ARRAY
        if(is_array($exibir)){
            $option = $exibir;
            $exibir = (isset($option['exibir']))? $option['exibir'] : 'list' ;
            $fields = (isset($option['fields']))? $option['fields'] : NULL ;
            $selecione = (isset($option['selecione']))? $option['selecione'] : true ;
            $defaultSelect = (isset($option['defaultSelect']))? $option['defaultSelect'] : 'Selecione...' ;
        }
        $potencial = (isset($option['potencial']) && $option['potencial'] == false)? array('Parametro.nome <> "Potencial"') : array() ;
        
        
        
        if(is_null($parameter)){
            $parameter = ClassRegistry::init('Parametro');
        }
       
        if ($tipo != '') {
            
            $fields_default = array('Parametro.valor','Parametro.nome');
            if(is_array($fields)){
                $fields_default = $fields;
            }
            
            $conditions = array('conditions' => array_merge($potencial,array('Parametro.tipo' => $tipo,'Parametro.status'=>'1')),
                'fields' => $fields_default,
                'order' => array('Parametro.ordenacao' => 'ASC'),
                'recursive' => -1
            );
            
        } else {
            $conditions = array('conditions' => array_merge($potencial,array('Parametro.status'=>'1')), 'fields' => array('Parametro.nome', 'Parametro.valor'),
                'order' => array('Parametro.id' => 'ASC'),
                'recursive' => -1
            );
        }
        
        $parametros = $parameter->find($exibir, $conditions);
        
        if ($exibir == 'list' && $selecione == true):
            $parametros = $this->select_merge($parametros, $defaultSelect);
        endif;
        
        return $parametros;
    }

    /**
     * UPLOAD DE IMAGENS DE MODO PERSONALIZADO DE ACORDO COM A CONTROLLER
     * @param type $check
     * @return boolean
     * @example $upload = $this->Funcoes->uploadImage($this->request->data[$TABLE]['arquivo_imagem'],$this->params['controller'],$this->params['action'],true);
     * http://blog.the-nerd.be/2013/04/cakephp-file-upload-validation/#sthash.sTJSZf2C.dpuf
     * #http://angelitomg.com/blog/manipulando-imagens-com-cakephp/
     */
    public function uploadImage($image, $controller, $action, $force_size = false, $options = array()) {
        #SE EXISTE IMAGEM
        if ($image['size'] == 0 || $image['error'] !== 0) {
            return false;
        }
        
       
        #PERMISSÃO DE TIPO DE IAMGEM
        $permitted = array('image/jpeg', 'image/gif', 'image/png');
        if(!in_array($image['type'],$permitted)){return false;}
            
        #EXTESÃO DO ARQUIVO
        $exArr = explode('.',$image['name']);
        $ext = $exArr[count($exArr)-1];
        
        #MONTA CAMINHO E NOME DO ARQUIVO
        $uploadFolder = 'img'. DS .'uploads' . DS . $controller;
        $filename = str_replace('.'.$ext, '', $image['name']);
        $filename = $this->normalizaeUrl($filename).'_'.time();
        
        
        #CRIA AS PASTAS 
        if (!file_exists($uploadFolder)) {
            mkdir($uploadFolder, 0777, true);
        }
        
        if (!file_exists($uploadFolder. DS .'thumb')) {
            mkdir($uploadFolder. DS .'thumb', 0777, true);
        }
        
        if (!file_exists($uploadFolder. DS .'thumb_sidebar')) {
            mkdir($uploadFolder. DS .'thumb_sidebar', 0777, true);
        }

        if (!file_exists($uploadFolder. DS .'mini')) {
            mkdir($uploadFolder. DS .'mini', 0777, true);
        }
        
        #REDIMENCIONA AS IMAGENS
        $type = str_replace('image/', 'imagecreatefrom', $image['type']);
        #é especificado a action para a definição de tamanho padrao para páginas, e o else serve como galeria de imagens!
        
        #GALERIA DE IMAGENS
        if($action == 'admin_upload_image'){
            if($controller == 'destino'){
                $img_original   = $this->resizeImage($image['tmp_name'],716,496,$type,TRUE);
                $thumb          = $this->resizeImage($image['tmp_name'],128,84,$type,TRUE);
                $thumbmini      = $this->resizeImage($image['tmp_name'],105,105,$type,TRUE);
            }else{
                $img_original   = $this->resizeImage($image['tmp_name'],655,440,$type,TRUE);
                $thumb          = $this->resizeImage($image['tmp_name'],128,84,$type,TRUE);
                $thumbmini      = $this->resizeImage($image['tmp_name'],55,55,$type,TRUE);
            }
        #TUDO QUE NÃO É GALERIA DE IMAGENS    
        }else{
            $width = '700';
            if(isset($options['width']) && is_numeric($options['width']) && $force_size == true){
                $width = $options['width'];
           
            }
            
            $height = '450';
            if(isset($options['height']) && is_numeric($options['height']) && $force_size == true){
                $height = $options['height'];
           
            }
            
            if($controller == 'usuario'){
                $img_original   = $this->resizeImage($image['tmp_name'],150,150,$type,TRUE);
                $thumbmini      = $this->resizeImage($image['tmp_name'],55,55,$type,TRUE);
            }else{
                $img_original   = $this->resizeImage($image['tmp_name'],$width,$height,$type,$force_size);
                $thumb          = $this->resizeImage($image['tmp_name'],140,140,$type,$force_size);
                $thumbmini      = $this->resizeImage($image['tmp_name'],55,55,$type,TRUE);
            }
        }
        
//        exit();
        
        #CRIA AS IMAGENS DE ACORDO COM A EXTESÃO
        unset($type);
        $type = str_replace('/', '', $image['type']);
        $type($img_original,  $uploadFolder . DS .$filename.'.'.strtolower($ext));
        if(isset($thumb)){ $type($thumb,  $uploadFolder . DS .'thumb'. DS . $filename.'.'.strtolower($ext));}
        if(isset($thumb_sidebar)){ $type($thumb_sidebar,  $uploadFolder . DS .'thumb_sidebar'. DS . $filename.'.'.strtolower($ext));}
        $type($thumbmini,  $uploadFolder . DS .'mini'. DS . $filename.'.'.strtolower($ext));
        
#SUBSTITUIDO PELO CÓDIGO DE CIMA        
//        if($image['type'] == 'image/jpeg'){
//            imagejpeg($img_original,  $uploadFolder . DS .$filename.'.'.$ext);
//            imagejpeg($thumb,  $uploadFolder . DS .'thumb'. DS . $filename.'.'.$ext);
//            imagejpeg($thumbmini,  $uploadFolder . DS .'mini'. DS . $filename.'.'.$ext);
//        }elseif($image['type'] == 'image/gif'){
//            imagegif($img_original,  $uploadFolder . DS .$filename.'.'.$ext);
//            imagegif($thumb,  $uploadFolder . DS .'thumb'. DS . $filename.'.'.$ext);
//            imagegif($thumbmini,  $uploadFolder . DS .'mini'. DS . $filename.'.'.$ext);
//        }elseif($image['type'] == 'image/png'){
//            imagepng($img_original,  $uploadFolder . DS .$filename.'.'.$ext);
//            imagepng($thumb,  $uploadFolder . DS .'thumb'. DS .$filename.'.'.$ext);
//            imagepng($thumbmini,  $uploadFolder . DS .'mini'. DS . $filename.'.'.$ext);
//        }
        
        return $filename.'.'.strtolower($ext);

    }
    
    
    public function deleteImage($image, $caminho){
        
        $deleteFolder = 'img'. DS .'uploads' . DS . $caminho;
        
        $caminho = $deleteFolder . DS . $image;
        $caminhomini = $deleteFolder . DS . 'mini' . DS . $image;
        $caminhothumb = $deleteFolder . DS . 'thumb' . DS . $image;
        $caminhothumbside = $deleteFolder . DS . 'thumb_sidebar' . DS . $image;
        
        if (file_exists($caminho)){unlink($caminho);}
        if (file_exists($caminhomini)){unlink($caminhomini);}
        if (file_exists($caminhothumb)){unlink($caminhothumb);}
        if (file_exists($caminhothumbside)){unlink($caminhothumbside);}
        return true;
    }
    
    public function deleteFile($file, $caminho){
        $deleteFolder = 'files'. DS .'uploads' . DS . $caminho;
        $caminho = $deleteFolder . DS . $file;
        if (file_exists($caminho)){unlink($caminho);}
        return true;
    }
    
    
    /**
     * DETERMINA O TAMANHO IDEAL PARA IMAGEM E GERA O TEMP DA MESMA
     * @param type $filename
     * @param type $newwidth
     * @param type $newheight
     * @return type
     */
    public function resizeImage($filename, $newwidth, $newheight,$type,$force_size = false){
        list($width, $height) = getimagesize($filename);
        if($force_size === false){
            if($width > $height && $newheight < $height){
                $newheight = $height / ($width / $newwidth);
            } else if ($width < $height && $newwidth < $width) {
                $newwidth = $width / ($height / $newheight);     
            } else {
                $newwidth = $width;
                $newheight = $height;
            }
        }
        $thumb = imagecreatetruecolor($newwidth, $newheight);

      
        if($type == 'imagecreatefrompng'){
            imagealphablending($thumb, false);
            imagesavealpha($thumb,true);
            $transparent = imagecolorallocatealpha($thumb, 255, 255, 255, 127);
            imagefilledrectangle($thumb, 0, 0, $newwidth, $newheight, $transparent);
            $source = $type($filename);
            imagecopyresampled($thumb, $source, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);
        }else{
            $source = $type($filename); #imagecreatefromjpeg($filename);
            imagecopyresized($thumb, $source, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);
        }
        
        
        return $thumb;
    }
    
    /**
     * ESTRUTURA PARA MONTAR URL 
     * @param type $str
     * @return type
     */
    public function normalizaeUrl($str){
        $str = strtolower(utf8_decode($str)); $i=1;
        $str = strtr($str, utf8_decode('àáâãäåæçèéêëìíîïñòóôõöøùúûýýÿ'), 'aaaaaaaceeeeiiiinoooooouuuyyy');
        $str = preg_replace("/([^a-z0-9])/",'-',utf8_encode($str));
        while($i>0) $str = str_replace('--','-',$str,$i);
        if (substr($str, -1) == '-') $str = substr($str, 0, -1);
        return $str;
    }
    
   
    public function slug($str){
        $str  = $this->normalizaeUrl($str);
        return $str;
    }
    
    /**
     * PASSAGEM DE INFORMAÇÃO DO FORMATO MOEDA PARA BANCO
     * @param type $value
     * @return type
     */
    public function moedaToDb($value){
        if(preg_match('/./', $value) && preg_match('/,/', $value)){
            $value = str_replace('.', '', $value);
            $value = str_replace(',', '.', $value);
        }
        return $value;
    }
    
    /**
     * RETORNO DO BANCO DO TIPO MOEDA PARA VIEW DO TIPO pt-Br R$
     * @param type $value
     * @return type
     */
    public function dbToMoeda($value){
//        $value = str_replace('.', ',', $value);
        $value = number_format($value, 2, ',', '.');
        return $value;
    }
    
    
    
    /**
     * 
     * @param array $opcoes 
     * @param type $comhora
     * @return type
     * @example $opções pode usar (dia)(mes)(ano)(hora)(minuto)(segundo) ou +1 ou -1 podendo adicionar ou remover
     *          Exemplo  : $this->Funcoes->dataAtual(); data atual
     *          Exemplo 1: $this->Funcoes->dataAtual(array(), false); data atual sem hora
     *          Exemplo 2: $this->Funcoes->dataAtual(array(), true); data atual com  hora
     *          Exemplo 3: $this->Funcoes->dataAtual(array('dias'=>+20), true);
     *          Exemplo 4: $this->Funcoes->dataAtual(array('mes'=>-1), true);
     *          Exemplo 5: $this->Funcoes->dataAtual(array('dia'=>+15,'mes'=>+3), true);
     */
    public function dataAtual(array $opcoes = array(), $formato = 'Y-m-d', $com_hora = false){
        $dia = date('d');
        if(isset($opcoes['dia']) && $opcoes['dia'] !== NULL && is_numeric($opcoes['dia'])){
            $dia = date('d')+$opcoes['dia'];
        }
        
        $mes = date('m');
        if(isset($opcoes['mes']) && $opcoes['mes'] !== NULL && is_numeric($opcoes['mes'])){
            $mes = date('m')+$opcoes['mes'];
        }
        
        $ano = date('Y');
        if(isset($opcoes['ano']) && $opcoes['ano'] !== NULL && is_numeric($opcoes['ano'])){
            $ano = date('Y')+$opcoes['ano'];
        }
        
        $hora = date('H');
        if(isset($opcoes['hora']) && $opcoes['hora'] !== NULL && is_numeric($opcoes['hora'])){
            $hora = date('H')+$opcoes['hora'];
        }
        
        $minuto = date('i');
        if(isset($opcoes['minuto']) && $opcoes['minuto'] !== NULL && is_numeric($opcoes['minuto'])){
            $minuto = date('i')+$opcoes['minuto'];
        }
        
        $segundo = date('s');
        if(isset($opcoes['segundo']) && $opcoes['segundo'] !== NULL && is_numeric($opcoes['segundo'])){
            $segundo = date('s')+$opcoes['segundo'];
        }
        
        $mkDate = mktime($hora, $minuto, $segundo, $mes, $dia, $ano);
        return ($com_hora == true) ? date($formato.' H:i:s', $mkDate) : date($formato, $mkDate) ;
    }
    
    
    
    
    /**
     * Serve para retirar o nome de subnível quando buscado.
     * @param array $array
     * @param type $model
     * @return array
     * @example $this->Funcoes->retiraSubArray($menuArr,'Pagina');
     * DE:  ... (Array, 8 elements)
                    0 (Array, 1 element)
                        Pagina (Array, 3 elements)
                                titulo (String, 7 characters ) Resorts
                                controller (String, 7 characters ) resorts
                                menu (String, 1 characters ) 1
     * PARA:... (Array, 8 elements)
                    0 (Array, 3 elements)
                        titulo (String, 7 characters ) Resorts
                        controller (String, 7 characters ) resorts
                        menu (String, 1 characters ) 1

     */
    public function retiraSubArray(array $array, $model, $type = 'all'){
        $arrayNew = array();
        if(count($array)>0):
            if($type == 'first'):
                $arrayNew = $array[$model];
            else:
                foreach($array as $kArray => $vArray):
                    $arrayNew[$kArray] = $vArray[$model];
                endforeach;
            endif;
        endif;
        return $arrayNew;
    }
    
    
    
    public function checkExternalFile($url) {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_NOBODY, true);
        curl_exec($ch);
        $retCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        return $retCode;
    }
    
    
    
    public function dateToDb($data, $comhora = false) {
        $dataNew = '';
        if(preg_match('/-/',$data)){
            return $data;
        }
        
        if ($data != '') {
            $dataHoraArr = explode(' ', $data);
            $dataArr = explode('/', $dataHoraArr[0]);
            $dataNew = $dataArr[2] . '-' . $dataArr[1] . '-' . $dataArr[0];
            if ($comhora != false && isset($dataHoraArr[1])) {
                $dataNew .= ' ' . $dataHoraArr[1];
            }
        }
        return $dataNew;
    }
    
    public function dateToView($data, $comhora = false){
        $dataNew = '';
        if($data != ''){
            $dataHoraArr = explode(' ',$data);
            $dataArr = explode('-',$dataHoraArr[0]);

            $dataNew = $dataArr[2]. '/' . $dataArr[1]. '/' .$dataArr[0];

            if($comhora != false){
                $dataNew .= ' '.$dataHoraArr[1];
            }
        }
        return $dataNew;
    }
    
    
    public function reevoo($type = 'geral'){
        $reevoo = array('respostas'=>'','porcentagem'=>'');
        $link = 'http://mark.reevoo.com/reevoomark/customer_experience_scores/LTV.json';
        if($type == 'geral'){
            
            if($this->checkExternalFile($link) == 200){
                $ReevooJson = "http://mark.reevoo.com/reevoomark/customer_experience_scores/LTV.json";
                $returnString = file_get_contents($ReevooJson);
                $obj       = json_decode($returnString, true);

                if(count($obj['customer_experience_scores'] > 0)){
                    $reevoo = array('respostas'=>$obj['customer_experience_scores']['overall_respondents'],
                                    'porcentagem'=>$obj['customer_experience_scores']['overall']);

                }
            }
        }
        
        return $reevoo;
        
    }
    
    /**
     * Quebra o texto para buscar mais de um ítem
     * @param type $str
     * @param type $retorno
     * @param type $separador
     * @return type
     */
    public function text_busca($str,$return_IN = true, $separador = ','){
        $str = str_replace('-','',$str);
        $str = str_replace('  ',' ',$str);
        $str = str_replace('(','',$str);
        $str = str_replace(')','',$str);
        
        $rs = explode(' ',$str);
        if($return_IN){
            $rs = implode($separador,$rs);
        }
        
        return $rs;
    }
    
    
    /**
     * GERA NOME MODEL ATRAVÉS DA CONTROLLER
     * @param type $controller
     * @return type
     */
    public function controller_to_model($controller){
        $control_verify = $controller;
        $control_verify = str_replace('_', ' ', $control_verify);
        $control_verify = ucwords($control_verify);
        $control_verify = str_replace(' ', '', $control_verify);
        return $control_verify;
    }
    
    
    
    
    function word_limiter($str, $limit = 20, $end_char = '&#8230;')
    {
        if (trim($str) == '')
        {
            return $str;
        }

        preg_match('/^\s*+(?:\S++\s*+){1,'.(int) $limit.'}/', $str, $matches);

        if (strlen($str) == strlen($matches[0]))
        {
            $end_char = '';
        }

        return rtrim($matches[0]).$end_char;
    }
    
    
    function character_limiter($str, $n = 500, $end_char = '&#8230;')
    {
        if (strlen($str) < $n)
        {
            return $str;
        }

        $str = preg_replace("/\s+/", ' ', str_replace(array("\r\n", "\r", "\n"), ' ', $str));

        if (strlen($str) <= $n)
        {
            return $str;
        }

        $out = "";
        foreach (explode(' ', trim($str)) as $val)
        {
            $out .= $val.' ';

            if (strlen($out) >= $n)
            {
                $out = trim($out);
                return (strlen($out) == strlen($str)) ? $out : $out.$end_char;
            }       
        }
    }
    
    
    function limpa_textarea($str){
        $str = strip_tags($str);
        $str = str_replace('&nbsp;', '', $str);
        $str = trim($str);
        
        return $str;
    }
    
    
//    public function data_log($model){
//        return $this->$model->getDataSource()->getLog(false, false);
//    }
//    
    
    
    
    
    
    
    
        
    
    
    /**
     * UTILIZADO PARA RETIRAR UM VINCULO ENTRE MODELS VIA unbindModel
     * @example $this->Funcoes->unbind($this->Destino,'DestinoConteudo','hasMany');
     * @param type $model 
     * @param type $unbindModel
     * @param type $type
     * @link http://blog.desenvolvedorsa.com/desvincular-foreignkey-fk-model-cakephp-unbindmodel/ Descrição de Utilização
     * 
     */
//    public function unbind($model, $unbindModel, $type = 'hasMany'){
//        $model->unbindModel(array($type => array($unbindModel)));
//    }
    
    
    

//    public function checkPermission($controller,$action,$permissions){
//        $controller = ucfirst($controller);
//        $control_allowed = array('Home');
//        $frase = 'Seu Usuário Não tem Permissão de Acesso';
//        if( !in_array($controller, $control_allowed)){# preg_match('/^admin_/',trim($action))&&
//            if(!isset($permissions[$controller])){
//                return $frase.'!!';
//            }elseif($permissions[$controller]['permissao'] == 0){
//                return $frase." para a área ({$permissions[$controller]['nome']})";
//            }
//        }
//        return true;
//    }
    
    
    

//    function ConvertData($Data) {
//        $D = explode("/", $Data);
//        $Data = $D[2] . '/' . $D[1] . '/' . $D[0];
//        return $Data;
//    }
//$url_full = Router::url($url);
//$urlArr = explode('/', $url_full);
//$urlRootArr = array();
//foreach ($urlArr as $value):
//    if ($value == 'admin') {
//        break;
//    }
//    $urlRootArr[] = $value;
//endforeach;
//$urlRoot = implode('/', $urlRootArr);

    /**
     * uploads files to the server
     * @params:
     *      $folder     = the folder to upload the files e.g. 'img/files'
     *      $formdata   = the array containing the form files
     *      $itemId     = id of the item (optional) will create a new sub folder
     * @return:
     *      will return an array with the success of each file upload
     * $fileOK = $this->Funcoes->uploadFiles('upload/cv', $this->request->data['Candidato']['cv_file']);
     */
//    function uploadFiles($folder, $formdata, $itemId = null) {
//        
//        
//        krumo($formdata);
//        // setup dir names absolute and relative
//        $folder_url = WWW_ROOT . $folder;
//        $rel_url = $folder;
//        
//        krumo($folder_url);
//
//        // create the folder if it does not exist
//        if (!is_dir($folder_url)) {
//            mkdir($folder_url);
//        }
//
//        // if itemId is set create an item folder
//        if ($itemId) {
//            // set new absolute folder
//            $folder_url = WWW_ROOT . $folder . '/' . $itemId;
//            // set new relative folder
//            $rel_url = $folder . '/' . $itemId;
//            // create directory
//            if (!is_dir($folder_url)) {
//                mkdir($folder_url);
//            }
//        }
//
//        // list of permitted file types, this is only images but documents can be added
//        $permitted = array('image/gif', 'image/jpeg', 'image/pjpeg', 'image/png');
//
//        // loop through and deal with the files
//        foreach ($formdata as $file) {
//            // replace spaces with underscores
//            $filename = str_replace(' ', '_', $file['name']);
//            // assume filetype is false
//            $typeOK = false;
//            // check filetype is ok
//            foreach ($permitted as $type) {
//                if ($type == $file['type']) {
//                    $typeOK = true;
//                    break;
//                }
//            }
//
//            // if file type ok upload the file
//            if ($typeOK) {
//                // switch based on error code
//                switch ($file['error']) {
//                    case 0:
//                        // check filename already exists
//                        if (!file_exists($folder_url . '/' . $filename)) {
//                            // create full filename
//                            $full_url = $folder_url . '/' . $filename;
//                            $url = $rel_url . '/' . $filename;
//                            // upload the file
//                            $success = move_uploaded_file($file['tmp_name'], $url);
//                        } else {
//                            // create unique filename and upload file
//                            ini_set('date.timezone', 'Europe/London');
//                            $now = date('Y-m-d-His');
//                            $full_url = $folder_url . '/' . $now . $filename;
//                            $url = $rel_url . '/' . $now . $filename;
//                            $success = move_uploaded_file($file['tmp_name'], $url);
//                        }
//                        // if upload was successful
//                        if ($success) {
//                            // save the url of the file
//                            $result['urls'][] = $url;
//                        } else {
//                            $result['errors'][] = "Error uploaded $filename. Please try again.";
//                        }
//                        break;
//                    case 3:
//                        // an error occured
//                        $result['errors'][] = "Error uploading $filename. Please try again.";
//                        break;
//                    default:
//                        // an error occured
//                        $result['errors'][] = "System error uploading $filename. Contact webmaster.";
//                        break;
//                }
//            } elseif ($file['error'] == 4) {
//                // no file was selected for upload
//                $result['nofiles'][] = "No file Selected";
//            } else {
//                // unacceptable file type
//                $result['errors'][] = "$filename cannot be uploaded. Acceptable file types: gif, jpg, png.";
//            }
//        }
//        return $result;
//    }
//
    


    
    #VS FUNCTION
    public function busca_empresas($cod_conta){
        $Empresa = ClassRegistry::init('Empresa');
        $sql = "SELECT Empresa.cod_empresa,
                        UPPER(CONCAT(Empresa.nome,
                                        ' (',
                                        Municipio.nome,
                                        ')',
                                        IF(Empresa.status = 1,
                                            ' - (ATIVO)',
                                            ' - (INATIVO)'))) AS nome
                    FROM empresa Empresa
                            LEFT JOIN endereco Endereco
                                ON Empresa.cod_endereco = Endereco.cod_endereco
                            LEFT JOIN municipio Municipio 
                                ON Endereco.cod_municipio = Municipio.cod_municipio
                    WHERE Empresa.cod_conta = {$cod_conta}
                    ORDER BY Empresa.nome";
        $rows = $Empresa->query($sql);
        $empresaArr = array();
        if (count($rows) > 0) {
            foreach ($rows as $row) {
                $empresaArr[$row['Empresa']['cod_empresa']] = $row[0]['nome'];
            }
        }
        
        return $empresaArr;
    }
    
    
#VS FUNCTIONS FULLL TRATA.CLASS

/*****************************************************************************
FUNÇÕES PARA NÚMEROS
*****************************************************************************/

    function trata_numero($variavel){
        if($variavel!=""){
            $variavel=str_replace(".","",$variavel);
            $variavel=str_replace(",",".",$variavel);
        }
        return $variavel;

    }

    function verNumero($numero){

        if(is_numeric($numero)){

            return $numero;

        }else{

            return false;

        }

    }

    function retira_zero($string_original,$caracter="0")
    {
        $check = false;

        $array_string = str_split($string_original);

        /*echo "original: ".$string_original."<br>";*/
        for ($i =0 ; $i < sizeof($array_string) && !$check ; $i++)
        {
            /*echo "i: $i<br>";
            echo "valor: ".$array_string[$i]."<br>";*/
            if ($array_string[$i] != $caracter)
            {
                $confirmacao = $i;
                $check = true;
            }
        }

        /*echo "valor encontrado: ".$confirmacao."<br>";
        echo substr($string_original,$confirmacao)."<br>";*/
        return (substr($string_original,$confirmacao));
    }

    function arredonda($numero,$casas,$separador){
        $true=0;
        @list($numero_inteiro,$decimal) = explode($separador,strval($numero));
        $decimal_str=substr($decimal,0,$casas);
        $determina=substr($decimal,$casas,1);
        if (substr($decimal,0,1)=="9"){
            $len=strlen($decimal_str);
            for ($i=0;$i<$len;$i++){
                if (substr($decimal_str,$i,1)=="9"){$true++;}
            }
            if ($true==$len){$numero_inteiro++;return $numero_inteiro;}
        }
        if ($determina>=5){$decimal_str++;}
              $denom=pow(10,strlen($decimal_str));
        $decimal_ajustado = $decimal_str/$denom;
        return($numero_inteiro+$decimal_ajustado);
    }


    function numero_banco($string, $casas_decimais=2, $separador=",", $limpar=false, $separador_milhar=".")
    {
        $formatado = number_format($string, $casas_decimais, $separador,$separador_milhar);

        if($limpar===true){


            for($i=strlen($formatado);$i<=strlen($formatado);$i--){

                 if(substr($formatado,($i-1),1)==$separador)
                 {
                    $formatado .= "00";
                    break;
                 }
                 else
                 {
                    if((substr($formatado,($i-1),1)=="0")){

                        $formatado = substr($formatado,0,-1);

                    }else{

                        break;

                    }
                 }

            }

        }

        return $formatado;

    }


    function valida_telefone($ddd,$telefone){

        $ddd_ereg="[0-9]{2}$";
        $telefone_ereg="[0-9]{6,8}";

        if(!ereg($ddd_ereg, $ddd)){

            return false;

        }else{

            if(!ereg($telefone_ereg, $telefone)){

                return false;

            }

        }

        return true;

    }

    public function monta_cnpj ( $valor )
    {
        $dados = $valor;

        //29.259.484/0001-70
        return ( substr($dados,0,2).".".substr($dados,2,3).".".substr($dados,5,3)."/".substr($dados,8,4)."-".substr($dados,12,2) );
    }

    public function monta_cpf( $valor )
    {

        if($this->valida_cpf($valor)){

            $dados = sprintf("%011s", $valor);
            return ( substr($dados,0,3).".".substr($dados,3,3).".".substr($dados,6,3)."-".substr($dados,9,2) );

        }else{

            return "";

        }
    }

    public function validarCPF( $valor )
    {

        if($this->valida_cpf($valor)){

            $dados = sprintf("%011s", $valor);
            return ( substr($dados,0,3).".".substr($dados,3,3).".".substr($dados,6,3)."-".substr($dados,9,2) );

        }else{

            return "";

        }
    }


    public function monta_pis( $valor )
    {
        //###.#####.##-#
        //###########

        $dados = sprintf("%011s", $valor);

        return ( substr($dados,0,3).".".substr($dados,3,5).".".substr($dados,8,2)."-".substr($dados,10,1) );
    }

    public function monta_cep( $valor )
    {
        //###.#####.##-#
        //###########

        if($this->valida_cep($valor)){

            $dados = sprintf("%08s", $valor);
            return ( substr($dados,0,5)."-".substr($dados,5,3));

        }else{

            return "";

        }

    }

        /**
         * Método que formata o valor da célula em formato monetário.
         * É necessário a movimentação do simbolo de R$ por incompatibilidade
         * em alguns servidores.
         *
         * @param string $valor
         */

        public function formatoMoeda($valor){

            $valor = money_format("%n",$valor);
            $valor = str_replace(" ","",$valor);
            $valor = str_replace("R$","",$valor);
            return "R$ ". $valor;

        }

        /**
        * Retira qualquer caracter que não seja número da String
        *
        * @param $numero
        * @return string
        */
        public function limparNumero($numero)
        {
            return preg_replace('/\D+/',"", $numero);
        }


/*****************************************************************************
FUNÇÕES PARA DATAS
*****************************************************************************/


    function data_banco($string){

        if($string!="0000-00-00" && $string!=""){

            if(strlen($string)>10){

                if($string!="0000-00-00 00:00:00"){

                    $separa = explode(" ",$string);
                    $dia = explode("-",$separa[0]);
                    $hora = explode(":",$separa[1]);
                    return array($dia[0],$dia[1],$dia[2],$hora[0],$hora[1]);

                }else{

                    return array("","","","","");

                }

            }else{

                $data=explode("-",$string);
                return $data;

            }

        }else{
            return array("","","");
        }

    }




    function formDataBanco($array,$mk=""){

        switch(count($array)){

            case 2:
                return $array[2] .":". $array[1] .":00";
                break;

            case 3:
                return $array[2] ."-". $array[1] ."-". $array[0];
                break;

            case 5:

                if($mk==""){

                    return $array[2] ."-". $array[1] ."-". $array[0] ." ". $array[3] .":". $array[4] .":00";
                }else{

                    return date($mk,mktime($array[3],$array[4],0,$array[1],$array[0],$array[2]));

                }

                break;
            default:
                return "";
                break;

        }


    }


    function dataArray($string){

        if($string!=""){

            if(strlen($string)>10){

                $separa = explode(" ",$string);
                $dia = explode("-",$separa[0]);
                $hora = explode(":",$separa[1]);
                return array($dia[0],$dia[1],$dia[2],$hora[0],$hora[1]);

            }else{

                $data=explode("/",$string);
                $data[0] = $this->preenche($data[0],"E",2);
                $data[1] = $this->preenche($data[1],"E",2);
                $data[2] = $this->preenche($data[2],"D",4);
                return $data;

            }

        }else{
            return array("","","");
        }

    }




    function mes_port($mes="",$html=true, $encode=false ){

        $mes = $mes != "" && strlen($mes)==2 ? $mes : date("m");

        $escrito = "";

        switch($mes){

            case "01":
                $escrito = "Janeiro";
                break;
            case "02":
                $escrito = "Fevereiro";
                break;
            case "03":
                $escrito = "Março";
                break;
            case "04":
                $escrito = "Abril";
                break;
            case "05":
                $escrito = "Maio";
                break;
            case "06":
                $escrito = "Junho";
                break;
            case "07":
                $escrito = "Julho";
                break;
            case "08":
                $escrito = "Agosto";
                break;
            case "09":
                $escrito = "Setembro";
                break;
            case "10":
                $escrito = "Outubro";
                break;
            case "11":
                $escrito = "Novembro";
                break;
            case "12":
                $escrito = "Dezembro";
                break;

        }

        if(!$encode){
            if($html===true){

                return $escrito;

            }else{

                return html_entity_decode($escrito);

            }
        }else{

            return utf8_encode($escrito);

        }

    }

    function mes_port_abr($mes="",$html=true){

        $mes = $mes != "" && strlen($mes)==2 ? $mes : date("m");

        $escrito = "";

        switch($mes){

            case "01":
                $escrito = "Jan";
                break;
            case "02":
                $escrito = "Fev";
                break;
            case "03":
                $escrito = "Mar";
                break;
            case "04":
                $escrito = "Abr";
                break;
            case "05":
                $escrito = "Mai";
                break;
            case "06":
                $escrito = "Jun";
                break;
            case "07":
                $escrito = "Jul";
                break;
            case "08":
                $escrito = "Ago";
                break;
            case "09":
                $escrito = "Set";
                break;
            case "10":
                $escrito = "Out";
                break;
            case "11":
                $escrito = "Nov";
                break;
            case "12":
                $escrito = "Dez";
                break;

        }

        if($html===true){

            return $escrito;

        }else{

            return html_entity_decode($escrito);

        }

    }



    function semana_port($sem,$html=true){

        $escrito = "";

        switch($sem){

            case "0":
                $escrito = "Domingo";
                break;
            case "1":
                $escrito = "Segunda-Feira";
                break;
            case "2":
                $escrito = "Ter&ccedil;a-Feira";
                break;
            case "3":
                $escrito = "Quarta-Feira";
                break;
            case "4":
                $escrito = "Quinta-Feira";
                break;
            case "5":
                $escrito = "Sexta-Feira";
                break;
            case "6":
                $escrito = "S&aacute;bado";
                break;

        }

        if($html===true){

            return $escrito;

        }else{

            return html_entity_decode($escrito);

        }

    }

    /*function calcula_idade($data_nascimento){
        $today = mktime(0,0,0,date("m"),date("d"),date("Y"));
        list($ano,$mes,$dia) = explode("-",$data_nascimento);
        $data_passada = mktime(0,0,0,$mes,$dia,$ano);
        $data_atual = mktime (0,0,0,DATE("m"),DATE("d"),DATE("Y"));

        $calculo = floor(($data_atual - $data_passada)/31104000);

        if ( $mes > DATE("m"))
        {
            $calculo -=  1;
        }
        elseif ( $mes == DATE("m") )
        {
            if ( $dia > DATE("d"))
            {
                $calculo -= 1;
            }
        }

        if ( $calculo < 0)
        {
            $calculo =0;
        }


        return ($calculo);
    }*/

    function calcula_idade($data_nascimento){

        list($anoNasc,$mesNasc,$diaNasc) = explode("-",$data_nascimento);
        list ($dia,$mes,$ano) = explode("/",date("d/m/Y"));
        $idade = $ano-$anoNasc;
        $idade = (($mes<$mesNasc) OR (($mes==$mesNasc) AND ($dia<$diaNasc))) ? --$idade : $idade;
        return $idade;
    }
    
    function calcula_idade2($data_nascimento, $data_calcula){
        // as datas devem ser no formato aaaa-mm-dd

        //conversão das datas para o formato de tempo linux
        $data_nascimento = strtotime($data_nascimento." 00:00:00");
        $data_calcula = strtotime($data_calcula." 00:00:00");

        //cálculo da idade fazendo a diferença entre as duas datas
        $idade = floor(abs($data_calcula-$data_nascimento)/60/60/24/365);

        return($idade);
    }
    
    
    
    
    function calc_idade($data_nasc, $formato = "dma", $separador = "-"){
    
        $data_nasc = explode($separador, $data_nasc);
    
        if($formato == "dma"):
            $dia = $data_nasc[0];
            $mes = $data_nasc[1];
            $ano = $data_nasc[2];
        elseif($formato == "amd"):
            $ano = $data_nasc[0];
            $mes = $data_nasc[1];
            $dia = $data_nasc[2];
        elseif($formato == "mda"):
            $mes = $data_nasc[0];
            $dia = $data_nasc[1];
            $ano = $data_nasc[2];
        endif;
    
        $dia_atual = date("d");
        $mes_atual = date("m");
        $ano_atual = date("Y");
    
        $idade = $ano_atual - $ano;
        if ($mes > $mes_atual) {
            $idade--;
        }
        if ($mes == $mes_atual and $dia_atual < $dia) {
            $idade--;
        }
        return $idade;
    }


    /**
     * Método que retorna uma data em array onde as posições
     * [0] -> Dia
     * [1] -> Mês
     * [2] -> Ano
     *
     * @param string $string
     */
    function validarDataBanco($string){
        $data = $string == "" ? "" : explode("-",$string);
        $dia = "";
        $mes = "";
        $ano = "";
        if(is_array($data)){
            $dia = $data[2];
            $mes = $data[1];
            $ano = $data[0];
        }

        $arrayData = array($dia,$mes,$ano);
        return $arrayData;
    }

    public function mesesExistentes($ano, $mes, $periodo, $separador='-', $trata=false, $retorno='meses'){
        $primeiroMes    =   strlen($mes) < 2 ? "{$ano}{$separador}0{$mes}" : "{$ano}{$separador}{$mes}";
        $proximoAno     =   $ano++;
        $inicio         =   1;

        for($i=0; $i<$periodo+1; $i++){
            if($mes>12){
                $ini = $inicio++;
                    if(strlen($ini) < 2){
                        $ini = '0'.$ini;
                    }
                $meses[] = "{$ano}{$separador}{$ini}";
                if(!isset($nAno)){
                    $nAno = $proximoAno + 1;
                }
            }else{
                $ini = $mes++;
                    if(strlen($ini) < 2){
                        $ini = '0'.$ini;
                    }
                $meses[]        =   "{$proximoAno}{$separador}{$ini}";
            }
        }

        if(!isset($nAno)){
            $nAno = $proximoAno;
        }
        $ultimoMes      =   "{$nAno}{$separador}{$ini}";

        switch ($retorno){

            case 'meses':

                if($trata != false && is_array($meses)){
                    $mesesTrata = array();
                    foreach($meses as $mes_Ano){
                        $mes_Ano = explode($separador,$mes_Ano);
                        $mes = $mes_Ano[1];
                        $mes = $this->mes_port_abr($mes);
                        $mesesTrata[] = "{$mes}{$separador}{$mes_Ano[0]}";
                    }
                    return $mesesTrata;
                }

                    return $meses;

                break;

            case 'pontas':

                $meses  =   array($primeiroMes,$ultimoMes);

                if($trata != false){
                    $mesesTrata = array();
                    foreach($meses as $mes_Ano){
                        $mes_Ano = explode($separador,$mes_Ano);
                        $mes = $mes_Ano[1];
                        $mes = $this->mes_port_abr($mes);
                        $mesesTrata[] = "{$mes}{$separador}{$ano}";
                    }
                    return $mesesTrata;
                }

                return $meses;
                break;
        }
    }
    
    /**
     *
     * Calculando datas no futuro ou passado a partir de datas definidas
     * exemplo:
     * Calcular a data daqui 3 dias
     *
     * $format = "d/m/Y H:i:s";
     * $date = "2009-05-20 06:34:00";
     * $calculo = "+ 3 days";
     * calculaData( $format, $date, $calculo );
     *
     * @param String $format
     * @param String $date
     * @param String $calculo
     * @return string
     */
    public function calculaData( $format, $date, $calculo )
    {

       
        $timestamp = strtotime( $date . $calculo );
        return date( $format, $timestamp );
    }
    
    public function horaParaMin( $hora )
    {
        if( $hora != "" && strpos( $hora, ":") )
        {
            list( $hh, $mm, $ss ) = explode(":", $hora );
            $resultadoMinutos = ( ($hh * 3600 ) / 60 ) + $mm;
            return $resultadoMinutos;
        }
        
        return false;
    }

/****************************************************************************
FUNÇÕES PARA STRINGS
*****************************************************************************/

    public function limpa_caracter($campo){
        $campo=trim($campo);
        $estranha = "ÁÉÍÓÚÀÈÌÒÙÂÊÎÔÛÄËÏÖÜÃÕÇáéíóúàèìòùâêîôûäëïöüãõç-@#$%!¨&*(){}][~^´`><:;/?| ";
        $correta  = "AEIOUAEIOUAEIOUAEIOUAOCaeiouaeiouaeiouaeiouaoc___________________________";
        $retorno  = "";

        $contagem=strlen($estranha);

        for($i=0;$i<$contagem;$i++){

            $retorno = str_replace($estranha[$i],$correta[$i],$campo);
            $campo = $retorno;

        }
        while(strpos($campo,"__")){
            $retorno = str_replace("__","_",$campo);
            $campo = $retorno;
        }
        $campo=strtolower($campo);
        return $campo;
    }


    /**
     * Metodo utilizado para tratar a string enviada adicionando caracteres:
     * $string      -> variavél que será alterada
     * $lado        -> "E" esquedo "D" direito
     * $quantidade  -> quantidade da repetição do caracter enviado
     * $carac       -> caracter que será repedido
     *
     * @param $string string
     * @param $lado string
     * @param $quantidade integer
     * @param $carac string
     */
    public function preenche($string,$lado,$quantidade,$carac="0"){

        if($lado=="E"){

            return str_pad($string,$quantidade,$carac,STR_PAD_LEFT);

        }elseif($lado=="D"){

            return str_pad($string,$quantidade,$carac,STR_PAD_RIGHT);

        }
    }

    public function preenche2($value, $side="E", $size, $carac){
        $result = substr(str_pad(trim($value), $size, $carac, $side == "E"? STR_PAD_LEFT:STR_PAD_RIGHT), 0, $size);
        return $result;
    }

    public function censura($texto){

        return str_ireplace($this->palavrao," (censurado) ",$texto);

    }


    public function maiuscula($campo){
        $campo    = strtoupper($campo);
        $estranha = "áéíóúàèìòùâêîôûäëïöüãõç";
        $correta  = "ÁÉÍÓÚÀÈÌÒÙÂÊÎÔÛÄËÏÖÜÃÕÇ";
        $retorno  = "";

        $contagem=strlen($estranha);

        for($i=0;$i<$contagem;$i++){

            $retorno = str_replace($estranha[$i],$correta[$i],$campo);
            $campo = $retorno;

        }
        return $campo;

    }

    public function stringMaiuscula($string){

        $campo    = strtoupper($string);
        $estranha = "áéíóúàèìòùâêîôûäëïöüãõç";
        $correta  = "ÁÉÍÓÚÀÈÌÒÙÂÊÎÔÛÄËÏÖÜÃÕÇ";
        $retorno  = "";

        $contagem=strlen($estranha);

        for($i=0;$i<$contagem;$i++){

            $retorno = str_replace($estranha[$i],$correta[$i],$campo);
            $campo = $retorno;

        }

        return $campo;

    }

    public function primeiraMinuscula($valor){

        $primeiro = substr($valor,0,1);
        $primeiro = strtolower($primeiro);
        $valor = $primeiro . substr($valor,1);

        return $valor;

    }


    public function escreverTamanhoArquivo($tamanho){

        $texto = "byte";

        if($tamanho>=1073741824){

            $tamanho = $tamanho/1073741824;
            $texto = "GB";

        }elseif($tamanho>=1048576){

            $tamanho = $tamanho/1048576;
            $texto = "MB";

        }elseif($tamanho>=1024){

            $tamanho = $tamanho/1024;
            $texto = "KB";

        }

        if($tamanho>1){

            $texto.="s";

        }

        $tamanho = $this->arredonda($tamanho,2,".");

        return $tamanho . $texto;

    }


    /**
     * Verifica se um CPF é válido.
     * @param string $cpf
     * @param boolean $validacaoReal
     * Se for false, a validação NÃO IRÁ considerar como inválido CPF com todos os números iguais. Ex.: '00000000000', '11111111111', '99999999999', etc.
     * @return boolean true se o CPF é válido, e false caso inválido
     */
    public function valida_cpf( $cpf, $validacaoReal = true ) {
        if ( $cpf != "" && $cpf != "0" ) {
            $cpf = $this->trata_cpf( $cpf );
            if ( $validacaoReal === true ) {
                $compara = 0;
                do {
                    if ( substr_count( $cpf, $compara ) == 11 ) {
                        return false;
                    }
                    
                    ++$compara;
                } while ( $compara < 10 );
            }

            for ( $t = 9; $t < 11; $t++ )
            {
                for ( $d = 0, $c = 0; $c < $t; $c++ )
                {
                    $d += $cpf[$c] * ( ( $t + 1 ) - $c );
                }

                $d = ( ( 10 * $d ) % 11 ) % 10;

                if ( $cpf[$c] != $d )
                {
                    return false;
                }
            }
            return true;
        }
        return false;
    }
    
    public function trata_cpf($cpf){
        $cpf=preg_replace("/[\"\.\-\,\/]/","",$cpf);
        $cpf=preg_replace('/[^0-9]*[a-zA-Z]*/s', "", $cpf);
        
        $cpf=trim($cpf);
        return $this->preenche($cpf,"E",11);
    }

    function trata_cnpj($cnpj){
        $cnpj=preg_replace("/[\"\.\-\,\/]/","",$cnpj);
        $cnpj=trim($cnpj);
        return $this->preenche($cnpj,"E",14);
    }

    public function validaCNPJ($cnpj)
    {
        $cnpj = preg_replace( "@[./-]@", "", $cnpj );

        if( strlen( $cnpj ) <> 14 or !is_numeric( $cnpj ) ) {
            return false;
        }

        $k = 6;
        $soma1 = "";
        $soma2 = "";

        for( $i = 0; $i < 13; $i++ ) {
            $k = $k == 1 ? 9 : $k;
            $soma2 += ( $cnpj{$i} * $k );
            $k--;

            if($i < 12) {
                if($k == 1) {
                    $k = 9;
                    $soma1 += ( $cnpj{$i} * $k );
                    $k = 1;
                } else {
                    $soma1 += ( $cnpj{$i} * $k );
                }
            }
        }


        $digito1 = $soma1 % 11 < 2 ? 0 : 11 - $soma1 % 11;
        $digito2 = $soma2 % 11 < 2 ? 0 : 11 - $soma2 % 11;

        return ( $cnpj{12} == $digito1 and $cnpj{13} == $digito2 );
    }

    function valida_cep($cep){
        //$cep_ereg = "^\d{8}$";
        $cep_ereg = "[0-9]{8}";

        if(!ereg($cep_ereg,$cep)){

            return false;

        }

        return true;

    }


    public function trata_cep($cep){
        $cep=preg_replace("/[\"\.\-\,\/]/","",$cep);
        return trim($cep);
    }





    function br2p($texto){

        $texto = nl2br($texto);

        $array_texto = explode("<br />",$texto);

        $texto_tratado = "<p>";

        foreach($array_texto as $quebra){

            if(trim($quebra) != ""){

                $texto_tratado.=$quebra. "</p><p>";

            }

        }

        $texto_tratado.= "</p>";

        return $texto_tratado;

    }



    function valida_data($dia,$mes,$ano)
    {
        $anoAtual = date("Y");
        $resultado = (($dia > 0) && ($dia < 32)) && (($mes > 0) && ($mes < 13)) && ((strlen($ano) == 4) && ($ano >= 1900));
        if($mes==2)
        {
            if(($ano%4==0 && !($dia>0 && $dia<30)) || ($ano%4!=0 && !($dia>0 && $dia<29)))
            {
                return false;
            }
        }
        if(($mes==4 || $mes==6 || $mes==9  || $mes==11) && $dia==31){
            return false;
        }
        if (!$resultado) {
            return false;
        }else{
            return true;
        }
    }


    function valida_email($email){

        if(!ereg("^[a-z0-9_.\-]+@([a-z0-9_]+\.)+[a-z]{2,4}$", $email)){

            return false;

        }

        return true;

    }

    public function isoToUtf8($a){
        if (is_array($a)){
            foreach ($a as $k => $v) {
                if (!is_array($v)){
                    $a[$k] = utf8_encode($a[$k]);
                } else {
                    $this->isoToUtf8($a[$k]);
                }
            }

        }else{

            $a = utf8_encode($a);
        }

        return $a;
    }

    public function utf8ToIso($a){
        if (is_array($a)){
            foreach ($a as $k => $v) {
                if (!is_array($v)){
                    $a[$k] = utf8_decode($a[$k]);
                } else {
                    $a[$k] = $this->utf8ToIso($a[$k]);
                }
            }

        }else{

            $a = utf8_decode($a);
        }

        return $a;
    }
    
    public function limpaString($string)
    {
        return preg_replace("/\d+/", "", $string );     
    }
    
    public function CamelCase( $string )
    {
        $string = strtolower( $string );
        if( strpos( $string, "_" ) )
        {
            $string = str_replace( "_", " ", $string );
            $string = ucwords( $string );
            $string = str_replace( " ", "", $string );
        }
        elseif( strpos( $string, " " ) )
        {
            $string = ucwords( $string );
            $string = str_replace( " ", "", $string );
        }
        else
        {
            $string =ucfirst( $string );
        }
        return $string;
    }
/****************************************************************************
    FUNÇÃO FULL UPPER
*****************************************************************************/
    
    
    function fullUpper($string){
        return strtr(strtoupper($string), array(
          "à" => "À",
          "è" => "È",
          "ì" => "Ì",
          "ò" => "Ò",
          "ù" => "Ù",
          "á" => "Á",
          "é" => "É",
          "í" => "Í",
          "ó" => "Ó",
          "ú" => "Ú",
          "â" => "Â",
          "ê" => "Ê",
          "î" => "Î",
          "ô" => "Ô",
          "û" => "Û",
          "ç" => "Ç",
        ));
    }
    


/****************************************************************************
    FUNÇÃO LIMITAR CARACTERES
*****************************************************************************/
    
    function str_chop($string, $length = 60, $center = false, $append = null){
        // Set the default append string
        if ($append === null)
        $append = ($center === true) ? ' ... ' : '...';
    
        // Get some measurements
        $len_string = strlen($string);
        $len_append = strlen($append);
    
        // If the string is longer than the maximum length, we need to chop it
        if ($len_string > $length) {
            // Check if we want to chop it in half
            if ($center === true) {
                // Get the lengths of each segment
                $len_start = $length / 2;
                $len_end = $len_start - $len_append;
    
                // Get each segment
                $seg_start = substr($string, 0, $len_start);
                $seg_end = substr($string, $len_string - $len_end, $len_end);
    
                // Stick them together
                $string = $seg_start.$append.$seg_end;
            } else {
                // Otherwise, just chop the end off
                $string = substr($string, 0, $length - $len_append).$append;
            }
        }
    
        return $string;
    }   
    
/****************************************************************************
FUNÇÕES PARA STATUS
*****************************************************************************/

    function imprime_status($status){
        if($status==1){
            return $this->html="<img src='"
                . PASTA_IMAGENS_GERAL . "icone_ativo.gif' border='0'"
                . " class='imagelink' title='Ativo' alt='ativo'/>";
        }else{
            return $this->html="<img src='"
                . PASTA_IMAGENS_GERAL ."icone_inativo.gif' border='0'"
                . " class='imagelink' title='Inativo' alt='inativo'/>";
        }
    }



/****************************************************************************
FUNÇÕES PARA ARRAY
*****************************************************************************/

    function monta_array_exibi($retira,$original){

        $array_final=array();

        foreach($original as $chave=>$valor){

            if (!in_array($chave,$retira) ) {

                $array_final[$chave] = $original[$chave];

            }

        }

        return $array_final;

    }


    public function array2string($array, $tabulacao = 0){

        $resposta = "";

        foreach ( $array as $indice=>$conteudo){
            if ( is_array($conteudo))
            {
                $resposta .= "[". $indice ."] => {\r\n";
                $resposta .= $this->array2string($conteudo, ($tabulacao+1));
                $resposta .= "}\r\n";
            }
            else
            {
                for($i=0; $i<$tabulacao; $i++){

                    $resposta .="\t";

                }
                $resposta .= "[". $indice ."] => ". $conteudo ."\r\n";
            }
        }

        return $resposta;

    }

    public function array_trim(&$array){
        foreach ($array as $indice=>$conteudo){
            //echo $conteudo."<br/>";
            $array[$indice] = trim($conteudo);
        }
        return $array;
    }
    
    public function format_date($format, $date) {
        if($date=="" ||  $format=="") return "";
        $timestamp = strtotime($date);
        return date($format, $timestamp);
    }
    
    public function format_date_db($date) {
        if($date!=""){
            $date_arr = explode('/',$date);
            $date = $date_arr[2].'-'.$date_arr[1].'-'.$date_arr[0];
        }
        return $date;
    }
    
    public function format_date_hour_db($date) {
        $hora = "";
        if($date!=""){
            $date_arr = explode('/',$date);
            $posicao_espaco = strpos($date_arr[2], ' ');
            $hora = substr($date_arr[2], $posicao_espaco);
            $date_arr[2] = substr($date_arr[2], 0, $posicao_espaco);    
            $date = $date_arr[2].'-'.$date_arr[1].'-'.$date_arr[0];
        }
        return $date.$hora;
    }
   
//    public function slug($campo){
//      $campo = ereg_replace("[^a-zA-Z0-9_]", "_", strtr($campo, "???????¥µÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖØÙÚÛÜÝßàáâãäåæçèéêëìíîïðñòóôõöøùúûüýÿ", "SOZsozYYuAAAAAAACEEEEIIIIDNOOOOOOUUUUYsaaaaaaaceeeeiiiionoooooouuuuyy"));
//      $campo = strtolower($campo);
//      return $campo;
//    }
    
    function somar_dias_uteis($str_data,$int_qtd_dias_somar = 7) {
        $str_data = substr($str_data,0,10);
    
        if ( preg_match("@/@",$str_data) == 1 ) {
            $str_data = implode("-", array_reverse(explode("/",$str_data)));
        }
    
        $array_data = explode('-', $str_data);
        $count_days = 0;
        $int_qtd_dias_uteis = 0;
    
        while ( $int_qtd_dias_uteis < $int_qtd_dias_somar ) {
            $count_days++;
            if ( ( $dias_da_semana = gmdate('w', strtotime('+'.$count_days.' day', mktime(0, 0, 0, $array_data[1], $array_data[2], $array_data[0]))) ) != '0' && $dias_da_semana != '6' ) {
                $int_qtd_dias_uteis++;
            }
        }
    
        return gmdate('d/m/Y',strtotime('+'.$count_days.' day',strtotime($str_data)));
    }
    
    public function formataCpf($cpf) {
        $cpf =
        preg_replace('/^(\d{3})(\d{3})(\d{3})(\d{2})$/',
                '${1}.${2}.${3}-${4}', $cpf);
        return $cpf;
    }
    
    public function stringToUtf8($string) {
        if(!mb_check_encoding($string, 'UTF-8') OR !($string === mb_convert_encoding(mb_convert_encoding($string, 'UTF-32', 'UTF-8' ), 'UTF-8', 'UTF-32'))) {
            $string = mb_convert_encoding($string, 'UTF-8');
        }
        return $string;
    }
    
    function mask( $val, $mask)
    {
        $maskared = '';
            
        for($k = 0, $i = 0; $i <= ( strlen( $mask ) -1 ); $i++)
        {
        if($mask[$i] == '#')
        {
            if(isset($val[$k]))
                $maskared .= $val[$k++];
            }
            else
            {
            if(isset($mask[$i]))
                $maskared .= $mask[$i];
            }
        }
                
        return $maskared;
    }
    
     
    
    public function unicode( $string )
    {
        $unicode = array(
            "u00e1", "u00e0", "u00e2", "u00e3", "u00e4", "u00c1", "u00c0", "u00c2", "u00c3", "u00c4", "u00e9", "u00e8",
            "u00ea", "u00ea", "u00c9", "u00c8", "u00ca", "u00cb", "u00ed", "u00ec", "u00ee", "u00ef", "u00cd", "u00cc",
            "u00ce", "u00cf", "u00f3", "u00f2", "u00f4", "u00f5", "u00f6", "u00d3", "u00d2", "u00d4", "u00d5", "u00d6",
            "u00fa", "u00f9", "u00fb", "u00fc", "u00da", "u00d9", "u00db", "u00e7", "u00c7", "u00f1", "u00d1", "u0026",
            "u0027"
        );
        
        $letter = array(
            "&aacute;","&agrave","&acirc;","&aatilde;","&auml;","&Aacute;","&Agrave","&Acirc;","&Aatilde;","&Auml;","&eacute;","&egrave","&ecirc;","&ecirc;",
            "&Eacute;","&Egrave","&Ecirc;","&Euml;", "&iacute;","&igrave","&icirc;","&iuml;","&Iacute;","&Igrave","&Icirc;","&Iuml;","&oacute;","&ograve","&ocirc;",
            "&oatilde;","&ouml;","&Oacute;","&Ograve","&Ocirc;","&Oatilde;","&Ouml;", "&uacute;","&ugrave","&ucirc;","&uuml;","&Uacute;","&Ugrave","&Ucirc;","&ccedil",
            "&Ccedil","&natilde;","&Natilde;","&amp;","&rsquo;"
        );
        
        foreach ($unicode AS $index=>$valor)
        {
            if( strpos($string, $valor) !== false )
            {
                $string = str_replace($valor, $letter[$index], $string);
            }
        }       
        
        return $string;
    }
    
    public function isAjax(){
        return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
    }
    
    public function minutoParaHora($minutos) {
        if($minutos=="" || $minutos==0){
            return;
        }
        $hora = floor($minutos/60);
        $resto = $minutos%60;
        return $hora.':'.$resto;
    }
    

     /**
     * 
     * @param type $peso
     * @param type $altura
     * @param type $gestante
     * @param type $idade
     * @return type
     */
    public function imc($peso,$altura,$gestante=false , $idade=null){
//        $altura = bcpow($altura, 2 , 2);
//        $imc = $peso / $altura;
//        $imc = round($imc);
////        if ($massa < 20) {
////        $mensagem = "Você está Magro";
////        } elseif(($massa > 20) and ($massa <25)) {
////        $mensagem = "Você está no pesso Ideal";
////        } else {
////        $mensagem = "Você está acima do peso.";
////        }
//        return $imc;
        
        $conta1 = ($altura*$altura);
        $conta2 = $peso/$conta1;
        $imc = number_format($conta2);
        return $imc;
        
    }
    
    
    /**
     * DETERMINA O PERCENTUAL FEITO DE UMA QUANTIDADE TOTAL
     * @param type $total
     * @param type $feitos
     * @return type
     * @reference https://www.scriptbrasil.com.br/forum/topic/174898-c%C3%A1lculo-de-porcentagem/
     */
    public function calcula_percentual($total,$feitos){
        $calculo = (($feitos / $total) * 100);
        
        return $calculo;
    }

    /**
    * 
    *
    * Tipo: 
    *    - 1 => Cadastro Novo 
    *    - 2 => Vestibular Agendado
    *    - 3 => Vestibular Aprovado
    *    - 4 => 
    *    - 5 => 
    *    - 6 => 
    *    - 7 => 
    *
    */
     #TESTE ENVIO MSG
    /*
    $msgArr = array();
    $msgArr['usuario'] = 'bruno@movimentocidadania.com.br';
    $msgArr['password'] = '01movcid01';
    $msgArr['msg'] = urlencode('Teste get contents'); FUNCIONOU
    //$msgArr['msg'] = str_replace(' ','%20','Teste get contents sem url'); FUNCIONOU
    $msgArr['number'] = '5511963880938';
            
    $url = 'http://painel.maciv.com/SendAPI/Send.aspx?usr='.$msgArr['usuario'].'&pwd='.$msgArr['password'].'&number='.$msgArr['number'].'&sender=&msg='.$msgArr['msg'];
    $envia_msg = file_get_contents($url);
    krumo($envia_msg);
    krumo($url);

    #teste email
    if($this->data['Usuario']['empresa_id'] == '53'){//Sr Roberto roberto@faculdadeprogresso.edu.br
        $toEmail = 'roberto@faculdadeprogresso.edu.br';
        $subject = 'Cadastro de Aluno';
        $msg = "Olá ";

        $msg .= "Sr. Roberto<br><br>";
        $msg .= "Foi cadastrado um novo aluno agora ".date('d/m/Y')." às ".date('H:i')." <br><br>";
        $msg .= "<b>Aluno Nome:</b> ".$this->data['Usuario']['nome']." <br>";
        $msg .= "<b>E-mail:</b> ".$this->data['Usuario']['email']." <br>";
        $msg .= "<b>Telefone ".$this->data['Usuario']['tel1_tipo']." : </b>".$this->data['Usuario']['tel1']."  <br>";
        
        #EFETUAR TESTE
    //                    parent::envio_email($toEmail,$subject,$msg);
        #END - ENVIAR EMAIL DESENVOLVER (FACULDADE PROGRESSO)
    }
    */


    public function envia_mensagem($data,$tipo=1){
        $empresa_id = $data['empresa_id'];
        if($empresa_id == ''){return false;}

        
        static $Mensagem;
//        static $Curso;
        if(is_null($Mensagem)){
            $Mensagem = ClassRegistry::init('Mensagem');
        }
//        if(is_null($Curso)){
//            $Curso = ClassRegistry::init('Curso');
//        }
//        
        $mensagemArr = $Mensagem->find('first', array('conditions'=>array('Mensagem.empresa_id'=>$empresa_id,'Mensagem.tipo'=>$tipo)));
        
//        $cursoArr = $Curso->find('first', array('conditions'=>array('Curso.curso_id'=>$data['curso_id'])));
//        krumo($cursoArr);
//        exit();
        
        if(isset($mensagemArr['Mensagem']) && count($mensagemArr['Mensagem']) > 0){
            $msg_sms = $mensagemArr['Mensagem']['sms'];
            $msg_email = $mensagemArr['Mensagem']['email'];
            
            $convert_msg = array('[NOME]'=>$data['nome'],
                                 '[FACULDADE]'=>$mensagemArr['Empresa']['nome'],
                                 '[UNIDADE]'=>'Unidade',
                                 '[DATAHORA]'=>date('d/m/Y').' às '.date('H:i:s'),
                                 '[DATA]'=>date('d/m/Y'),
                                 '[HORA]'=>date('H:i:s'),
//                                 '[CURSO]'=>'Curso',
//                                 '[PERIODO]'=>'Período',
                                 '[TEL_FACULDADE]'=>$mensagemArr['Empresa']['telefone'],
                                 '[PULA_LINHA]'=>'<br>');
            
            #CONVERT REFERENCIAS
            foreach($convert_msg as $kRef => $vRef):
                $msg_sms = str_replace($kRef,$vRef,$msg_sms);
                $msg_email = str_replace($kRef,$vRef,$msg_sms);
            endforeach;

            if($data['tel1_tipo'] == 'Celular' && $data['tel1'] != '' && $msg_sms){$enviado = $this->envia_tel($data['tel1'],$msg_sms);}
            if($data['tel2_tipo'] == 'Celular' && $data['tel2'] != '' && $msg_sms){$enviado = $this->envia_tel($data['tel2'],$msg_sms);}
            if($data['tel3_tipo'] == 'Celular' && $data['tel3'] != '' && $msg_sms){$enviado = $this->envia_tel($data['tel3'],$msg_sms);}
            
            if($data['email'] != '' && $msg_email != '')
            {
                $enviado = $this->envia_email($data['email'],'Cadastro de Aluno',$msg_email);
            }
        }

        return true;
    }

    public function envia_tel($tel){
        $telefone = str_replace('_','',$tel);
        $telefone  = trim($telefone);
        $telefone  = str_replace('(', '', $telefone );
        $telefone  = str_replace(')', '', $telefone );
        $telefone  = '55'.$telefone ;
//        $url = 'http://painel.maciv.com/SendAPI/Send.aspx?usr=bruno@movimentocidadania.com.br&pwd=01movcid01&number='.$telefone_ok.'&sender=&msg='. urlencode($msg_sms);
//        $envia_msg = file_get_contents($url);
//        krumo('envia_msg');
        return true;
    }
    
    public function envia_email($email,$assunto,$msg_email,$template = 'cadastro_novo'){
        #trata email
//        $Email = new CakeEmail();
//        $Email->config('default');
//        $Email->emailFormat('html');
//        $Email->template($template)->viewVars(array('msg'=>$msg_email));
//        $Email->to($email);
//        $Email->subject($assunto);
//        $Email->send('default');
//        krumo('envia_email');
        return true;
    }

    public function busca_empresa($conditions = array(), $fields = array(), $findType = 'all'){
        static $Empresa;
        if(is_null($Empresa)){
            $Empresa = ClassRegistry::init('Empresa');
        }
        $empresaArr = $Empresa->find($findType, array('conditions'=>$conditions,'fields'=>$fields,'recursive'=>'-1'));
        return $this->retiraSubArray($empresaArr,'Empresa');

    }

    public function busca_aluno($conditions = array(), $fields = array(), $findType = 'all'){
        static $Aluno;
        if(is_null($Aluno)){
            $Aluno = ClassRegistry::init('Aluno');
        }
        $alunoArr = $Aluno->find($findType, array('conditions'=>$conditions,'fields'=>$fields,'recursive'=>'-1'));

        return $this->retiraSubArray($alunoArr,'Aluno');
    }


    /**
     * ABA "CLASSIFICAÇÃO ALIMENTAÇÃO" DO MAPEAMENTO
     * Cores: qv_sinalizador 1=>cinza; 2=>verde; 3=>amarelo; 4=>vermelho
     * @param type $classificacoes array de cores (verde, amarelo, vermelho)
     * @return type
     * 
     */
    
    public function alimentacao_classificacao($classificacoes = array()) {
        if (count($classificacoes) < 0) {
            return 0;
        }
        $verde = $classificacoes['verde'];
        $amarelo = $classificacoes['amarelo'];
        $vermelho = $classificacoes['vermelho'];
        
        $cor = array();
        $cor[7][0][0] = 6;
        $cor[6][1][0] = 6;
        $cor[6][0][1] = 7;
        $cor[5][2][0] = 6;
        $cor[5][0][2] = 7;
        $cor[5][1][1] = 7;
        $cor[4][3][0] = 7;
        $cor[4][0][3] = 8;
        $cor[4][2][1] = 7;
        $cor[4][1][2] = 7;
        $cor[3][4][0] = 7;
        $cor[3][0][4] = 8;
        $cor[3][3][1] = 7;
        $cor[3][1][3] = 8;
        $cor[3][2][2] = 7;
        $cor[2][5][0] = 7;
        $cor[2][0][5] = 8;
        $cor[2][4][1] = 7;
        $cor[2][1][4] = 8;
        $cor[2][3][2] = 7;
        $cor[2][2][3] = 8;
        $cor[1][6][0] = 7;
        $cor[1][0][6] = 8;
        $cor[1][5][1] = 7;
        $cor[1][1][5] = 8;
        $cor[1][4][2] = 7;
        $cor[1][2][4] = 8;
        $cor[1][3][3] = 8;
        $cor[0][7][0] = 7;
        $cor[0][6][1] = 7;
        $cor[0][5][2] = 7;
        $cor[0][4][3] = 8;
        $cor[0][3][4] = 8;
        $cor[0][2][5] = 8;
        $cor[0][1][6] = 8;
        $cor[0][0][7] = 8;

        return isset($cor[$verde][$amarelo][$vermelho]) ? $cor[$verde][$amarelo][$vermelho] : 5;
    }
    
    
    public function periodo_gestacional($semana = ''){
        if($semana == ''){ return ''; }
        $semanas = array();
        $texto1a4   = 'Começou a jornada! O óvulo fecundado pelo espermatozoide já subiu as trompas e se instalou no útero. 
            As células se dividiram e originaram o embrião. 
            É dessa estrutura, tão pequena quanto uma sementinha de maçã, que vão se originar todos os órgãos do bebê. 
            Nas duas próximas semanas, iniciará a formação do tubo neural, coração, aparelho digestivo, os olhos, as orelhas, os braços e pernas.  <br><br>
            Está com aproximadamente 5mm.
        ';
        $texto5a8   = 'Ele está crescendo rápido. O corpo do embrião adquiriu a forma de um C e agora está mais parecido com um ser humano. 
            Os braços e pernas despontaram e a cabeça está sendo moldada. 
            Outros órgãos, como o intestino e o pâncreas, estão se desenvolvendo. É neste período que o coração de seu bebê começará a bater.  <br><br>
            Está com aproximadamente 3cm e pesa cerca de 10 gramas.
            ';
        $texto9a12  = 'Os braços e pernas estão mais alongados, os dedinhos ganharam forma e as unhas começaram a nascer. 
            As pálpebras e a pontinha do nariz agora podem ser vistas no rosto. 
            O sistema circulatório e urinário já estão funcionando, os órgãos genitais já se desenvolveram, os músculos e as articulações consentem ao feto fazer seus primeiros movimentos e ele já pratica a sucção, engolindo líquidos à sua volta. <br><br>
            Está com aproximadamente 7cm e pesa cerca de 15 gramas.
            ';
        $texto13a16 = 'Uma fina camada de pelos, chamada lanugem, surgiu para proteger a pele do bebê. 
            Com 4 meses de gestação ele já percebe alterações de luz e consegue diferenciar entre os gostos amargo e doce. 
            O fígado começa a produzir o primeiro suco gástrico e os rins iniciam a produção de urina, que é diluída e formada basicamente de líquido amniótico. <br><br>
            Está com aproximadamente 12cm e pesa cerca de 200 gramas.
            ';
        $texto17a21 = 'Nas meninas, as trompas e o útero aparecem a partir deste mês. Se for menino, os órgãos genitais externos já podem ser vistos no exame de ultrassom. 
            Os músculos faciais estão ativos, e o bebê agora é capaz de franzir a testa, piscar os olhos e chupar o dedo. 
            A futura mamãe começará a sentir os primeiros movimentos do bebê.<br><br>
            Está com aproximadamente 25 cm e pesa cerca de 400 gramas.
            ';
        $texto22a26 = 'Neste mês, seu bebê começa a desenvolver quatro sentidos: audição, olfato, tato e paladar. 
            O bebê já reage a estímulos externos, como as vozes e músicas, e também percebe barulhinhos como o bater do seu coração ou os gorgulhos intestinais. 
            Aproveite para conversar bastante com seu bebê e fortalecer o vínculo entre vocês. 
            Prepare-se! Agora você vai começar a sentir chutes, socos e cotoveladas, que farão você vibrar de emoção.<br><br>
            Está com aproximadamente 33cm e pesa cerca de 700g.
            ';
        $texto27a30 = 'Os primeiros fios de cabelo estão nascendo, mas a cor poderá mudar depois que ele deixar o seu útero.
                Ele já reage abrindo e fechando os olhos e responde a certos estímulos com choro. 
                Ao final do 7º mês, seus pulmões começam a se desenvolver.<br><br>
                Está com aproximadamente 38cm e pesa cerca de 1200 gramas.
                ';
        $texto31a35 = 'Uma camada de gordura se formou sob a pele do bebê. 
            Quando ele nascer, ela ajudará a manter sua temperatura corporal equilibrada. 
            Seus pulmões estão quase prontos e são exercitados diariamente enquanto ele inspira e expira o líquido amniótico.<br><br>
            Está com aproximadamente 46cm e pesa cerca de 2400 gramas
            ';
        $texto36a40 = 'Hora dos ajustes finais! 
            Seus cotovelos e joelhos agora formam covinhas. 
            Os órgãos estão prontos e em funcionamento e o seu corpo recebeu uma boa camada de gordura e agora está bem mais rechonchudo. 
            Todas estas características são sinais que ele está pronto para deixar o conforto do útero materno.<br><br>
            Está com aproximadamente 48- 52cm e pesa cerca de 3000-3200 gramas.
            ';
        
        $semanas[1] = $texto1a4;
        $semanas[2] = $texto1a4;
        $semanas[3] = $texto1a4;
        $semanas[4] = $texto1a4;
        $semanas[5] = $texto5a8;
        $semanas[6] = $texto5a8;
        $semanas[7] = $texto5a8;
        $semanas[8] = $texto5a8;
        $semanas[9] = $texto9a12;
        $semanas[10] = $texto9a12;
        $semanas[11] = $texto9a12;
        $semanas[12] = $texto9a12;
        $semanas[13] = $texto13a16;
        $semanas[14] = $texto13a16;
        $semanas[15] = $texto13a16;
        $semanas[16] = $texto13a16;
        $semanas[17] = $texto17a21;
        $semanas[18] = $texto17a21;
        $semanas[19] = $texto17a21;
        $semanas[20] = $texto17a21;
        $semanas[21] = $texto17a21;
        $semanas[22] = $texto22a26;
        $semanas[23] = $texto22a26;
        $semanas[24] = $texto22a26;
        $semanas[25] = $texto22a26;
        $semanas[26] = $texto22a26;
        $semanas[27] = $texto27a30;
        $semanas[28] = $texto27a30;
        $semanas[29] = $texto27a30;
        $semanas[30] = $texto27a30;
        $semanas[31] = $texto31a35;
        $semanas[32] = $texto31a35;
        $semanas[33] = $texto31a35;
        $semanas[34] = $texto31a35;
        $semanas[35] = $texto31a35;
        $semanas[36] = $texto36a40;
        $semanas[37] = $texto36a40;
        $semanas[38] = $texto36a40;
        $semanas[39] = $texto36a40;
        $semanas[40] = $texto36a40;

        return  (isset($semanas[$semana])) ? $semanas[$semana] : '';
    }
    
    
    public function get_name_database(){
        $source = ConnectionManager::getDataSource('default');
        return $source->config['database'];
    }
    
    
    public function map_convert_in_array($data){
        $array = array();
        
        foreach($data as $kData => $vData){
            foreach($vData as $k => $v){
                if(is_array($v)){
                    $array[$k] = $v;
                }else{
                    $array[$k] = array($v);
                }
            }
        }
        
        return $array;
    }

    
    public function pr($data){
        echo '<pre>';
        print_r($data);
        echo '</pre>';
    }
    public function pre($data){
        echo '<pre>';
        print_r($data);
        echo '</pre>';
        exit();
    }
    
    
    function utf8Fix($msg){
        $accents = array("á", "à", "â", "ã", "ä", "é", "è", "ê", "ë", "í", "ì", "î", "ï", "ó", "ò", "ô", "õ", "ö", "ú", "ù", "û", "ü", "ç", "Á", "À", "Â", "Ã", "Ä", "É", "È", "Ê", "Ë", "Í", "Ì", "Î", "Ï", "Ó", "Ò", "Ô", "Õ", "Ö", "Ú", "Ù", "Û", "Ü", "Ç");
        $utf8 = array("Ã¡","Ã ","Ã¢","Ã£","Ã¤","Ã©","Ã¨","Ãª","Ã«","Ã­","Ã¬","Ã®","Ã¯","Ã³","Ã²","Ã´","Ãµ","Ã¶","Ãº","Ã¹","Ã»","Ã¼","Ã§","Ã","Ã?","Ã?","Ã?","Ã?","Ã?","Ã?","Ã?","Ã?","Ã","Ã?","Ã?","Ã","Ã?","Ã?","Ã?","Ã?","Ã?","Ã?","Ã?","Ã?","Ã?","Ã?");
        $fix = str_replace($utf8, $accents, $msg);
        return $fix;
    }
    
    function raw_json_encode3($input) {
        
    }
    
    function model_exists($type){
        $model_list = array_flip(App::objects('model'));
        return isset($model_list[$type]);
    }

    
    public function nome_menor($nome_completo, $caracteres_max = 32, $abreviacao = 3){
        if(strlen($nome_completo) > $caracteres_max){
            $nomeCompletoArr = explode(' ',$nome_completo);
            foreach($nomeCompletoArr as $kn => $vn){
                $nomeCompletoArr[$kn] = trim($vn); #TIRANDO ESPAÇOS
            }
            
            if(count($nomeCompletoArr) > 2){
                $nomeMenor = array();
                $nomeMenor[] = $nomeCompletoArr[0]; #PEGA PRIMEIRO NOME
                for($i=1 ; $i < (count($nomeCompletoArr)-1) ; $i++){
                    $nomeMenor[] = substr($nomeCompletoArr[$i], 0, $abreviacao);#FAZ ABREVIAÇÃO DE 3 CARACTERES
                }
                $nomeMenor[] = $nomeCompletoArr[count($nomeCompletoArr)-1]; #PEGA ÚLTIMO NOME
                
                #VERIFICA SE O NOME É MAIOR QUE OS CARACTERES MÁXIMOS E VAI REMOVENDO O ÚLTIMO INDICE
                for($i=1;$i<count($nomeMenor);$i++){
                    $nome_completo = implode(' ',$nomeMenor);
                    if(strlen($nome_completo) > $caracteres_max && count($nomeMenor) > 2){
                        unset($nomeMenor[count($nomeMenor)-2]);
                    }else{
                        break;
                    }
                }
            }
        }
        return $nome_completo;
    }


    public function gera_notificacao($data){
        #CRIAR NOTIFICAÇÃO

        #BUSCA USUÁRIOS QUE SERÃO NOTIFICADOS DE UM CLIENTE ESPECÍFICO

        #GERA NOTIFICAÇÃO

        #GERA NOTIFICAÇÃO USUÁRIO


    }

    public function busca_notificacao($usuario_id,$contagem = false){
        $NotificacaoUsuario = ClassRegistry::init('NotificacaoUsuario');
        $typeFind = ($contagem === false)? 'all' : 'count';
        $notificacaoArr = $this->$NotificacaoUsuario->find($typeFind,array('conditions'=>array('usuario_id'=>$usuario_id)));
        
        return $notificacaoArr;
    }
    

    public function att_notificacao($usuario_id){
        $NotificacaoUsuario = ClassRegistry::init('NotificacaoUsuario');
        $sql = 'update notificacao set status = 0 where usuario_id = '.$usuario_id;
        $result = $this->$NotificacaoUsuario->query($sql);
        
        #$this->$NotificacaoUsuario->deleteAll(array('NotificacaoUsuario.usuario_id' => $usuario_id), false);
      
        return true;
    }

     
    
    /**
     * 
     * @param type $url
     * @param array $fields
     * 
     *  App::uses('HttpSocket', 'Network/Http');
            $url  = ENDERECO.'v4/ws/gera_cronicos/';
//            $HttpSocket = new HttpSocket();
//            $response = $HttpSocket->post($url, array('cod_preenchido'=>$cod_preenchido));
//            
//            
            $response = $this->Funcoes->call_background($url, array('cod_preenchido'=>$cod_preenchido));
            $this->pr($response);
     */
    public function call_background($url,array $fields){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, count($fields));
//        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($fields));
        $result = curl_exec($ch);
        curl_close($ch);
        var_dump($result);
    }
    
    /**
     * BUSCA REGISTRO GERAL
     * Faz a varredura geral de um conteúdo dentro de um banco, de uma tabela, de uma coluna
     * @param type $busca
     * @param type $tabela
     * @param type $banco
     * @param type $busca_registro
     * @return type
     */
    public function busca_registro_geral($busca,$tabela = array(),$banco = 'victorysolutions',$busca_registro = true){
        if(count($this->QvPreenchido) == 0){
            $this->QvPreenchido = ClassRegistry::init('QvPreenchido');
        }
        
        #CRIAR $busca_registro = false para buscar somente 
        
        if(count($tabela) > 0){
            $tabela = implode(',',$tabela);
            $tabela = "AND TABLE_NAME IN '{$tabela}' ";
        }
        $result = $this->QvPreenchido->query("  SELECT TABLE_SCHEMA, TABLE_NAME, COLUMN_NAME
                                                FROM INFORMATION_SCHEMA.COLUMNS
                                                WHERE TABLE_SCHEMA = '{$banco}' {$tabela}
                                                GROUP BY COLUMN_NAME
                                                ORDER BY ORDINAL_POSITION ASC;");
        $local = array();
        if(count($result)>0){
            foreach($result as $res){
                $res = $res['COLUMNS'];
                $sql = "SELECT {$res['COLUMN_NAME']} FROM {$res['TABLE_NAME']} WHERE {$res['COLUMN_NAME']} like '%{$busca}%';";
                $result2 = $this->QvPreenchido->query($sql);
                if(count($result2)>0){
                    foreach($result2 as $res2){
                        $sql2 = "SELECT * FROM {$res['TABLE_NAME']}  WHERE {$res['COLUMN_NAME']} like '%{$busca}%';";
                        $local[$res['TABLE_SCHEMA']][$res['TABLE_NAME']][$res['COLUMN_NAME']]['SQL'] = $sql2;
                        $local[$res['TABLE_SCHEMA']][$res['TABLE_NAME']][$res['COLUMN_NAME']]['COUNT'] = count($result2);
                    }
                }
            }
        }
        
        return $local;
    }

    public function busca_logo($tipo,$id){
        $tipo = trim($tipo);
        $tipo = strtolower($tipo);
        
        if(in_array($tipo, array('cliente','grupo_empresarial')) && is_numeric($id)){
            $class = ($tipo == 'grupo_empresarial')? 'GrupoEmpresarial' : 'Cliente' ; 
            $classData = ClassRegistry::init($class);
            if (!$classData->exists($id)) {
               return false;
            }
            $classData->recursive = -1;
            $row = $classData->find('first', array('conditions' => array($classData->primaryKey => $id),'fields'=>'img_logo'));
            
            $logo = '';
            if(count($row)>0 && $row[$class]['img_logo'] != '') {
                $imagem = 'img'. DS .'uploads' . DS . $tipo. DS .$row[$class]['img_logo'];
                if (file_exists($imagem)){
                    $logo = Router::url('/img/uploads/'.$tipo.'/'.$row[$class]['img_logo'],true);
                }
            }
            return $logo;
        }else{
            return '';
        }
    }
    
    
    /**
  * CHAMADA ASSINCRONA 
  * @param  [type] $url     [description]
  * @param  [type] $payload [description]
  * @return [type]          [description]
  * @example ABAIXO SEGUE O EXEMPLO [<description>]
  * $url = "http://teste.com.br/enviar.php";
    $payload = array("nome_da_variavel_post" => "valor_de_teste");
    asyncRequest($url, $payload);
     Observações
    É necessário que a função fsockopen esteja habilitada
    Se não for possível utilizar a função fsockopen, utilize pfsockopen.
    A configuração allow_url_fopen, tem que estar habilitada, altere no seu php.ini se necessário.
    É uma requisição post, normal, recupere os valores utilizando $_POST, no arquivo de destino.
    Função não retorna nada, você terá que validar a execução no destino.
    Requisição post assíncrona no PHP deve ser utilizada apenas quando não existe a necessidade de validar imediatamente o sucesso da execução de uma função,
     #https://rogertakemiya.com.br/fazendo-uma-requisicao-post-assincrona-no-php/
     Requisições JQUERY***
    Requisição síncrona
     $.ajax({
        url: 'script.php',
        async: false
    }).done(function(data) {
        alert(data);
    });
     Requisição assíncrona
        $.ajax({
            url: 'script.php',
            async: true
        }).done(function(data) {
            alert(data);
        });
    Arquivo script.php
    <?php
        // 5 seconds
        sleep(5);
         
        // Response
        echo "Hello";
    ?>
  */
    function asyncRequest($url, $payload) {
  
        foreach($payload as $key=>$value) $values[]="$key=".urlencode($value);
            $post_string=implode("&",$values);
     
     
     
        $parts=parse_url($url);
     
        $fp = fsockopen($parts['host'],
            isset($parts['port'])?$parts['port']:80,
            $errno, $errstr, 30);
     
        $out = "POST ".$parts['path']." HTTP/1.1\r\n";
        $out.= "Host: ".$parts['host']."\r\n";
        $out.= "Content-Type: application/x-www-form-urlencoded\r\n";
        $out.= "Content-Length: ".strlen($post_string)."\r\n";
        $out.= "Connection: Close\r\n\r\n";
        if (isset($post_string)) $out.= $post_string;
     
        fwrite($fp, $out);
        fclose($fp);
         
    }

    
    
    /**
     * SALVA IMAGEM 
     * @param  [type] $url [description]
     * @return [type]      [description]
     * @example $result = $this->Funcoes->getimg($result,'relatorio_medico','avatar_'.$cod_empresa_beneficiario.'.jpg'); [<description>]
     */
    function getimg($url, $pasta = 'relatorio_medico' ,$nome_imagem = 'teste.jpg') {     
        $headers[] = 'Accept: image/gif, image/x-bitmap, image/jpeg, image/pjpeg';              
        $headers[] = 'Connection: Keep-Alive';         
        $headers[] = 'Content-type: application/x-www-form-urlencoded;charset=UTF-8';         
        $user_agent = 'php';         
        $process = curl_init($url);         
        curl_setopt($process, CURLOPT_HTTPHEADER, $headers);         
        curl_setopt($process, CURLOPT_HEADER, 0);         
        curl_setopt($process, CURLOPT_USERAGENT, $user_agent);         
        curl_setopt($process, CURLOPT_TIMEOUT, 30);         
        curl_setopt($process, CURLOPT_RETURNTRANSFER, 1);         
        curl_setopt($process, CURLOPT_FOLLOWLOCATION, 1);         
        $return = curl_exec($process);         
        curl_close($process);    

        $caminho = 'img' . DS . 'uploads' . DS .$pasta. DS .$nome_imagem;
       
        file_put_contents($caminho,$return); 

        #$caminho = Router::url('/'.$caminho,true);
        $caminho = 'uploads' . DS .$pasta. DS .$nome_imagem;
        
        
        return $caminho;     
    } 

    function dias_entre_datas($data1,$data2){
        // converte as datas para o formato timestamp
        $d1 = strtotime($data1); 
        $d2 = strtotime($data2);
        // verifica a diferença em segundos entre as duas datas e divide pelo número de segundos que um dia possui
        $dataFinal = ($d2 - $d1) /86400;
        // caso a data 2 seja menor que a data 1
        if($dataFinal < 0)
        $dataFinal = $dataFinal * -1;
        
        return round($dataFinal);
    }

    
    /**
     * RETORNO DICIONÁRIO JSON PARA ARRAY 
     * conversão python dictionary in json php 
     * @param type $json_dic
     * @return type
     */
    function json_dic_py($json_dic){
        $json_dic = str_replace("{'",'{"',$json_dic);
        $json_dic = str_replace("':",'":',$json_dic);
        $json_dic = str_replace("', '",',"',$json_dic);
        #$json_dic = str_replace("'}",'}',$json_dic);
        $json_dic = str_replace("'",'',$json_dic);
        
        $phpArr = json_decode($json_dic, true);
        return $phpArr;
    }

    public function isBetween($x, $lower, $upper){
        return  ($lower <= $x && $x <= $upper);
    }

    /**
     * Data provável do parto
     */
    public function data_provavel_parto ($data_menstruacao){
        $data = $this->calculaData('Y-m-d H:i:s', $data_menstruacao, ' + 280 DAY');
        $data = explode(' ', $data);
        return $data[0];
    }

    /**
     * Dias prováveis para o parto
     */
    public function dias_para_parto($data_menstruacao){
        $data_atual = date('Y-m-d');
        $data_fim = $this->data_provavel_parto($data_menstruacao);
        

        #= + 280 dias para o parto
        $qtd_dias = $this->dias_entre_datas($data_atual,$data_fim);
        if($qtd_dias < 0){
            $return = 'Data da Última Menstruação: '.$this->dateToView($data_menstruacao).'<br>';
            $return .= 'Parto já realizado!';
        }else{
            $return = 'Data da Última Menstruação: '.$this->dateToView($data_menstruacao).'<br>';
            $return .= 'Data Provável: '.$this->dateToView($data_fim).'<br>';
            $return .= 'Semanas restantes: '.round($qtd_dias/7).'<br>';
            $return .= 'Dias restantes: '.$qtd_dias;
        }

        return $return;
        
    }

    /**
     * Periodo em semnas da gestação
     * $data_inicio = menstrucao
     * $data_fim
     */
    public function semanas_entre_datas($data_inicio,$data_fim){
        $qtd_dias = $this->dias_entre_datas($data_inicio,$data_fim);
        $semanas = round($qtd_dias/7);
        return $semanas;
    }

    /**
     * Grupo trimestral VH gestante
     */
    public function grupo_trimestral($data_menstruacao, $data_fim = '' ){
        if($data_fim == ''){
            $data_fim = date('Y-m-d');
        }
        $semanas = $this->semanas_entre_datas($data_menstruacao,$data_fim);

        if($this->isBetween($semanas, 0, 12))
            return 'Primeiro Trimestre';
        elseif($this->isBetween($semanas, 13, 30))
            return 'Segundo Trimestre';
        elseif($this->isBetween($semanas, 31, 42))
            return 'Terceiro Trimestre';
        else
		    return 'Pós Parto';

    }

    function bigintval($value) {
        $value = trim($value);
        if (ctype_digit($value)) {
          return $value;
        }
        $value = preg_replace("/[^0-9](.*)$/", '', $value);
        if (ctype_digit($value)) {
          return $value;
        }
        return 0;
      }
    
    public function formata_cnpj($cnpj = ''){
        $str = '';
        if($cnpj != ''){
            $str = preg_replace("/([0-9]{2})([0-9]{3})([0-9]{3})([0-9]{4})([0-9]{2})/", "$1.$2.$3/$4-$5", $cnpj);    
        }
        
        return $str;
    }

    public function formata_cpf($cpf = ''){
        $str = '';
        if($cpf != ''){
            $str = preg_replace("/([0-9]{3})([0-9]{3})([0-9]{3})([0-9]{2})/", "$1.$2.$3-$4", $cpf);    
        }
        
        return $str;
    }
    
}
?>
