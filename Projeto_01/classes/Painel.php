<?php

    class Painel {
        
        public static function logado() {
            return isset($_SESSION['login']) ? true : false;
        }
        
        public static function logout() {
            session_destroy();
            header('Location: ' . INCLUDE_PATH_PAINEL);
        }
        
        public static function carregarPagina() {
            if(isset($_GET['url'])) {
                $url = explode('/', $_GET['url']);
                if(file_exists('pages/'.$url[0].'.php')) {
                    include('pages/'.$url[0].'.php');
                } else {
                    header('Location: ' . INCLUDE_PATH_PAINEL);
                }
            } else {
                include('pages/home.php');
            }
        }
        
        public static function listarUsuariosOnline() {
            self::limparUsuariosOnline();
            $sql = MySql::conectar()->prepare("SELECT * FROM `tb_admin.online`");
            $sql->execute();
            return $sql->fetchAll();
        }
        
        public static function limparUsuariosOnline() {
            $date = date('Y-m-d H:i:s');
            $sql = MySql::conectar()->exec("DELETE FROM `tb_admin.online` WHERE ultima_acao < '$date' - INTERVAL 1 MINUTE");
        }
        
        public static function alert($tipo, $mensagem) {
            if($tipo == 'sucesso') {
                echo '<div class="box-alert sucesso"><i class="fas fa-check"></i> '. $mensagem .'</div>';
            } else if($tipo == 'erro') {
                echo '<div class="box-alert erro"><i class="fas fa-times"></i> '. $mensagem .'</div>';
                
            }
        }
        
        public static function imagemValida($imagem) {
            if($imagem['type'] == 'image/jpeg' ||
               $imagem['type'] == 'image/jpg' ||
               $imagem['type'] == 'image/png') {
                
                $tamanho = intval($imagem['size']/1024);
                if($tamanho < 300)
                    return true;
                else
                    return false;
            } else {
                return false;
            }
        }
        
        public static function uploadFile($file) {
            if(move_uploaded_file($file['tmp_name'], BASE_DIR_PAINEL.'/uploads/'.$file['name'])) {
                return $file['name'];
            } else {
                return false;
            }
        }
        
        public static function deleteFile($file) {
            @unlink('uploads/'.$file);
        }
    }
    
?>