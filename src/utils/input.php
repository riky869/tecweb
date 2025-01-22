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
        return [
            'callback' => [self::class, 'lengthCallback'],
            'args' => ['min' => $min, 'max' => $max],
            'error' => 'deve essere lungo ' .
                ($min !== -1 ? "almeno $min caratteri" : '') .
                ($min !== -1 && $max !== -1 ? ' e ' : '') .
                ($max !== -1 ? "al massimo $max caratteri" : '') . '.'
        ];
    }

    public static function minMaxNumCheck(int $min = -1, int $max = -1)
    {
        return [
            'callback' => function ($value, $args) {
                $num = floatval($value);
                return !is_nan($num) && ($args['min'] === -1 || $num >= $args['min']) && ($args['max'] === -1 || $num <= $args['max']);
            },
            'args' => ['min' => $min, 'max' => $max],
            'error' => 'deve essere ' .
                ($min !== -1 ? "almeno $min" : '') .
                ($min !== -1 && $max !== -1 ? ' e ' : '') .
                ($max !== -1 ? "al massimo $max" : '') . '.'
        ];
    }
}

$commonChecks = [
    'name' => [
        Validation::minMaxCharsCheck(2, -1),
        [
            'callback' => [Validation::class, 'regexCallback'],
            'args' => ['regex' => "/^[A-Za-zÀ-ÖØ-öø-ÿ'’\-]+(?: [A-Za-zÀ-ÖØ-öø-ÿ'’\-]+)*$/"],
            'error' => 'può contenere solo lettere degli alfabeti e spazi.'
        ]
    ],
    'film' => [
        [
            'callback' => [Validation::class, 'regexCallback'],
            'args' => ['regex' => "/^[A-Za-zÀ-ÖØ-öø-ÿ0-9!?,.'’\-: ]+$/"],
            'error' => 'può contenere solo lettere, numeri e spazi.'
        ]
    ],
    'image' => [
        [
            'callback' => [Validation::class, 'regexCallback'],
            'args' => ['regex' => "/\.(jpeg|jpg|gif|png|webp)$/"],
            'error' => 'deve essere un file jpeg, jpg, gif o png.'
        ]
    ],
    'rec_title' => [
        Validation::minMaxCharsCheck(2, 30),
        [
            'callback' => [Validation::class, 'regexCallback'],
            'args' => ['regex' => "/^(?![-\s])[A-Za-zÀ-ÖØ-öø-ÿ0-9\s'()\-\u{2013}]{2,30}(?<![-\s])$/"],
            'error' => 'deve essere lungo tra 2 e 30 caratteri, può contenere lettere, numeri, spazi, trattini, apostrofi e parentesi, ma non può iniziare o terminare con spazi o caratteri speciali.'
        ]
    ],
    'rec_content' => [
        [
            'callback' => [Validation::class, 'regexCallback'],
            'args' => ['regex' => "/^[A-Za-zÀ-ÖØ-öø-ÿ0-9\s'(),.!?;:\-\u{2013}\u{2014}]+$/"],
            'error' => 'può contenere solo lettere, numeri, spazi e alcuni caratteri speciali (, . ! ? ; : - – —).'
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
