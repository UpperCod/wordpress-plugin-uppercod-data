<?php namespace UpperCodShortcodeData;

class Thumbnail
{
    public function __construct($post)
    {
        $this->id = get_post_thumbnail_id($post);
        $this->meta = wp_get_attachment_metadata($this->id);
    }
    public function __toString()
    {
        return wp_get_attachment_image_url($this->id, false);
    }
    public function __get($size)
    {
        return wp_get_attachment_image_url($this->id, $size);
    }

}
