<?php

namespace App\Repository;


class ProductRepository extends \Doctrine\ORM\EntityRepository
{

    public function searchByString($string)
    {
        $param['string'] = '%' . $string . '%';


        $medicaments = $this->createQueryBuilder("m")

            ->select("m.productNumber, m.longDesctription as name, m.form as packageSize, m.dose as quantity, m.doseUnit as unit")
            ->where('m.longDesctription LIKE :string')
            ->setParameters($param)
            ->setMaxResults(10)
            ->getQuery()
            ->execute();

        return $medicaments;
    }
}