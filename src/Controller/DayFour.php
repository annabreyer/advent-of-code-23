<?php declare(strict_types = 1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DayFour extends AbstractController
{

    #[Route('/day-four/1', name: 'day_four_one')]
    public function dayTwoPartOne(): Response
    {
        $sum       = 0;
        $lines     = file('data/DayFour/input.txt', FILE_IGNORE_NEW_LINES);
        $cardGames = [];

        foreach ($lines as $line) {
            $gameDetails = explode(':', $line);
            $gameNumber  = str_replace('Game ', '', $gameDetails[0]);

            $gameSets   = $this->formatGameData($gameDetails[1]);
            $gamePoints = $this->getGamePoints($gameSets);

            $sum += $gamePoints;
        }

        return new Response('Sum is ' . $sum . '.');
    }

    #[Route('/day-four/2', name: 'day_four_two')]
    public function dayTwoPartTwo(): Response
    {
        $sum   = 0;
        $lines = file('data/DayTwo/input.txt', FILE_IGNORE_NEW_LINES);

        foreach ($lines as $line) {

        }

        return new Response('Sum is ' . $sum . '.');

    }

    private function formatGameData(string $gameDetails): array
    {
        $gameSets = explode('|', $gameDetails);
        $gameSets = [
            explode(' ', $gameSets[0]),
            explode(' ', $gameSets[1]),
        ];

        foreach ($gameSets as $setKey => $set) {
            foreach ($set as $key => $number) {
                if (1 === preg_match('/[0-9]/', $number)) {
                    $gameSets[$setKey][$key] = (int)$number;
                } else {
                    unset($gameSets[$setKey][$key]);
                }
            }

            $gameSets[$setKey] = array_values($gameSets[$setKey]);
        }

        return $gameSets;
    }

    private function getGamePoints(array $gameSets)
    {
        $gamePoints = 0;
        foreach ($gameSets[0] as $number) {
            if (in_array($number, $gameSets[1])) {
                if ($gamePoints >= 1) {
                    $gamePoints = $gamePoints * 2;
                } else {
                    $gamePoints = 1;
                }
            }
        }

        return $gamePoints;
    }

}
