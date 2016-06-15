<?php

namespace app\helpers;

use yii\helpers\BaseStringHelper;

class StringHelper extends BaseStringHelper
{
    /**
     * Функция возвращает диапазон символов от начального до конечного, например от А до Я.
     * @param $start string
     * @param $end string
     * @return array
     */
    public static function mb_range($start, $end)
    {
        if ($start == $end) {
            return array($start);
        }

        $_result = array();
        // Получаем символы в юникоде
        list(, $_start, $_end) = unpack("N*", mb_convert_encoding($start . $end, "UTF-32BE", "UTF-8"));
        // Определяем направление смещения
        $_offset = $_start < $_end ? 1 : -1;
        $_current = $_start;
        while ($_current != $_end) {
            $_result[] = mb_convert_encoding(pack("N*", $_current), "UTF-8", "UTF-32BE");
            $_current += $_offset;
        }
        $_result[] = $end;
        return $_result;
    }

    /**
     * Функция, позволяющая получить десятичный код символа в юникод
     * @param $char string
     * @return integer
     */
    public static function decimalCodeUnicodeChar($char)
    {
        return unpack("N*", mb_convert_encoding($char, "UTF-32BE", "UTF-8"))[1];
    }

    /**
     * Функция, позволяющая найти символ юникод по его десятичному коду.
     * @param $code integer
     * @return string
     */
    public static function UnicodeCharFromDecimal($code)
    {
        return mb_convert_encoding(pack("N*", $code), "UTF-8", "UTF-32BE");
    }
}