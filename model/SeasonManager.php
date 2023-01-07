<?php

class SeasonManager
{
    /**
     * Loads season from database by its name and year
     * @param string $year of the season
     * @param string $name of the season
     * @return array loaded season, returns null if not found
     */
    public function getSeason(string $year, string $name) : array
    {
        $season = Db::queryOne('
            SELECT *
            from `seasons`
            WHERE `season_year` = ? AND `season_name` = ?
        ', array($year, $name));
        if (!$season)
            return null;
        return $season;
    }

    /**
     * Loads season from database by its id
     * @param int $id of season to be loaded
     * @return array loaded season, returns null if not found
     */
    public function getSeasonById(int $id) : array
    {
        $season = Db::queryOne('
            SELECT *
            FROM `seasons`
            WHERE `season_id` = ?
        ', array($id));
        if (!$season)
            return null;
        return $season;
    }

    /**
     * Loads all seasons from database
     * @return array loaded seasons, returns null if no season found
     */
    public function getSeasons() : array
    {
        return Db::queryAll('
            SELECT *
            FROM `seasons`
        ');
    }

    /**
     * Inserts given season to database
     * @param array $season to be inserted
     */
    public function addSeason(array $season) : void
    {
        Db::insert("seasons", $season);
    }

    /**
     * Updates given season
     * @param array $season to be updated
     */
    public function editSeason(array $season) : void
    {
        Db::update("seasons", $season,
                   "WHERE season_id = ?",
                   array($season['season_id']));
    }

    /**
     * Deletes season from database by given id
     * @param int $id of the season
     */
    public function removeSeason(int $id) : void
    {
        Db::query('
            DELETE FROM `seasons`
            WHERE season_id = ?
        ', array($id));
    }

    /**
     * Adds or edits season
     * @param array $season data
     * @return string error message
     */
    public function submitDialog(array $season) : string
    {
        $exists = $this->getSeason($season['season_year'], $season['season_name']);

        if (isset($season['season_id']) && !empty($season['season_id']))
        {
            $this->editSeason($season);
            return "";
        }

        if ($exists)
        {
            if ($_SESSION['lang'] == 'cs')
                return "Tato sezóna již existuje";
            return "This season already exists";
        }

        $this->addSeason($season);
        return "";
    }
}