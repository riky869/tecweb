<?php

function clean_input($value): mixed
{
    $value = trim($value);
    $value = strip_tags($value);
    $value = htmlentities($value);
    return $value;
}


class Validation
{
    public static function regexCallback(string $value, array $args)
    {
        return preg_match($args['regex'], $value);
    }

    public static function lengthCallback(string $value, array $args)
    {
        $length = strlen($value);
        return ($length >= $args['min'] || $args['min'] == -1) && ($length <= $args['max'] || $args['max'] == -1);
    }

    public static function minMaxCharsCheck(int $min = -1, int $max = -1)
    {
        $max_formatted = number_format($max, 0, ',', '.');
        $min_formatted = number_format($min, 0, ',', '.');
        return [
            'callback' => [self::class, 'lengthCallback'],
            'args' => ['min' => $min, 'max' => $max],
            'error' => 'deve essere lungo ' .
                ($min !== -1 ? "almeno $min_formatted caratteri" : '') .
                ($min !== -1 && $max !== -1 ? ' e ' : '') .
                ($max !== -1 ? "al massimo $max_formatted caratteri" : '') . '.'
        ];
    }

    public static function minMaxNumCheck(int $min = -1, int $max = -1)
    {
        $max_formatted = number_format($max, 0, ',', '.');
        $min_formatted = number_format($min, 0, ',', '.');
        return [
            'callback' => function ($value, $args) {
                if (is_numeric($value) && strpos($value, '.') !== false) {
                    return false;
                }

                $num = intval($value);
                return !is_nan($num) && ($args['min'] === -1 || $num >= $args['min']) && ($args['max'] === -1 || $num <= $args['max']);
            },
            'args' => ['min' => $min, 'max' => $max],
            'error' => 'deve essere intero e ' .
                ($min !== -1 ? "almeno $min_formatted" : '') .
                ($min !== -1 && $max !== -1 ? ' e ' : '') .
                ($max !== -1 ? "al massimo $max_formatted" : '') . '.'
        ];
    }
}

$commonChecks = [
    'name' => [
        Validation::minMaxCharsCheck(2, -1),
        [
            'callback' => [Validation::class, 'regexCallback'],
            'args' => ['regex' => "/^[a-zA-ZàèéìòùÀÈÉÌÒÙçÇ' ]+$/"],
            'error' => 'può contenere solo lettere, spazi e apostrofi'
        ]
    ],
    'film_id' => [
        Validation::minMaxCharsCheck(0, -1),
    ],
    'film' => [
        [
            'callback' => [Validation::class, 'regexCallback'],
            'args' => ['regex' => "/^[a-zA-Z0-9\s'’\-!?]+$/"],
            'error' => 'può contenere solo lettere, numeri, spazi, apostrofi, trattini, punti esclamativi o interrogativi.'
        ]
    ],
    'image' => [
        [
            'callback' => [Validation::class, 'regexCallback'],
            'args' => ['regex' => "/\.(jpeg|jpg|gif|png|webp)$/"],
            'error' => 'deve essere un file jpeg, jpg, gif, webp o png.'
        ]
    ],
    'rec_title' => [
        Validation::minMaxCharsCheck(2, 30),
        [
            'callback' => [Validation::class, 'regexCallback'],
            'args' => ['regex' => "/^[a-zA-Z0-9\s.,!?:'-]+$/"],
            'error' => 'può contenere solo lettere, numeri, spazi e caratteri speciali come .,!?\'-'
        ]
    ],
    'rec_content' => [
        [
            'callback' => [Validation::class, 'regexCallback'],
            'args' => ['regex' => "/^(?!\s*$).+/"],
            'error' => 'non può essere vuota o composta solo da spazi.'
        ],
        Validation::minMaxCharsCheck(8, 1000)
    ],
    'rec_rating' => [
        Validation::minMaxNumCheck(1, 10)
    ]
];


// Funzione per validare un campo
function validate(?string $value, array $checks, array &$error)
{
    if (empty($value) && $checks['optional'] === true) {
        return null;
    }

    $value = clean_input($value);
    foreach ($checks['checks'] as $check) {
        $callback = $check['callback'];
        $args = $check['args'];
        if (!$callback($value, $args)) {
            $error[] = "Il campo \"" . $checks['err_name'] . "\" " . $check['error'];
        }
    }
    return $value;
}

function toSpan(string $value, string $lang = "en"): string
{
    return "<span lang=\"$lang\">$value</span>";
}
