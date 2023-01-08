<?php

class RecordManager
{
    /**
     * Loads record by its id from database
     * @param int $id of the record to be loaded
     * @return array loaded records, null if not found
     */
    public function getRecord(int $id) : array
    {
        $record = Db::queryOne('
            SELECT *
            FROM `records`
            WHERE `record_id` = ?
        ', array($id));
        if (!$record)
            return null;
        return $record;
    }

    /**
     * Loads all records from given level and season from database
     * @param int $id of the season
     * @param int $level where record have been set
     * @return array level records
     */
    public function getRecordsBySeasonLevel(int $season, int $level) : array
    {
        return Db::queryAll('
            SELECT *
            FROM `records`
            WHERE `record_season` = ? AND `record_level` = ?
            ORDER BY `record_time` DESC
        ', array($season, $level));
    }

    /**
     * Loads best time from each level in given season
     * @param int $id of the season
     * @return array best time of each level
     */
    public function getSeasonLevelsRecords(int $season) : array
    {
        $records = array();
        for ($i = 0; $i < 25; $i++)
        {
            $rec = Db::queryOne('
                SELECT *
                FROM `records`
                WHERE `record_season` = ? AND `record_level` = ?
                ORDER BY `record_time` ASC
                LIMIT 1
            ', array($season, $i + 1));
            if (empty($rec))
            {
                $records[$i + 1] = $this->getNotSetRecord($season, $i + 1);
                continue;
            }
            $records[$i + 1] = $rec['record_time'];
        }
        return $records;
    }

    /**
     * Creates not set record on given season and level
     * @param int $season of record to be created
     * @param int $level of record to be created
     * @return array not set record on given season and level
     */
    public function getNotSetRecord(int $season, int $level) : array
    {
        return array(
            'record_id' => '',
            'record_holder' => '',
            'record_time' => 'No record',
            'record_season' => $season,
            'record_level' => $level
        );
    }
}