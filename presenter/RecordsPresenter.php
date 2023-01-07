<?php

class RecordsPresenter extends Presenter
{
    public function process(array $params): void
    {
        $this->header = array(
            'title' => 'Trackmania records',
            'description' => '',
            'keywords' => ''
        );

        $this->view = 'records';
    }
}