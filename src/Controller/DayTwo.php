<?php declare(strict_types = 1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DayTwo extends AbstractController
{
    private const RED = 'red';
    private const GREEN = 'green';
    private const BLUE = 'blue';

    #[Route('/day-two/1', name: 'day_two_one')]
    public function dayTwoPartOne(): Response
    {
        $sum   = 0;
        $lines = file('data/DayTwo/input.txt', FILE_IGNORE_NEW_LINES);

        foreach ($lines as $line) {
            $gameDetails = explode(':', $line);
            $gameNumber  = str_replace('Game ', '', $gameDetails[0]);
            $gameData    = $this->linesGameToArray($gameDetails[1]);

            if ($this->gameIsPossible($gameData)) {
                $sum += (int)$gameNumber;
            }
        }

        return new Response('Sum is '. $sum . '.');
    }
    #[Route('/day-two/2', name: 'day_two_two')]
    public function dayTwoPartTwo(): Response
    {
        $sum   = 0;
        $lines = file('data/DayTwo/input.txt', FILE_IGNORE_NEW_LINES);

        foreach ($lines as $line) {
            $gameDetails = explode(':', $line);
            $gameData    = $this->linesGameToArray($gameDetails[1]);
            $minSet = $this->getMinimumSetForGame($gameData);
            $powerOfMinSet = $this->getPowerOfSet($minSet);

            $sum += $powerOfMinSet;
        }

        return new Response('Sum is '. $sum . '.');

    }

    private function linesGameToArray(string $line)
    {
        $gameResults = explode(';', $line);

        foreach ($gameResults as $setNumber => $set) {
            $set = explode(',', $set);
            foreach ($set as $color) {
                $colorAndNumber                       = explode(' ', substr($color, 1));
                $game[$setNumber][$colorAndNumber[1]] = $colorAndNumber[0];
            }
        }

        return $game;
    }

    private function gameIsPossible(array $game)
    {
        $availableCubes = [
            self::RED   => 12,
            self::GREEN => 13,
            self::BLUE  => 14,
        ];

        foreach ($game as $set) {
            foreach ($set as $color => $amount) {
                if (self::RED === $color && $availableCubes[self::RED] < $amount) {
                    return false;
                }
                if (self::GREEN === $color && $availableCubes[self::GREEN] < $amount) {
                    return false;
                }
                if (self::BLUE === $color && $availableCubes[self::BLUE] < $amount) {
                    return false;
                }
            }
        }

        return true;
    }

    private function getPowerOfSet(array $set)
    {
        return $set[self::BLUE] * $set[self::RED] * $set[self::GREEN];
    }

    private function getMinimumSetForGame(array $game)
    {
        $valuesByColor = [
            self::RED,
            self::GREEN,
            self::BLUE
        ];

        foreach ($game as $set) {
            foreach ($set as $color => $amount) {
                if (self::RED === $color) {
                    $valuesByColor[self::RED][] = $amount;
                }
                if (self::GREEN === $color) {
                    $valuesByColor[self::GREEN][] = $amount;
                }
                if (self::BLUE === $color ) {
                    $valuesByColor[self::BLUE][] = $amount;
                }
            }
        }

        $minSet = [
            self::RED => max($valuesByColor[self::RED]),
            self::GREEN => max($valuesByColor[self::GREEN]),
            self::BLUE => max($valuesByColor[self::BLUE])
        ];

        return $minSet;
    }
}
