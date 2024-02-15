<?php
namespace Model;

class Image {
    private $blob;

    public function __construct($blob) {
        $this->blob = $blob;
    }

    public function toURI() {
        $imageString = stream_get_contents($this->blob);
        if ($this->blob && !empty($imageString)) {
            $imageBase64 = base64_encode($imageString);
            $imageSrc = "data:image/png;base64," . $imageBase64;
        } else {
            $imageSrc = "web/img/default_profile_picture.png";
        }
        return $imageSrc;
    }
}