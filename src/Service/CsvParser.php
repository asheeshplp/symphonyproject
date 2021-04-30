<?php
/*
 * CSV Parser class
*/
namespace App\Service;

use App\Service\ScoreDataIndexerInterface;
use Symfony\Component\Filesystem\Filesystem;


class CsvParser implements ScoreDataIndexerInterface
{
    public $filename = ""; //Declare initial file name
    public $filepath = ""; //Declare initial file path
    private $data = [];
    public $scoreRangeMinValue = -100;
    public $scoreRangeMaxValue = 100;
    public $ageMinValue = 0;
    public $ageMaxValue = 100;

    /*
     * Intial construct function
     * Scope: Public
     * Params: Filename, FilePath
     */
    public function __construct(string $filename, string $filepath)
    {
        $this->filename = $filename;
        $this->filepath = $filepath;
    }
	/*
     * csvToDataset function
     * Scope: Public
     * Params: NULL
     * Return: Void
     */
    public function csvToDataset(): void
    {
        $filesystem = new Filesystem();
        $file = $this->filepath . '/public/csvfiles/' . $this->filename;
        if ($filesystem->exists($file)) {            
            $header = NULL;
            if (($handle = fopen($file, 'r')) !== FALSE) {
                while (($row = fgetcsv($handle, 0, ";")) !== FALSE) {
                    if (!$header)
                        $header = $row;
                    else
                        $this->data[] = array_combine($header, $row);
                }
                fclose($handle);
            }
        } else {
            $this->data = [];
        }
    }
	/*
     * getData function
     * Scope: Public
     * Params: NULL
     * Return: Data array
     */
    public function getData()
    {
        return $this->data;
    }
	/*
     * getCountOfUsersWithinScoreRange function
     * Scope: Public
     * Params: rangeStart, rangeEnd
     * Return: Number of users
     */
    public function getCountOfUsersWithinScoreRange(int $rangeStart, int $rangeEnd): int {
        if (empty($this->data)) {
            return 0;
        }
        if ($rangeEnd < $rangeStart) {
            // If Range end is less than $rangeStart
            return 0;
        }
        $noOfUsers = 0;
        foreach ($this->data as $data) {
            if ($data['Score'] >= $rangeStart && $data['Score'] <= $rangeEnd) {
                $noOfUsers++;
            }
        }
        return $noOfUsers;
    }
	/*
     * getCountOfUsersByCondition function
     * Scope: Public
     * Params: region, gender, hasLegalAge, hasPositiveScore
     * Return: Number of users
     */
    public function getCountOfUsersByCondition(string $region, string $gender, bool $hasLegalAge, bool $hasPositiveScore
    ): int {
        if (empty($this->data)) {
            return 0;
        }
        $noOfUsers = 0;
        foreach ($this->data as $data) {
            if ($data['Region'] == $region && $data['Gender'] == $gender) {
                $ageResult = $hasLegalAge ? $data['Age'] >= 18 : $data['Age'] < 18;
                $scoreResult = $hasPositiveScore ? $data['Score'] > 0 : $data['Score'] < 0;   
                if($ageResult && $scoreResult){
                    $noOfUsers++;
                }
            }
        }
        return $noOfUsers;
    }
}
