# UpperCodData

This plugin allows access through shortcode to information of the shared concurrent object
for wordpress, by default $post.

## Syntax

```
[date <property> [...filters] ]
```

1. `<property>`: Property to access of the current object.
2. `[...filters]`: Optional filters to apply to the data selected by `<property>`.

## Example

get page title

```txt
[data title]
```

get page date

```txt
[data date]
```

get page date and apply filter

```txt
[data date date="Y"]
```

## Default filters

```php
[
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
```

You can add more filters by accessing the global variable `$UpperCodData`.
