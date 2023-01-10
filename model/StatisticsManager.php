<?php

class StatisticsManager
{
    /**
     * Gets record on every map
     * @return array of records on each map
     */
    public function getStatistics() : array
    {
        $seasonManager = new SeasonManager();
        $recordManager = new RecordManager();
        $seasons = $seasonManager->getSeasons();
        $statistics = array();

        foreach ($seasons as $season)
        {
            $records = $recordManager->getSeasonLevelsRecords($season['season_id']);
            foreach ($records as $record)
            {
                $id = $this->inStatistics('record_holder', $record['record_holder'], $statistics);
                if ($id != -1)
                {
                    $statistics[$id]['record_number']++;
                    continue;
                }

                if ($record['record_time'] == '')
                    continue;

                array_push($statistics, array(
                    'record_holder' => $record['record_holder'],
                    'record_number' => 1
                ));
            }
        }

        usort($statistics, function($a, $b) {
            return $b['record_number'] <=> $a['record_number'];
        });
        
        return $statistics;
    }

    /**
     * Gets all map records in given season
     * @param array $season to find records in
     * @return array of records on each map
     */
    public function getSeasonStatistics(array $season) : array
    {
        $recordManager = new RecordManager();
        $stats = array();

        $records = $recordManager->getSeasonLevelsRecords($season['season_id']);
        foreach ($records as $record)
        {
            $id = $this->inStatistics('record_holder', $record['record_holder'], $stats);
            if ($id != -1)
            {
                $stats[$id]['record_number']++;
                continue;
            }

            if ($record['record_time'] == '')
                continue;

            array_push($stats, array(
                'record_holder' => $record['record_holder'],
                'record_number' => 1
            ));
        }
        usort($stats, function($a, $b) {
            return $b['record_number'] <=> $a['record_number'];
        });

        return $stats;
    }

    /**
     * Gets all records by given player
     * @param string $player name
     * @return array records list
     */
    public function getPlayerRecords(string $player) : array
    {
        $seasonManager = new SeasonManager();
        $seasons = $seasonManager->getSeasons();
        $stats = array();

        foreach ($seasons as $season)
        {
            $stats[$season['season_id']] = $this->getPlayerSeasonRecords($player, $season);
        }

        return $stats;
    }

    /**
     * Gets all records by given player in given season
     * @param string $player name
     * @param array $season to be searched
     * @return array records list
     */
    public function getPlayerSeasonRecords(string $player, array $season) : array
    {
        $recordManager = new RecordManager();
        $statistics = array();

        $records = $recordManager->getSeasonLevelsRecords($season['season_id']);

        foreach ($records as $record)
        {
            if ($record['record_holder'] != $player)
                continue;
            array_push($statistics, $record);
        }

        return $statistics;
    }

    /**
     * Checks if value is in array on key
     * @param string $key of the array
     * @param string $val value to be found
     * @param array $statistics array
     * @return int index of the item, if not found -1
     */
    public function inStatistics(string $key, string $val, array $statistics) : int
    {
        for ($i = 0; $i < count($statistics); $i++)
            if (isset($statistics[$i][$key]) && $statistics[$i][$key] == $val)
                return $i;
        return -1;
    }
}