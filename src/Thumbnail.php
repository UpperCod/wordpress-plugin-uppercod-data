<?php namespace UpperCodShortcodeData;

class Thumbnail
{
    public function __construct($post)
    {
        $this->ID = get_post_thumbnail_id($post);
    }
    public function __toString()
    {
        return wp_get_attachment_image_url($this->ID, false);
    }
    public function __get($type)
    {
        if ($type === "meta") {
            return wp_get_attachment_metadata($this->ID);
        }
        if ($type === "id") {
            return $this->ID;
        }
        return wp_get_attachment_image_url($this->ID, $type);
    }

}
