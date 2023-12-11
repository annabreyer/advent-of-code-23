<?php declare(strict_types = 1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DaySix extends AbstractController
{
    #[Route('/day-six/1', name: 'day_six_one')]
    public function daySixPartOne(): Response
    {
        $input = file('data/DaySix/input.txt', FILE_IGNORE_NEW_LINES);
        $raceData = $this->getRaceData($input);
        dump($raceData);

        foreach ($raceData['distance'] as $index => $distanceRecord){
            $time       = $raceData['time'][$index];
            $waysToBeat[] = $this->getPossibleWinningWays($time, $distanceRecord);
        }

        return new Response('' . array_product($waysToBeat) . '.');
    }


    private function getRaceData(array $input)
    {
        $time     = array_values(array_map('intval', array_filter(explode(' ', explode(':',$input[0])[1]))));
        $distance = array_values(array_map('intval', array_filter(explode(' ', explode(':',$input[1])[1]))));

        return ['time' => $time, 'distance' => $distance];
    }

    private function getPossibleWinningWays(int $time, int $record)
    {
        $i          = 1;
        $waysToBeat = 0;
        while ($i < $time){
            $timePushed    = $i;
            $remainingTime = $time - $timePushed;
            $reachedDistance = $timePushed * $remainingTime;

            if ($reachedDistance > $record){
                $waysToBeat++;
            }
            $i++;
        }

        return $waysToBeat;
    }
}
