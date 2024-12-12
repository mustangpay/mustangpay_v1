<?php

namespace app\extensions;

class ZImage
{

    var $image;
    var $image_type;
    var $image_info;

    function __construct($filename)
    {
        $this->image_info = $image_info = getimagesize($filename);
        $this->image_type = $image_info[2];
        if ($this->image_type == IMAGETYPE_JPEG) {
            $this->image = imagecreatefromjpeg($filename);
        } elseif ($this->image_type == IMAGETYPE_GIF) {
            $this->image = imagecreatefromgif($filename);
        } elseif ($this->image_type == IMAGETYPE_PNG) {
            $this->image = imagecreatefrompng($filename);
        }
    }

    function save($filename, $image_type = IMAGETYPE_JPEG, $compression = 80, $permissions = null)
    {
        if ($image_type == IMAGETYPE_JPEG) {
            imagejpeg($this->image, $filename, $compression);
        } elseif ($image_type == IMAGETYPE_GIF) {
            imagegif($this->image, $filename);
        } elseif ($image_type == IMAGETYPE_PNG) {
            imagepng($this->image, $filename);
        }
        if ($permissions != null) {
            chmod($filename, $permissions);
        }
    }

    function saveThumb($filename, $suffix = '', $image_type = IMAGETYPE_JPEG, $compression = 80, $permissions = null)
    {
        $fps = pathinfo($filename);
        $filename = $fps['filename'] . $suffix . '.' . $fps['extension'];
        $filename = $fps['dirname'] . '/' . $filename;
        $this->save($filename, $image_type, $compression, $permissions);
    }

    function output($image_type = IMAGETYPE_JPEG)
    {
        if ($image_type == IMAGETYPE_JPEG) {
            imagejpeg($this->image);
        } elseif ($image_type == IMAGETYPE_GIF) {
            imagegif($this->image);
        } elseif ($image_type == IMAGETYPE_PNG) {
            imagepng($this->image);
        }
    }

    function getWidth()
    {
        return imagesx($this->image);
    }

    function getHeight()
    {
        return imagesy($this->image);
    }

    function resizeTo($w, $h)
    {
        $ratio = $w / $this->getWidth();
        $width = $w;
        $height = $this->getHeight() * $ratio;
        if ($h < $height) {
            $ratio = $h / $this->getHeight();
            $height = $h;
            $width = $this->getWidth() * $ratio;
        }
        $this->resize($width, $height);
    }

    function resizeToHeight($height)
    {
        $ratio = $height / $this->getHeight();
        $width = $this->getWidth() * $ratio;
        $this->resize($width, $height);
    }

    function resizeToWidth($width)
    {
        $ratio = $width / $this->getWidth();
        $height = $this->getheight() * $ratio;
        $this->resize($width, $height);
    }

    function thumb($w, $h, $pos = 'c')
    {
        $ow = $this->getWidth();
        $oh = $this->getHeight();
        $rw = $ow / $w;
        $rh = $oh / $h;
        $r = min($rw, $rh);

        $new_image = imagecreatetruecolor($w, $h);
        if ($pos == 'lt') {
            $x = 0;
            $y = 0;
        } elseif ($pos == 'rt') {
            if ($r < $rw) {
                $x = $ow - $w * $r;
            } else {
                $x = 0;
            }
            $y = 0;
        } elseif ($pos == 'lb') {
            $x = 0;
            if ($r < $rw) {
                $y = 0;
            } else {
                $y = $oh - $h * $r;
            }
        } elseif ($pos == 'rb') {
            if ($r < $rw) {
                $x = $ow - $w * $r;
                $y = 0;
            } else {
                $x = 0;
                $y = $oh - $h * $r;
            }
        } else {
            if ($r < $rw) {
                $x = ($ow - $w * $r) / 2;
                $y = 0;
            } else {
                $x = 0;
                $y = ($oh - $h * $r) / 2;
            }
        }
        imagecopyresampled($new_image, $this->image, 0, 0, $x, $y, $w, $h, $w * $r, $h * $r);
        $this->image = $new_image;
    }

    function scale($scale)
    {
        $width = $this->getWidth() * $scale / 100;
        $height = $this->getheight() * $scale / 100;
        $this->resize($width, $height);
    }

    function resize($width, $height)
    {
        $new_image = imagecreatetruecolor($width, $height);
        imagecopyresampled($new_image, $this->image, 0, 0, 0, 0, $width, $height, $this->getWidth(), $this->getHeight());
        $this->image = $new_image;
    }

    function fixed($newW, $newH, $x, $y, $w, $h)
    {
        $newImage = imagecreatetruecolor($newW, $newH);
        imagecopyresampled($newImage, $this->image, 0, 0, $x, $y, $newW, $newH, $w, $h);
        $this->image = $newImage;
    }
}

?>