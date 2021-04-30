<?php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use App\Service\CsvParser;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CsvController extends AbstractController
{
    /*
     * read function
     * Scope: Public
     * Params: NULL
     * Return: Response
     */
	 public function read(): Response {
        $csvParser = new CsvParser("dataset.csv", $this->getParameter('kernel.project_dir'));
        $csvParser->csvToDataset();
        
        echo $csvParser->getCountOfUsersWithinScoreRange(20, 50);
        echo $csvParser->getCountOfUsersWithinScoreRange(-40, 0);
        echo $csvParser->getCountOfUsersWithinScoreRange(0, 80);
        echo $csvParser->getCountOfUsersByCondition('CA', 'w', false, false);
        echo $csvParser->getCountOfUsersByCondition('CA', 'w', false, true);
        echo $csvParser->getCountOfUsersByCondition('CA', 'w', true, true);
        
        return new Response(
            ''
        );
    }
}
