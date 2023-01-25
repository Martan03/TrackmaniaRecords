<?php

class SeasonsPresenter extends Presenter
{
    public function process(array $params): void
    {
        $this->data['lang'] = getLang();

        switch (count($params))
        {
            case 0:
                $this->displaySeasons();
                break;
            case 2:
                $this->displaySeason($params);
                break;
            case 3:
                $this->displayLevel($params);
                break;
            case 5:
                $this->removeRecord($params);
                break;
            default:
                $this->redirect('error');
                break;
        }
    }
    
    /**
     * Displays all seasons from database
     */
    private function displaySeasons() : void
    {
        $seasonManager = new SeasonManager();
        
        $this->header = array(
            'title' => 'Trackmania records',
            'description' => '',
            'keywords' => ''
        );

        $this->data['seasons'] = $seasonManager->getSeasons();
        $this->view = 'seasons';
    }
    
    /**
     * Displays season from URL params
     */
    private function displaySeason($params) : void
    {
        $recMng = new RecordManager();
        $statMng = new StatisticsManager();

        $this->data['season'] = $this->getSeason($params);
        $this->data['top'] = $statMng->getSeasonStatistics(
            $this->data['season']
        );

        $this->header = array(
            'title' => $this->data['season']['season_year'] . ' ' 
                     . $this->data['season']['season_name'],
            'description' => '',
            'keywords' => ''
        );
        
        $this->data['records'] = $recMng->getSeasonLevelsRecords(
            $this->data['season']['season_id']
        );
        
        $this->view = 'records';
    }

    /**
     * Displays level from URL params from database
     */
    private function displayLevel($params) : void
    {
        $recMng = new RecordManager();

        $this->data['season'] = $this->getSeason($params);
        $this->data['level'] = sprintf("%02d", $params[2]);

        $this->header = array(
            'title' => $this->data['season']['season_year'] . ' ' 
                     . $this->data['season']['season_name'] . ' - '
                     . $this->data['level'],
            'description' => '',
            'keywords' => ''
        );

        $this->data['records'] = $recMng->getRecordsBySeasonLevel(
            $this->data['season']['season_id'],
            $params[2]
        );
        $this->data['url'] = $this->data['season']['season_year'] . '/'
                            . $this->data['season']['season_name'] . '/'
                            . $params[2];

        $this->view = 'level';
    }

    /**
     * Removes record from database
     */
    private function removeRecord($params) : void
    {
        if ($params[3] != 'remove')
            $this->redirect('error');
        
        $recMng = new RecordManager();
        $recMng->removeRecord($params[4]);
        $this->redirect('seasons/' . $params[0] . '/' . $params[1] . '/' . $params[2]);
    }

    /**
     * Gets season from database, if not found redirects to error page
     */
    private function getSeason($params) : array
    {
        $seasonManager = new SeasonManager();
        $season = $seasonManager->getSeason($params[0], $params[1]);

        if (!$season)
            $this->redirect('error');
        return $season;
    }
}