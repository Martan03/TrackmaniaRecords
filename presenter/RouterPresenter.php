<?php

class RouterPresenter extends Presenter
{
    protected Presenter $presenter;

    public function process(array $params) : void
    {
        $parsedURL = $this->parseURL($params[0]);
        if ($parsedURL[count($parsedURL) - 1] == "")
            unset($parsedURL[count($parsedURL) - 1]);
        
        if (empty($parsedURL[0]))
            $this->redirect("records");

        $_SESSION['lang'] = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);
        require_once("lang/" . $_SESSION['lang'] . ".php");
        $this->data['url'] = implode("/", $parsedURL);

        $presenterClass = $this->dashesIntoCamelNotation(array_shift($parsedURL)) . "Presenter";

        if (file_exists("presenter/" . $presenterClass . ".php"))
            $this->presenter = new $presenterClass;
        else
            $this->redirect("error");

        $this->presenter->process($parsedURL);

        $this->data['title'] = $this->presenter->header['title'];
        $this->data['description'] = $this->presenter->header['description'];
        $this->data['keywords'] = $this->presenter->header['keywords'];

        $this->view = 'layout';
    }

    /**
     * Parses url
     * @param string $url to be parsed
     * @return array of url subpaths
     */
    private function parseURL(string $url) : array
    {
        $parsedURL = parse_url($url);
        return explode("/", trim(ltrim($parsedURL["path"], "/")));
    }

    /**
     * Transforms text from dashes to camel notation
     * @param string $txt string to be transformed¨
     * @return string transformed text
     */
    private function dashesIntoCamelNotation(string $txt) : string
    {
        return str_replace(' ', '', ucwords(str_replace('-', ' ', $txt)));
    }
}