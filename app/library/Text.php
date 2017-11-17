<?php


class Text {
    public static function removeExtraSpaces($text){
        return preg_replace('/\s+/', ' ', trim($text));
    }
} 