<?php
namespace App\Controllers;


class QuadraticController
{

    public static function check_input($text){
        $arr = explode(" ", $text);
        if (count($arr) !== 3) {
            return false;
        }
        $num_arr = [];
        foreach ($arr as $num){
            if (is_numeric($num)){
                array_push($num_arr, (int)$num);
            } else {
                return false;
            }
        }
        return true;
    }


    public static function get_coefs($text){
        $arr = explode(" ", $text);
        $num_arr = [];
        foreach ($arr as $num){
            array_push($num_arr, (int)$num);
        }
        return $num_arr;
    }


    public static function in_real_numbers($coef){
        return ($coef[1]*$coef[1] - 4 * $coef[0] * $coef[2] < 0) ? false: true;
    }

    
    public static function solve($coef){
        return [
            (-1 * $coef[1] + sqrt($coef[1]*$coef[1] - 4 * $coef[0] * $coef[2]))/(2 * $coef[0]),
            (-1 * $coef[1] - sqrt($coef[1]*$coef[1] - 4 * $coef[0] * $coef[2]))/(2 * $coef[0])
        ];
    }


    public static function respond($roots){
        return sprintf("Уравнение решено.\nx1 = %f\nx2 = %f", floatval($roots[0]), floatval($roots[1]));
    }

}

