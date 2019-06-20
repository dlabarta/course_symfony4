<?php
namespace App\Twig;

use Twig\Extension\RuntimeExtensionInterface;

class PriceRuntime implements RuntimeExtensionInterface
{
    public function formatPriceDolar($number, $decimals = 0, $decPoint = '.', $thousandsSep = ',')
    {
        $price = number_format($number, $decimals, $decPoint, $thousandsSep);
        $price = '$ '.$price ;

        return $price;
    }

    public function formatPriceEuro($number, $decimals = 0, $decPoint = '.', $thousandsSep = ',')
    {
        $price = number_format($number, $decimals, $decPoint, $thousandsSep);
        $price = $price . ' €';

        return $price;
    }
}
