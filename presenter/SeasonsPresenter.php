<?php

class SeasonsPresenter extends Presenter
{
    public function process(array $params): void
    {
        $seasonManager = new SeasonManager();
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

        if (count($params) != 2)
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

        $this->view = 'records';
    }
}