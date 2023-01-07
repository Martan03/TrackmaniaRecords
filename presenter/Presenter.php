<?php
abstract class Presenter
{
    protected array $data = array();
    protected string $view = "";
    protected array $header = array(
        'title' => '',
        'description' => '',
        'keywords' => ''
    );

    /**
     * Processes given URI array
     * @param array $params URI array
     */
    abstract function process(array $params) : void;

    /**
     * Writes views and extracts validated data
     */
    public function writeView() : void
    {
        if (!$this->view)
            return;
        
        extract($this->validate($this->data));
        extract($this->data, EXTR_PREFIX_ALL, "");
        require("template/" . $this->view . ".phtml");
    }

    /**
     * Redirects to given url
     * @param string $url to be redirected to
     */
    public function redirect(string $url) : never
    {
        header('Location: /' . $url);
        header('Connection: close');
        exit;
    }

    /**
     * Validates given variable
     * Uses htmlspecialchars on strings
     * @param $x variable to be validated
     * @return mixed validated variable
     */
    private function validate($x = null)
    {
        if (!isset($x))
            return null;
        if (is_string($x))
            return htmlspecialchars($x, ENT_QUOTES);
        if (is_array($x))
        {
            foreach ($x as $key => $value)
                $x[$key] = $this->validate($value);
            return $x;
        }
        return $x;
    }
}