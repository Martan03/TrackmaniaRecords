<?php

class ErrorPresenter extends Presenter
{
    public function process(array $params): void
    {
        $this->header = array(
            'title' => 'Error',
            'description' => '',
            'keywords' => ''
        );

        $this->view = 'error';
    }
}