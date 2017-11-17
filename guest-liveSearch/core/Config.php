<?php

namespace AjaxLiveSearch\core;

if (count(get_included_files()) === 1) {
    exit("Direct access not permitted.");
}

/**
 * Class Config
 */
class Config
{
    /**
     * @var array
     */
    private static $configs = array(
        // ***** Database ***** //
        'db' => array(
            'host' => '127.0.0.1',
            'database' => 'suksabai',
            'username' => 'root',
            'pass' => '',
            'table' => 'Guests',
            // 'host' => 'localhost',
            // 'database' => 'cheetah_suksabai',
            // 'username' => 'cheetah_staff',
            // 'pass' => 'Z9yT]}Kiqd.x',
            // 'table' => 'Guests',
            // specify the name of search column
            'searchColumn' => 'firstName',
            // filter the result by entering table column names
            // to get all the columns, remove filterResult or make it an empty array
            'filterResult' => array(
                'firstName',
                'lastName',
                'tel',
                'province'
            )
        ),
        // ***** Form ***** //
        // This must be the same as form_anti_bot in script.min.js or script.js
        'antiBot' => "Ehsan's guard",
        // Assigning more than 3 seconds is not recommended
        'searchStartTimeOffset' => 3,
        // ***** Search Input ***** /
        'maxInputLength' => 20
    );

    /**
     * @param $key
     *
     * @return mixed
     * @throws \Exception
     */
    public static function getConfig($key)
    {
        if (!array_key_exists($key, static::$configs)) {
            throw new \Exception("Key: {$key} does not exist in the configs");
        }

        return static::$configs[$key];
    }
}
