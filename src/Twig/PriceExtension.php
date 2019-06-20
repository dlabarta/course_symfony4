<?php
namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class PriceExtension extends AbstractExtension
{
    public function getFilters()
    {
        return [
            new TwigFilter('price_dolar', [PriceRuntime::class, 'formatPriceDolar']),
            new TwigFilter('price_euro', [PriceRuntime::class, 'formatPriceEuro']),
        ];
    }
}
