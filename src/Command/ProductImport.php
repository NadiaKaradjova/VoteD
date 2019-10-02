<?php

namespace App\Command;

use App\Entity\PharmaCompany;
use App\Entity\Product;
use App\Constansts\Constants;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;


class ProductImport extends ContainerAwareCommand
{

    protected function configure()
    {
        $this->setName('import:products')
            ->addArgument('file', InputArgument::REQUIRED)
            ->addArgument('start_from', InputArgument::REQUIRED);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $doctrine = $this->getContainer()->get('doctrine');
        $startTime = microtime(true);
        $output->writeln('start read files ' . (string)(microtime(true) - $startTime));

        ini_set('memory_limit', -1);
        ini_set('max_execution_time', 60000);

        $fileName = $input->getArgument('file');
        $file = $this->getContainer()->getParameter('kernel.project_dir') . "/src/Command/Files/" . $fileName;

        $a = file_get_contents($file);
        $xmlFIle = new \SimpleXMLElement($a);

        $result = [];
        $arrDelete = [];

        foreach ($xmlFIle as $medication) {
            $number = (int)$medication->PRDNO;

//            if (!(string)$medication->FORMD) {
//                continue;
//            }


            $forDelete = filter_var((string)$medication->DEL, FILTER_VALIDATE_BOOLEAN);
            $shortName = strtolower((string)$medication->ADNAMD);
            $description = (string)$medication->DSCRD;

            if (preg_match('/' . implode("|", Constants::DESCRIPTION).'\b/', $description)) {
                continue;
            }

            if ($forDelete === true || (string)$medication->TRADE == "aH" || preg_match('/' . implode("|", Constants::SHORTNAME).'\b/', $shortName)) {
                continue;
            }

            $result[] = (int)$number;
        }


        //$result = array_unique($result);
        //$output->writeln(count($result));
        $start = $input->getArgument('start_from');

        $lenght = 5000;
        $result = array_slice($result, $start, $lenght);

        $count = 0;
        foreach ($result as $number) {
            $arr = [];
            foreach ($xmlFIle as $medication) {
                if ($number == (int)$medication->PRDNO) {
                    $arr[] = $medication;
                }
            }

            //$output->writeln($count . ' - Number: ' . $number);
            $drug = $doctrine->getRepository(Product::class)->find($number);
            $count++;
            if (!$drug) {
                $drug = new Product($number);
            } else {
                continue;
            }

            $basic = $arr[0];

            //$drug->setProductNumber($number);
            $drug->setDose($basic->DOSE);
            $drug->setDoseUnit($basic->DOSEU);

            if ($companyId = (int)$basic->PRTNO) {
                /** @var PharmaCompany $company */
                $company = $doctrine->getRepository(PharmaCompany::class)->find($companyId);
                $drug->setCompany($company);
            }

            $drug->setDesctription($basic->DSCRD);
            $drug->setLongDesctription($basic->DSCRLONGD);
            $drug->setForm($basic->FORMD);
            $drug->setShortNamePart($basic->ADNAMD);
            $drug->setLongNamePart($basic->ADNAMLONGD);
            $drug->setTrade($basic->TRADE);
            $drug->setOther(json_encode($basic));


            $doctrine->getManager()->persist($drug);
            $doctrine->getManager()->flush();

            //$output->writeln($number);
        }


        $next = $start + $lenght;
        $string = "Time from " . $start . " for $lenght articles: " . (string)(microtime(true) - $startTime . ". Next start value: $next");
        $output->writeln($string);

    }
}