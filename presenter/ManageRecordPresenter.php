<?php

class ManageRecordPresenter extends Presenter
{
    public function process(array $params) : void
    {
        if ($_POST)
        {
            $recordManager = new RecordManager();
            $this->data['errors'] = $recordManager->submitDialog($_POST);

            if (empty($this->data['errors']))
            {
                $this->redirect("seasons/" . $params[0] . 
                                "/" . $params[1] . "/" . $params[2]);
                return;
            }
        }

        if (count($params) != 3)
        {
            $this->redirect('error');
            return;
        }

        $seasonManager = new SeasonManager();
        $this->data['season'] = $seasonManager->getSeason($params[0], $params[1]);
        $this->data['level'] = $params[2];
        $this->data['lang'] = getLang();

        $this->header = array(
            'title' => 'Manage record',
            'description' => '',
            'keywords' => ''
        );

        $this->view = 'manageRecord';
    }
}