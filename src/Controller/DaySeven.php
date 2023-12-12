<?php declare(strict_types = 1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DaySeven extends AbstractController
{
    public const CARD_VALUES = ['A', 'K', 'Q', 'J', 'T', '9', '8', '7', '6', '5', '4', '3', '2'];
    public const RANK_VALUES = [
        'five-of-a-kind'  => 0,
        'four-of-a-kind'  => 1,
        'full-house'      => 2,
        'three-of-a-kind' => 3,
        'two-pair'        => 4,
        'one-pair'        => 5,
        'high-card'       => 6,
    ];

    #[Route('/day-seven/1', name: 'day_seven_one')]
    public function daySevenPartOne(): Response
    {
        $winnings = 0;
        $input    = file('data/DaySeven/input.txt', FILE_IGNORE_NEW_LINES);
        $hands    = $this->getHands($input);
        $this->sortByRank($hands);

        $currentHand = count($hands);
        foreach ($hands as $hand => $bid) {
            $winnings += $bid * $currentHand;
            --$currentHand;
        }

        return new Response('' . $winnings . '.');
    }

    private function getHands(array $input)
    {
        $hands = [];
        foreach ($input as $hand) {
            $data            = explode(' ', $hand);
            $hands[$data[0]] = (int)$data[1];
        }

        return $hands;
    }

    private function sortByRank(array &$hands)
    {
        uksort($hands, [$this, 'compareHand']);
    }

    private function compareHand(string $handA, string $handB)
    {
        $rankValueA = self::RANK_VALUES[$this->getType($handA)];
        $rankValueB = self::RANK_VALUES[$this->getType($handB)];

        if ($rankValueA === $rankValueB) {
            for ($i = 0; $i < strlen($handA); ++$i) {
                $cardA = array_search($handA[$i], self::CARD_VALUES, true);
                $cardB = array_search($handB[$i], self::CARD_VALUES, true);

                if ($cardA === $cardB) {
                    continue;
                }

                return $cardA < $cardB ? -1 : 1;
            }
        }

        return ($rankValueA < $rankValueB) ? -1 : 1;
    }

    private function getType(string $hand)
    {
        $cards           = str_split($hand);
        $valueCount      = array_values(array_count_values($cards));
        $differentValues = count($valueCount);

        if (1 === $differentValues) {
            return 'five-of-a-kind';
        }

        if (2 === $differentValues) {
            if (4 === $valueCount[0] || 4 === $valueCount[1]) {
                return 'four-of-a-kind';
            }

            if (3 === $valueCount[0] || 3 === $valueCount[1]) {
                return 'full-house';
            }
        }

        if (3 === $differentValues) {
            if (3 === $valueCount[0] || 3 === $valueCount[1] || 3 === $valueCount[2]) {
                return 'three-of-a-kind';
            }

            if (2 === $valueCount[0] || 2 === $valueCount[1] || 2 === $valueCount[2]) {
                return 'two-pair';
            }
        }

        if (4 === $differentValues) {
            if (2 === $valueCount[0] || 2 === $valueCount[1] || 2 === $valueCount[2] || 2 === $valueCount[3]) {
                return 'one-pair';
            }
        }

        return 'high-card';
    }
}
