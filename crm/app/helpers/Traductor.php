<?php
class Traductor {
    public static function traducir($texto) {
        $url = 'https://api.mymemory.translated.net/get?q=' . urlencode($texto) . '&langpair=es|en';
        $respuesta = @file_get_contents($url);

        if ($respuesta) {
            $datos = json_decode($respuesta, true);
            if ($datos && $datos['responseStatus'] == 200) {
                return $datos['responseData']['translatedText'];
            }
        }

        // si falla devuelvo el texto original
        return $texto;
    }
}
