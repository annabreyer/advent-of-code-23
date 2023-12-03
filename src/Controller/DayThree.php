<?php declare(strict_types = 1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DayThree extends AbstractController
{
    #[Route('/day-three/1', name: 'day_three_one')]
    public function dayThreePartOne(): Response
    {
        $sum           = 0;
        $lines         = file('data/DayThree/input.txt', FILE_IGNORE_NEW_LINES);
        $explodedLines = $this->convertLinesToArrays($lines);

        foreach ($lines as $key => $line) {
            $numbersInLine = $this->extractNumbersFromString($line);

            foreach ($numbersInLine as $position => $number) {
                $numberIsSymbolAdjacent = $this->isSymbolAdjacentToNumber($explodedLines, $key, $position, $number);

                if ($numberIsSymbolAdjacent) {
                    $sum += (int)$number;
                }
            }
        }

        //false === 535391;

        return new Response('Sum is ' . $sum . '.');
    }

    #[Route('/day-three/2', name: 'day_three_two')]
    public function dayThreePartTwo(): Response
    {
        $sum   = 0;
        $lines = file('data/DayThree/input.txt', FILE_IGNORE_NEW_LINES);

        foreach ($lines as $line) {

        }

        return new Response('Sum is ' . $sum . '.');

    }
    
    private function isSymbol(string $value): bool
    {
        return 1 === preg_match('/[^0-9.]/', $value);
    }

    private function extractNumbersFromString(string $line): array
    {
        $matchesWithPosition = [];
        preg_match_all('/[0-9]+/', $line, $matches, PREG_OFFSET_CAPTURE);

        foreach ($matches[0] as $key => $match) {
            $position                       = $match[1];
            $number                         = $match[0];
            $matchesWithPosition[$position] = $number;
        }

        return $matchesWithPosition;
    }

    private function convertLinesToArrays(array $lines): array
    {
        $explodedLines = [];
        foreach ($lines as $key => $line) {

            $lineArray           = str_split($line);
            $explodedLines[$key] = $lineArray;

        }

        return $explodedLines;
    }

    private function isSymbolAdjacentToNumber(array $explodedLines, int $lineKey, int $position, string $number)
    {
        $numberLength    = strlen($number);
        $endPosition     = $position + $numberLength;
        $nextLineKey     = $lineKey + 1;
        $lineBeforeKey   = $lineKey - 1;
        $isFirstLine     = $lineKey === 0;
        $isLastLine      = false === isset($explodedLines[$nextLineKey]);

        while ($position <= $endPosition) {
            $isFirstPosition = 0 === $position;
            $positionBefore  = $position - 1;
            $positionAfter   = $position + 1;

            //check if symbol or before (if there is a value before)
            if (false === $isFirstPosition && $this->isSymbol($explodedLines[$lineKey][$positionBefore])) {
                return true;
            }

            //check if symbol after (if there is a value after)
            if (isset($explodedLines[$lineKey][$positionAfter]) && $this->isSymbol($explodedLines[$lineKey][$positionAfter])) {
                return true;
            }

            //if there is a line above
            if (false === $isFirstLine) {
                //check if symbol above
                if (isset($explodedLines[$lineBeforeKey][$position]) && $this->isSymbol($explodedLines[$lineBeforeKey][$position])) {
                    return true;
                }

                //check if symbol diagonal up left
                if (isset($explodedLines[$lineBeforeKey][$positionBefore]) && $this->isSymbol($explodedLines[$lineBeforeKey][$positionBefore])) {
                    return true;
                }

                //check iof symbol diagonal up right
                if (isset($explodedLines[$lineBeforeKey][$positionAfter]) && $this->isSymbol($explodedLines[$lineBeforeKey][$positionAfter])) {
                    return true;
                }
            }

            //if there is a line below
            if (false === $isLastLine) {
                //check if symbol below
                if (isset($explodedLines[$nextLineKey][$position]) && $this->isSymbol($explodedLines[$nextLineKey][$position])) {
                    return true;
                }

                //check if symbol diagonal down right
                if (false === $isFirstPosition && $this->isSymbol($explodedLines[$nextLineKey][$positionBefore])) {
                    return true;
                }

                //check if symbol diagonal down left
                if (isset($explodedLines[$nextLineKey][$positionAfter]) && $this->isSymbol($explodedLines[$nextLineKey][$positionAfter])) {
                    return true;
                }
            }

            $position++;
        }

        return false;
    }
}
