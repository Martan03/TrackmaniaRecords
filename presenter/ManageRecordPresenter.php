<?php

class ManageSeasonPresenter extends Presenter
{
    public function process(array $params) : void
    {
        if ($_POST)
        {
            if (!isset($_POST['season_year']) || empty($_POST['season_year']))
                $this->data['error']['year'] = "Year must be filled in";
            if (!isset($_POST['season_name']) || empty($_POST['season_name']))
                $this->data['error']['name'] = "Name must be filled in";

            if (!isset($this->data['error']) && empty($this->data['error']))
            {
                $seasonManager = new SeasonManager();
                $seasonManager->submitDialog($_POST);
                $this->redirect("seasons/" . $_POST['season_year'] . 
                                "/" . $_POST['season_name']);
            }
        }

        $this->header = array(
            'title' => 'Manage record',
            'description' => '',
            'keywords' => ''
        );

        $this->view = 'manageSeason';
    }
}