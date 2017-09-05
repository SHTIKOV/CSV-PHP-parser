<?php
    //------------------------------------------------------------
    // Функция парсера CSV-файла
    //------------------------------------------------------------
    // На входе: $file_name - имя файла для парсинга
    //           $separator - разделитель полей, по умолчанию ';'
    //           $quote - ограничитель строк, по умолчанию '"'
    // На выходе: массив значений всего файла
    //------------------------------------------------------------
    function fuck_csv($file_name, $separator=';', $quote='"') {
        // Загружаем файл в память целиком
        $f=fopen($file_name,'r');
        $str=fread($f,filesize($file_name));
        fclose($f);
     
        // Убираем символ возврата каретки
        $str=trim(str_replace("\r",'',$str))."\n";
     
        $parsed=Array();    // Массив всех строк
        $i=0;               // Текущая позиция в файле
        $quote_flag=false;  // Флаг кавычки
        $line=Array();      // Массив данных одной строки
        $varr='';           // Текущее значение
     
        while($i<=strlen($str)) {
            // Окончание значения поля
            if ($str[$i]==$separator && !$quote_flag) {
                $varr=str_replace("\n","\r\n",$varr);
                $line[]=$varr;
                $varr='';
            }
            // Окончание строки
            elseif ($str[$i]=="\n" && !$quote_flag) {
                $varr=str_replace("\n","\r\n",$varr);
                $line[]=$varr;
                $varr='';
                $parsed[]=$line;
                $line=Array();
            }
            // Начало строки с кавычкой
            elseif ($str[$i]==$quote && !$quote_flag) {
                $quote_flag=true;
            }
            // Кавычка в строке с кавычкой
            elseif ($str[$i]==$quote && $str[($i+1)]==$quote && $quote_flag) {
                $varr.=$str[$i];
                $i++;
            }
            // Конец строки с кавычкой
            elseif ($str[$i]==$quote && $str[($i+1)]!=$quote && $quote_flag) {
                $quote_flag=false;
            }
            else {
                $varr.=$str[$i];
            }
            $i++;
        }
        return $parsed;
    }

