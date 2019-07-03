<?php
/**
 *
 * @author : 尤嘉兴
 * @version: 2019/7/3 9:56
 */
function parse_heroku_env()
{
    if (!getenv("IS_IN_HEROKU")) {
        return;
    }

    $databaseUrl = getenv("DATABASE_URL");
    if (empty($databaseUrl)) {
        return;
    }

    $url = parse_url($databaseUrl);

    $connection = $url['schema'] == 'postgres' ? "pgsql" : $url['schema'];
    putenv("DB_CONNECTION=" . $connection);
    putenv("DB_HOST=" . $url['host']);
    putenv("DB_PORT=" . $url['port']);
    putenv("DB_DATABASE=" . substr($url['path'], 1));
    putenv("DB_USERNAME=" . $url['user']);
    putenv("DB_PASSWORD=" . $url['pass']);
}