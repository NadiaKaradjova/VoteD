<?php

namespace App\Command;


use App\Entity\Article;
use App\Entity\Product;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ArticleImport extends ContainerAwareCommand
{

    protected function configure()
    {
        $this->setName('import:articles')
            ->addArgument('file', InputArgument::REQUIRED)
            ->addArgument('start_from', InputArgument::REQUIRED);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $startTime = microtime(true);
        $doctrine = $this->getContainer()->get('doctrine');

        ini_set('memory_limit', -1);
        ini_set('max_execution_time', 60000);

        $output->writeln('start read files ' . (string)(microtime(true) - $startTime));

        $fileName = $input->getArgument('file');

        $file = $this->getContainer()->getParameter('kernel.project_dir') . "/src/Command/Files/" . $fileName;

        $a = file_get_contents($file);
        $xmlFIle = new \SimpleXMLElement($a);

        $start = $input->getArgument('start_from');

        $result = [];
        $arrDelete = [];
        foreach ($xmlFIle as $medication) {

            $number = (int)$medication->PHARMACODE;

            $forDelete = filter_var((string)$medication->DEL, FILTER_VALIDATE_BOOLEAN);
            $BG = (string)$medication->BG;
            $CLINCD = (string)$medication->CLINCD;
            $HOSPCD = (string)$medication->HOSPCD;
            $ARTTYPE = (int)$medication->ARTTYP;

            if ($BG == 'Y' || $CLINCD == 'Y' || $HOSPCD == 'Y' || $ARTTYPE == 4) {
                continue;
            }

            if ($forDelete === true) {
                //$arrDelete[] = $number;
                continue;
            }

            $result[] = $number;
        }


//        $output->writeln(count($result));
//        die;

        $lenght = 2000;
        $result = array_slice($result, $start, $lenght);
        $count = 0;
        $countRealImportArticles = 0;
        foreach ($result as $number) {
            $count++;
            $arr = [];
            foreach ($xmlFIle as $medication) {
                if ($number == (int)$medication->PHARMACODE) {
                    $arr[] = $medication;
                }
            }
            $basic = $arr[0];
            //$output->writeln("Article number: " .$number);
            $drug = $doctrine->getRepository(Product::class)->find((int)$basic->PRDNO);

            if (!$drug) {
                $output->writeln($count . " - Article number: " . $number . " No drug");
                continue;
            }

            /** @var Article $article */
            $article = $doctrine->getRepository(Article::class)->findOneBy(['id' => $number]);

            if (!$article) {
                $article = new Article();
            } else {
                $article->setUpdateOn(new \DateTime());
            }

            $article->setId($number);

            //$article->setProductNumber((int)$basic->PRDNO);
            $article->setProduct($drug);
            $article->setQty($basic->QTY);
            $article->setType($basic->ARTTYP);


            $article->setDesctription((string)$basic->DSCRLONGD);
            $article->setUnit((string)$basic->QTYUD);
            $article->setOther(json_encode($basic));


            //$this->setArticlePicture($article, $number);

            $doctrine->getManager()->persist($article);
            $doctrine->getManager()->flush();
            $countRealImportArticles++;
            $output->writeln($count . " - Article number: " . $number);
        }
        $next = $start + $lenght;
        $output->writeln("Real import count: " . $countRealImportArticles);
        $string = "Time from " . $start . " for $lenght articles: " . (string)(microtime(true) - $startTime . ". Next start value: $next");
        $output->writeln($string);
        file_put_contents('log.txt', $string . PHP_EOL, FILE_APPEND);
    }

}