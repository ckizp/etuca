<?php
namespace vue;

class Vue {
    private $file;
    private $title;

    public function __construct($action) {
        $this->file = __DIR__ . "/{$action}.php";
    }

    public function displayWithoutTemplate($data) {
        $content = $this->generateVue($this->file, $data);
        echo $content;
    }

    public function display($data) {
        $content = $this->generateVue($this->file, $data);
        $view = $this->generateVue(__DIR__ . "/template.php", ["title" => $this->title, "content" => $content]);

        echo $view;
    }

    private function generateVue($file, $data) {
        if (file_exists($file)) {
            extract($data);
            ob_start();
            require $file;
            return ob_get_clean();
        } else {
            throw new \Exception("Unable to find {$file}");
        }
    }
}