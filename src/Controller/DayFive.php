<?php declare(strict_types = 1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DayFive extends AbstractController
{
    private const SOURCE_INDEX            = 1;
    private const DESTINATION_INDEX       = 0;
    private const RANGE_INDEX             = 2;
    private const SEED_TO_SOIL            = 'seed-to-soil';
    private const SOIL_TO_FERTILIZER      = 'soil-to-fertilizer';
    private const FERTILIZER_TO_WATER     = 'fertilizer-to-water';
    private const WATER_TO_LIGHT          = 'water-to-light';
    private const LIGHT_TO_TEMPERATURE    = 'light-to-temperature';
    private const TEMPERATURE_TO_HUMIDITY = 'temperature-to-humidity';
    private const HUMIDITY_TO_LOCATION    = 'humidity-to-location';

    #[Route('/day-five/1', name: 'day_five_one')]
    public function dayFivePartOne(): Response
    {
        $lines = file('data/DayFive/input.txt', FILE_IGNORE_NEW_LINES);

        $seeds       = array_values(array_filter($this->getSeeds($lines[0])));
        $mappingData = $this->parseInput($lines);
        $locations = [];

        foreach ($seeds as $seed){
            $soil        = $this->map($mappingData[self::SEED_TO_SOIL], $seed);
            $fertilizer  = $this->map($mappingData[self::SOIL_TO_FERTILIZER], $soil);
            $water       = $this->map($mappingData[self::FERTILIZER_TO_WATER], $fertilizer);
            $light       = $this->map($mappingData[self::WATER_TO_LIGHT], $water);
            $temperature = $this->map($mappingData[self::LIGHT_TO_TEMPERATURE], $light);
            $humidity    = $this->map($mappingData[self::TEMPERATURE_TO_HUMIDITY], $temperature);
            $location    = $this->map($mappingData[self::HUMIDITY_TO_LOCATION], $humidity);

            $locations[$seed] = $location;
        }

        return new Response('Lowest location is ' . min($locations) . '.');
    }

    private function getSeeds(string $line): array
    {
        $seedData = explode(':', $line);
        $seeds    = array_map('intval', explode(' ', $seedData[1]));

        return $seeds;
    }

    private function parseInput(array $lines)
    {
        $mappings = [];

        foreach ($lines as $line) {
            if (empty($line)) {
                continue;
            }

            if (str_contains($line, ':')) {
                $mappingType = $this->getMappingType($line);
                continue;
            }

            $mapData                  = explode(' ', $line);
            $mappings[$mappingType][] = array_map('intval', $mapData);
        }

        return $mappings;
    }

    private function getMappingType(string $line): string
    {
        $mapping = explode(' ', $line);

        return $mapping[0];
    }

    private function map(array $mapping, int $toMap)
    {
        foreach ($mapping as $mapValues){
            $source         = $mapValues[self::SOURCE_INDEX];
            $destination    = $mapValues[self::DESTINATION_INDEX];
            $range          = $mapValues[self::RANGE_INDEX];
            $sourceRangeEnd = $source + $range;

            if ($toMap === $source){
                return $destination;
            }

            if ($toMap > $source && $toMap <= $sourceRangeEnd){
                $difference = $destination - $source;
                return $toMap + $difference;
            }
        }

        return $toMap;
    }
}
