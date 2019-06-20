<?php

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;

class CodeGenerator
{
    protected $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function geCode($entity, $object)
    {
        $code = null;
        $year =  $object->getCreatedAt()->format('Y');

        $repositoy = $this->em->getRepository($entity);
        $last= $repositoy->getLastByYear($year);
        $number = 0;
        if ($last) {
            $number = $last->getNumber();
        }
        ++$number;
        $code = $number.'/'.$year;

        $object->setNumber($number);
        $object->setCode($code);

        return $object;
    }
}
