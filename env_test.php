<?php

echo "<h2>getenv()</h2>";
var_dump(getenv("DB_HOST"));

echo "<h2>_ENV</h2>";
var_dump($_ENV);

echo "<h2>_SERVER</h2>";
var_dump($_SERVER["DB_HOST"] ?? null);

echo "<h2>Environment Variables</h2>";

foreach ($_SERVER as $key => $value) {
    if (
        strpos($key, "DB_") === 0 ||
        strpos($key, "MYSQL") === 0
    ) {
        echo "$key = $value<br>";
    }
}