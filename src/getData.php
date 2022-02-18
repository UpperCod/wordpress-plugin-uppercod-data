<?php namespace UpperCodShortcodeData;

/**
 * @param string $field field to retrieve from concurrent object
 */
function getData(string $field, $current = null)
{
    if (!$current) {
        global $post;
        $current = $post;
    }

    $path = preg_split("/\./", $field);

    foreach ($path as $i => $value) {
        if ($current instanceof \WP_Post) {
            if ($value === "thumbnail") {
                $current = new Thumbnail($current);
                continue;
            } elseif (function_exists("get_field")) {
                $nextCurrent = get_field($value, $current->ID);
                if ($nextCurrent) {
                    $current = $nextCurrent;
                    continue;
                }
            }
            $current = $current->{$value} ?? $current->{"post_{$value}"};
        } else if (is_object($current)) {
            $current = $current->{$value};
        } else if (is_array($current)) {
            $current = $current[$value];
        } else {
            return $current;
        }
    }

    return $current;
}
