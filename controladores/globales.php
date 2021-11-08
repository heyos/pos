<?php

class Globales{

    //crear una contraseña segura
    public static function crypt_blowfish($password,$digito = 7) {
        
        $password = self::unEspacio($password);

        $set_salt = './1234567890ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
        $salt = sprintf('$2a$%02d$', $digito);

        for($i = 0; $i < 22; $i++){

            $salt .= $set_salt[mt_rand(0, 22)];
        }

        return crypt($password, $salt);
    }

    //limpiar informacion de los formularios en el servidor
    public static function sanearData($string){

        $string = trim($string);
        $string = self::unEspacio($string);
        $string = stripcslashes($string);
        $string = htmlspecialchars($string);

        $string = str_replace(
            array('á', 'à', 'ä', 'â', 'ª', 'Á', 'À', 'Â', 'Ä'),
            array('a', 'a', 'a', 'a', 'a', 'A', 'A', 'A', 'A'),
            $string
        );

        $string = str_replace(
            array('é', 'è', 'ë', 'ê', 'É', 'È', 'Ê', 'Ë'),
            array('e', 'e', 'e', 'e', 'E', 'E', 'E', 'E'),
            $string
        );

        $string = str_replace(
            array('í', 'ì', 'ï', 'î', 'Í', 'Ì', 'Ï', 'Î'),
            array('i', 'i', 'i', 'i', 'I', 'I', 'I', 'I'),
            $string
        );

        $string = str_replace(
            array('ó', 'ò', 'ö', 'ô', 'Ó', 'Ò', 'Ö', 'Ô'),
            array('o', 'o', 'o', 'o', 'O', 'O', 'O', 'O'),
            $string
        );

        $string = str_replace(
            array('ú', 'ù', 'ü', 'û', 'Ú', 'Ù', 'Û', 'Ü'),
            array('u', 'u', 'u', 'u', 'U', 'U', 'U', 'U'),
            $string
        );

        $string = str_replace(
            array('ñ', 'Ñ', 'ç', 'Ç'),
            array('n', 'N', 'c', 'C'),
            $string
        );

        //Esta parte se encarga de eliminar cualquier caracter extraño
        $string = str_replace(
            array("\\", "¨", "º", "~","'",
                 "#", "|", "!", "\"",
                 "·", "$", "%", "&",
                 "(", ")", "?", "'", "¡",
                 "¿", "^", "`","+", "¨", "´",
                 ">", "< ", ";", ",", ":","/","*"
                 ),
            '',
            $string
        );
        return $string;
    }

    public static function unEspacio($string){
        
        $array = explode(' ', $string);
        $newArray = array();
        $salida = '';

        foreach ($array as $key => $value) {

            if($value != ''){
                $newArray [] = $value;
            }
        }

        foreach ($newArray as $key => $value) {
            
            $salida .= $value.' ';
        }

        $salida = trim($salida);

        return $salida;
    }

    public static function full_copy($origen,$carpetaOrigen,$carpetaDestino){

        $files = glob($origen.'/*.php');

        $destino = "../../../views/modules/".$carpetaDestino;

        if(!file_exists($destino)){
            mkdir($destino,0777, true);
        }

        foreach ($files as $file) {

            $dest = str_replace($carpetaOrigen, $carpetaDestino, $file);
            copy($file, $dest);
        }

    }

    public static function nombre_dia($dia){
        $str = '';

        switch ($dia) {
            case 0:
                $str = 'Domingo';
                break;

            case 1:
                $str = 'Lunes';
                break;

            case 2:
                $str = 'Martes';
                break;

            case 3:
                $str = 'Miercoles';
                break;

            case 4:
                $str = 'Jueves';
                break;

            case 5:
                $str = 'Viernes';
                break;

            case 6:
                $str = 'Sabado';
                break;

            case 8:
                $str = "Todos";
                break;
            
            default:
                $str = 'No definido';
                break;
        }

        return $str;
    }

}
