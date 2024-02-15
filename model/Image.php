<?php
namespace Model;

class Image {
    private $blob;

    public function __construct($blob) {
        $this->blob = $blob;
    }

    public function toURI() {
        if($this->blob == null)
            return "img/default_profile_picture.png";
        return "data:image/png;base64," . base64_encode($this->blob);
    }
}