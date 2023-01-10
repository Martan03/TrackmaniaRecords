<?php

class SeasonsPresenter extends Presenter
{
    public function process(array $params): void
    {
        $seasonManager = new SeasonManager();
        $recordManager = new RecordManager();
        $statsManager = new StatisticsManager();
        $this->data['lang'] = getLang();

        // If not parameters, open season overview
        if (empty($params))
        {
            $this->header = array(
                'title' => 'Trackmania records',
                'description' => '',
                'keywords' => ''
            );
    
            $this->data['seasons'] = $seasonManager->getSeasons();
            $this->view = 'seasons';

            return;
        }

        // Remove record from database
        if (count($params) == 5 && $params[3] == 'remove')
        {
            $recordManager->removeRecord($params[4]);
            $this->redirect('seasons/' . $params[0] . '/' . $params[1] . '/' . $params[2]);
        }

        // Invalid number of parameters, redirect to error page
        if (count($params) < 2 || count($params) > 3)
        {
            $this->redirect("error");
            return;
        }

        $season = $seasonManager->getSeason($params[0], $params[1]);

        if (!$season)
        {
            $this->redirect("error");
            return;
        }

        $this->header = array(
            'title' => $season['season_year'] . ' ' . $season['season_name'],
            'description' => '',
            'keywords' => ''
        );

        $this->data['season'] = $season;

        // If two parameters, open season details
        if (count($params) == 2)
        {
            $this->data['records'] = $recordManager->getSeasonLevelsRecords($season['season_id']);
            $this->data['top'] = $statsManager->getSeasonStatistics($season);
            $this->view = 'records';
            return;
        }

        // Last option is level details page
        $this->data['records'] = $recordManager->getRecordsBySeasonLevel($season['season_id'], $params[2]);
        $this->data['level'] = sprintf("%02d", $params[2]);
        $this->data['url'] = $season['season_year'] . '/'
                            . $season['season_name'] . '/'
                            . $params[2];
        $this->header['title'] .= ' - ' . $this->data['level'];
        $this->view = 'level';
    }
}