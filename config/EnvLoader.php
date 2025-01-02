<?php

function loadEnv($file)
{
    if (!file_exists($file)) {
        throw new Exception('The .env file is missing!');
    }

    // Read the .env file
    $lines = file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

    foreach ($lines as $line) {
        // Ignore comments
        if (strpos($line, '#') === 0) {
            continue;
        }

        // Split key=value pairs
        list($key, $value) = explode('=', $line, 2);

        // Remove possible surrounding whitespace
        $key = trim($key);
        $value = trim($value);

        // Set the environment variable
        putenv("$key=$value");
        $_ENV[$key] = $value;
    }
}
