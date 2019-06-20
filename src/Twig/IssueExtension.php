<?php
namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class IssueExtension extends AbstractExtension
{
    public function getFunctions()
    {
        return [
            new TwigFunction('issue_has_priority', [IssueRuntime::class, 'hasPriority']),
        ];
    }
}
