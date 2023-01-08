<?php

class ManageSeasonPresenter extends Presenter
{
    public function process(array $params) : void
    {
        if ($_POST)
        {
            $seasonManager = new SeasonManager();
            $this->data['errors'] = $seasonManager->submitDialog($_POST);

            if (empty($this->data['errors']))
            {
                $this->redirect("seasons/" . $_POST['season_year'] . 
                                "/" . $_POST['season_name']);
                return;
            }
        }

        $this->header = array(
            'title' => 'Manage season',
            'description' => '',
            'keywords' => ''
        );

        $this->view = 'manageSeason';
    }
}