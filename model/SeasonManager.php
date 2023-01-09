<?php

class SeasonManager
{
    /**
     * Loads season from database by its name and year
     * @param string $year of the season
     * @param string $name of the season
     * @return array loaded season, returns null if not found
     */
    public function getSeason(string $year, string $name) : ?array
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
    public function getSeasonById(int $id) : ?array
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
            ORDER BY `season_year` DESC
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
                   "WHERE `season_id` = ?",
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
            WHERE `season_id` = ?
        ', array($id));
    }

    /**
     * Adds or edits season
     * @param array $season data
     * @return array errors array
     */
    public function submitDialog(array $season) : array
    {
        $errors = array();

        if (!isset($_POST['season_year']) || empty($_POST['season_year']))
            $errors['year'] = "Invalid";
        if (!isset($_POST['season_name']) || empty($_POST['season_name']))
            $errors['name'] = "Name must be filled in";

        $exists = $this->getSeason($season['season_year'], $season['season_name']);
        
        if (!empty($errors))
            return $errors;

        if (isset($season['season_id']) && !empty($season['season_id']))
        {
            $this->editSeason($season);
            return array();
        }

        if ($exists)
        {
            if ($_SESSION['lang'] == 'cs')
                return array("exists" => "Tato sezóna již existuje");
            return array("exists" => "This season already exists");
        }

        $this->addSeason($season);
        return array();
    }
}