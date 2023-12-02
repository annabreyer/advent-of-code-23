<?php declare(strict_types = 1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DayOne extends AbstractController
{
    private const NUMBERS = [
        'one'   => 1,
        'two'   => 2,
        'three' => 3,
        'four'  => 4,
        'five'  => 5,
        'six'   => 6,
        'seven' => 7,
        'eight' => 8,
        'nine'  => 9,
    ];

    #[Route('/day-one', name: 'day-one')]
    public function DayOne(): Response
    {
        $lines = file('data/DayOne/input.txt', FILE_IGNORE_NEW_LINES);
        $sum   = 0;

        foreach ($lines as $line){
            $matches    = $this->findNumbers($line);
            $firstDigit = $matches[0];
            $lastDigit  = $matches[count($matches) - 1];
            $number     = $firstDigit . '' . $lastDigit;

            $sum += (int)$number;
        }

        return new Response('Sum is '. $sum . '.');
    }



    private function findNumbers(string $line): array
    {
        $matches = [];
        foreach (self::NUMBERS as $number => $digit) {
            $firstNumber = strpos($line, $number);
            $firstDigit  = strpos($line, (string)$digit);
            $lastNumber  = strrpos($line, $number);
            $lastDigit   = strrpos($line, (string)$digit);

            if (false !== $firstNumber) {
                $matches = $matches + [$firstNumber => $digit];
            }

            if (false !== $firstDigit) {
                $matches = $matches + [$firstDigit => $digit];
            }

            if (false !== $lastNumber) {
                $matches = $matches + [$lastNumber => $digit];
            }

            if (false !== $lastDigit) {
                $matches = $matches + [$lastDigit => $digit];
            }
        }

        ksort($matches);
        $matches = array_values($matches);

        return $matches;
    }
}
