<?php

namespace App\Command;

use App\Entity\PharmaCompany;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class PharmaCompanyImport extends ContainerAwareCommand
{

    protected function configure()
    {
        $this->setName('import:company')
        ->addArgument('file', InputArgument::REQUIRED);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $comps = [
            "%AMAVITA%",
            "%coop vitality%"
        ];

        $doctrine = $this->getContainer()->get('doctrine');


        ini_set('memory_limit', -1);
        ini_set('max_execution_time', 30000);

        $fileName = $input->getArgument('file');
        $file = $this->getContainer()->getParameter('kernel.project_dir') . "/src/Command/Files/" . $fileName;

        $a = file_get_contents($file);

        $xmlFIle = new \SimpleXMLElement($a);

        $arrDelete = [];

        foreach ($xmlFIle as $newCompany) {

            $name = strtolower((string)$newCompany->NAMS);

            if (preg_match('/\amavita\b/', $name) || preg_match('/\coop vitality\b/', $name)) {

                continue;
            }

            $output->writeln((string)$newCompany->NAMS);

            $forDelete = filter_var((string)$newCompany->DEL, FILTER_VALIDATE_BOOLEAN);

            if ($forDelete === true) {
                $arrDelete[] = (int)$newCompany->PRTNO;
                continue;
            }

            $company = $doctrine->getRepository(PharmaCompany::class)->findOneBy(['id' => (int)$newCompany->PRTNO]);

            if (!$company ) {
                $company = new PharmaCompany((int)$newCompany->PRTNO);
            }

            $company->setName((string)$newCompany->NAMS);
            $company->setAdditionalName((string)$newCompany->ADNAM);
            $company->setStreet((string)$newCompany->STRT);
            $company->setAdditionalInfo((string)$newCompany->APPENDIX);
            $company->setZip((string)$newCompany->ZIP);
            $company->setCity((string)$newCompany->CITY);
            $company->setCountry((string)$newCompany->CNTRY);
            $company->setSite((string)$newCompany->WWW);
            $company->setPhone((string)$newCompany->TEL);
            $company->setFax((string)$newCompany->FAX);
            $company->setEmail((string)$newCompany->EMAIL);
            $company->setContactPerson((string)$newCompany->CNTCT);

            $doctrine->getManager()->persist($company);
            $doctrine->getManager()->flush();
        }
    }


}