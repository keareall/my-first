<?php

if (isset($_POST['text'])) {
    //принимаем параметр "text"
    $str = $_POST['text'];

//внимание! на русском языке строки являются многобайтвыми

    $str = htmlspecialchars($str);
    //заменим в строке пробел на "пусто", т.е. уберем пробелы
    $str = mb_ereg_replace('\ ', '', $str);
    //все буквы переведем в нижний регистр, чтобы сделать поиск регистронезависимым
    $str = mb_strtolower($str);
    //превращаем строку в массив для его перебора
    $arr = mbStringToArray($str);
    //ключ первого сравниваемого символа (для перебора)
    $i = 0;
    //палиндром
    $palindrome = false;
    //найденное совпадение
    $match = false;
    //центр симметрии полиндрома
    $centralSymbol = false;
    //счетчик найденных полиндромов
    $palindromeCounter = 0;
    //ключ второго сравнимаего символа (для перебора)
    $lastSymbol = count($arr) - 1;
    //ответ
    $answer = false;

    findPalindrome();

}

//функция переворачивание многобайтовой строки аналогичная strrev
function mb_strrev($str)
{
    $r = '';
    for ($i = mb_strlen($str); $i >= 0; $i--) {
        $r .= mb_substr($str, $i, 1);
    }
    return $r;
}

//функция для преобразования строки в массив аналогичная str_split
function mbStringToArray($string)
{
    $array = array();
    $strlen = mb_strlen($string);
    while ($strlen) {
        $array[] = mb_substr($string, 0, 1, "UTF-8");
        $string = mb_substr($string, 1, $strlen, "UTF-8");
        $strlen = mb_strlen($string);
    }
    return $array;
}

//функция для преобразования первой буквы в верхний регистр аналогичная ucfirst
function my_mb_ucfirst($str) {
    $fc = mb_strtoupper(mb_substr($str, 0, 1));
    return $fc.mb_substr($str, 1);
}


function findPalindrome()
{
    //добавляем в область видимости переменные вне функции
    global $str, $arr, $i, $palindrome, $match, $centralSymbol, $palindromeCounter, $lastSymbol, $answer;

    //если длина строки меньше и равно одного символа искать палиндром не нужно
    if (mb_strlen($str) <= 1) {
        if ($palindromeCounter > 0) {
            if ($palindromeCounter == 1) {
                echo 'Найден ' . '<span id="number">' . $palindromeCounter . '</span>' . ' палиндром<hr><br>' . $answer;
            }
            else {
                echo 'Найдено ' . '<span id="number">' . $palindromeCounter . '</span>' . ' палиндрома<hr><br>' . $answer;
            }
        }
        else {
            echo 'Не найдено ни одного палиндрома<hr>';
        }
    } //в противном случае - ищем палиндром
    else {
        //ищем совпадение первого и последнего символа при переборе строки
        if ($arr[$i] == $arr[$lastSymbol]) {
            //если в финальном переборе два соседних символа равны
            if ($i + 1 == $lastSymbol) {
                $match .= $arr[$i];
                //если внутри палиндрома содержатся полиндромы, то выведем их всех
                $j = 1;
                while ($j < mb_strlen($match)) {
                    $half = mb_substr($match, -$j);
                    $palindromePart = $half . mb_strrev($half);
                    $palindromeCounter++;
                    $answer .= '<span id="number">' . $palindromeCounter . '</span>' . '. ' . my_mb_ucfirst($palindromePart) . '<br>';
                    $j++;
                }
                //выведем итоговый полиндром
                $palindrome = $match . mb_strrev($match);
                $palindromeCounter++;
                $answer .= '<span id="number">' . $palindromeCounter . '</span>' . '. ' . my_mb_ucfirst($palindrome) . '<br>';
                $str = mb_ereg_replace($palindrome, '', $str);
                $arr = mbStringToArray($str);
                $i = 0;
                $lastSymbol = count($arr) - 1;
                $match = false;
                findPalindrome();

            } //если в финальном переборе в середине полиндрома находится символ являющийся его центром
            elseif ($i + 2 == $lastSymbol) {
                $centralSymbol = $arr[$i + 1];
                $match .= $arr[$i];
                //если внутри палиндрома содержатся полиндромы, то выведем их всех
                $j = 1;
                while ($j < mb_strlen($match)) {
                    $half = mb_substr($match, -$j);
                    $palindromePart = $half . $centralSymbol . mb_strrev($half);
                    $palindromeCounter++;
                    $answer .= '<span id="number">' . $palindromeCounter . '</span>' . '. ' . my_mb_ucfirst($palindromePart) . '<br>';
                    $j++;
                }
                //выведем итоговый полиндром
                $palindrome = $match . $centralSymbol . mb_strrev($match);
                $palindromeCounter++;
                $answer .= '<span id="number">' . $palindromeCounter . '</span>' . '. ' . my_mb_ucfirst($palindrome) . '<br>';
                $str = mb_ereg_replace($palindrome, '', $str);
                $arr = mbStringToArray($str);
                $i = 0;
                $lastSymbol = count($arr) - 1;
                $match = false;
                findPalindrome();
            } //продолжаем перебор
            else {
                //если совпадение до этого было найдено, то добавляем его в строку совпадений
                if ($match !== false) {
                    $match .= $arr[$i];
                    $i++;
                    $lastSymbol--;
                    findPalindrome();
                } //до этого не было совпадение, создаем строку совпадений и продолжаем поиск
                else {
                    $match = '';
                    $match = $arr[$i];
                    $i++;
                    $lastSymbol--;
                    findPalindrome();
                }
            }
        } //не найдено совпадение первого и последнего символа при переборе строки
        else {
            //если сравниваются уже смежные символы
            if ($i + 1 == $lastSymbol) {
                //перебор закончился, все символы проверены
                if ($lastSymbol == count($arr) - 1) {
                    if ($palindromeCounter > 0) {
                        if ($palindromeCounter == 1) {
                            echo 'Найден ' . '<span id="number">' . $palindromeCounter . '</span>' . ' палиндром<hr><br>' . $answer;
                        }
                        else {
                            echo 'Найдено ' . '<span id="number">' . $palindromeCounter . '</span>' . ' палиндрома<hr><br>' . $answer;
                        }                    } else {
                        echo 'Не найдено ни одного палиндрома<hr>';
                    }
                } //продолжаем перебор
                else {
                    $lastSymbol = count($arr) - 1;
                    $i++;
                    $match = false;
                    findPalindrome();
                }
            } //если прошлое совпадение оказалось случайным, уменьшаем диапозон поиска
            elseif ($match !== false) {
                $i--;
                $match = false;
                findPalindrome();
            } //уменьшаем диапозон поиска
            else {
                $lastSymbol--;
                $match = false;
                findPalindrome();
            }
        }
    }
}

