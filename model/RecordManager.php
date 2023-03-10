<?php

class RecordManager
{
    /**
     * Loads record by its id from database
     * @param int $id of the record to be loaded
     * @return array loaded records, null if not found
     */
    public function getRecord(int $id) : ?array
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
     * Loads every records from database
     * @return array loaded records
     */
    public function getRecords() : array
    {
        return Db::queryAll('
            SELECT *
            FROM `records`
        ');
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
            ORDER BY `record_time` ASC
        ', array($season, $level));
    }
    
    /**
     * Loads best time from each level in given season
     * @param int $id of the season
     * @return array best time of each level
     */
    public function getSeasonLevelsRecords(int $season) : array
    {
        $rec = Db::queryAll('
            SELECT *
            FROM records r
            WHERE record_time = (
                SELECT MIN(record_time)
                FROM records
                WHERE record_level = r.record_level AND record_season = ?
            ) AND record_season = ?
            ORDER BY record_level
        ', array($season, $season));

        $records = array();
        $count = 0;
        for ($i = 0; $i < 25; $i++)
        {
            if (isset($rec[$count]) && $rec[$count]['record_level'] == $i + 1)
                array_push($records, $rec[$count++]);
            else
                array_push($records, $this->getNotSetRecord($season, $i + 1));
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
            'record_time' => '',
            'record_season' => $season,
            'record_level' => $level
        );
    }

    /**
     * Inserts given record to database
     * @param array $record to be inserted
     */
    public function addRecord(array $record) : void
    {
        Db::insert("records", $record);
    }

    /**
     * Updates given record
     * @param array $record to be updated
     */
    public function editRecord(array $record) : void
    {
        Db::update("records", $record,
                   "WHERE `record_id` = ?",
                   array($record['record_id']));
    }

    /**
     * Deletes record from database by given id
     * @param int $id of the record
     */
    public function removeRecord(int $id) : void
    {
        Db::query('
            DELETE FROM `records`
            WHERE `record_id` = ?
        ', array($id));
    }

    /**
     * Adds or edits record
     * @param array $record to be submited
     * @return array errors array
     */
    public function submitDialog(array $record) : array
    {
        $errors = array();
        if (!isset($record['record_holder']) || empty($record['record_holder']))
            $errors['holder'] = 'Invalid';
        if (!isset($record['record_time']) || empty($record['record_time']))
            $errors['holder'] = 'Invalid';
        
        if (!empty($errors))
            return $errors;
        
        $this->addRecord($record);
        return $errors;
    }
}