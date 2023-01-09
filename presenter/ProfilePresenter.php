<?php

class ProfilePresenter extends Presenter
{
    public function process(array $params): void
    {
        if (empty($params))
        {
            $this->redirect("error");
            return;
        }

        $seasonManager = new SeasonManager();
        $statisticsManager = new StatisticsManager();

        $this->data['seasons'] = $seasonManager->getSeasons();
        $this->data['records'] = $statisticsManager->getPlayerRecords($params[0]);

        $this->data['profile_name'] = $params[0];
        $this->data['lang'] = getLang();

        $this->header = array(
            'title' => $params[0],
            'description' => '',
            'keywords' => ''
        );

        $this->view = 'profile';
    }
}