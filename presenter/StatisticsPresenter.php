<?php

class StatisticsPresenter extends Presenter
{
    public function process(array $params): void
    {
        $statisticsManager = new StatisticsManager();

        $this->data['statistics'] = $statisticsManager->getStatistics();
        $this->data['lang'] = getLang();

        $this->header = array(
            'title' => $this->data['lang']['statistics'],
            'description' => '',
            'keywords' => ''
        );

        $this->view = 'statistics';
    }
}