<?php namespace UpperCodData;

/**
 * Plugin Name:       UpperCod
 * Plugin URI:        http://github.com/uppercod/wordpress-plugin-data
 * Description:       shortcode that accelerates the reuse of information
 * Version:           1.0.0
 * Requires PHP:      7.2
 * Author:            UpperCod
 * Author URI:        http://github.com/uppercod
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       UpperCod
 * Domain Path:       /languages
 */

defined("ABSPATH") || exit;

global $UpperCodData;

$UpperCodData = [
    "date" => "date",
    "json" => function (string $option, string $value) {
        return JSON_ENCODE($value);
    },
    "md5" => function (string $option, string $value) {
        return md5($value);
    },
    "striptags" => function (string $option, string $value) {
        return striptags($value);
    },
    "htmlencode" => function (string $option, string $value) {
        return htmlentities($value);
    },
    "urlencode" => function (string $option, string $value) {
        return urlencode($value);
    },
    "base64encode" => function (string $option, string $value) {
        return base64_encode($value);
    },
    "slug" => function (string $option, string $value) {
        return sanitize_title($value);
    },
];

/**
 * @param string $field field to retrieve from concurrent object
 */
function getData(string $field, $base = null)
{
    if (!$base) {
        global $post;
        $base = $post;
    }

    $current = "";
    $path = preg_split("/\./", $field);
    foreach ($path as $i => $value) {
        if (!$i) {
            if (function_exists("get_field")) {
                $current = get_field($value, $base->ID);
                if ($current) {
                    continue;
                }
            }
            $current = $base->{$value} ?? $base->{"post_{$value}"};
        } elseif ($current instanceof \WP_Post) {
            return getData(join(".", array_slice($path, $i)), $current);
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

/**
 * Retrieve data from the concurrent object and apply filters based on the shortcode arguments
 * [data title] will get from the concurrent object the `title` property
 * [data relation.title] will get from the concurrent object the `relation` property and then `title` only if it is of type object.
 */
add_shortcode('data', function ($attrs) {
    global $oxyslab_data_utils;
    $data = "";
    $i = 0;
    foreach ($attrs as $key => $value) {
        $prop = is_numeric($key) ? $value : $key;
        // only the first index will go into getData
        if (!$i) {
            $data = getData($prop);
        } else if ($oxyslab_data_utils[$prop]) {
            // the following indices are analyzed by filters
            $data = $oxyslab_data_utils[$prop]($value, $data);
        }
        $i++;
    }
    return $data;
});
