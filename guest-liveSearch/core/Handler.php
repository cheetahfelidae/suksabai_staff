<?php

    namespace AjaxLiveSearch\core;

    file_exists(__DIR__.DIRECTORY_SEPARATOR.'DB.php') ? require_once(__DIR__.DIRECTORY_SEPARATOR.'DB.php') : die('There is no such a file: DB.php');
    file_exists(__DIR__.DIRECTORY_SEPARATOR.'Config.php') ? require_once(__DIR__.DIRECTORY_SEPARATOR.'Config.php') : die('There is no such a file: Config.php');

    if (count(get_included_files()) === 1) {
        exit("Direct access not permitted.");
    }

    if (session_id() == '') {
        session_start();
    }

    /**
     * Class Handler
     */
    class Handler
    {
        /**
         * returns a 32 bits token and resets the old token if exists
         *
         * @return string
         */
        public static function getToken()
        {
            // create a form token to protect against CSRF
            $token = bin2hex(openssl_random_pseudo_bytes(32));
            return $_SESSION['ls_session']['token'] = $token;
        }

        /**
         * receives a posted variable and checks it against the same one in the session
         *
         * @param $session_parameter
         * @param $session_value
         *
         * @return bool
         */
        public static function verifySessionValue($session_parameter, $session_value)
        {
            $white_list = array('token', 'anti_bot');

            if (in_array($session_parameter, $white_list) &&
                $_SESSION['ls_session'][$session_parameter] === $session_value
            ) {
                return true;
            } else {
                return false;
            }
        }

        /**
         * checks required fields, max length for search input and numbers for pagination
         *
         * @param $input_array
         *
         * @return array
         */
        public static function validateInput($input_array)
        {
            $error = array();

            foreach ($input_array as $k => $v) {
                if (!isset($v) || (trim($v) == "" && $v != "0") || $v == null) {
                    array_push($error, $k);
                } elseif ($k === 'ls_current_page' || $k === 'ls_items_per_page') {
                    if ((int)$v < 0) {
                        array_push($error, $k);
                    }
                } elseif ($k === 'ls_query' && strlen($v) > Config::getConfig('maxInputLength')) {
                    array_push($error, $k);
                }
            }

            return $error;
        }

        /**
         * forms the response object including
         * status (success or failed)
         * message
         * result (html result)
         *
         * @param        $status
         * @param        $message
         * @param string $result
         */
        public static function formResponse($status, $message, $result = '')
        {
            $css_class = ($status === 'failed') ? 'error' : 'success';

            $message = "<tr><td class='{$css_class}'>{$message}</td></tr>";

            echo json_encode(array('status' => $status, 'message' => $message, 'result' => $result));
        }

        /**
         * @param     $query
         * @param int $current_page
         * @param int $items_per_page
         *
         * @return array
         */
        public static function getResult($query, $current_page = 1, $items_per_page = 0)
        {
            // get connection
            $db = DB::getConnection();

            $dbInfo = Config::getConfig('db');

            $sql = "SELECT COUNT(id) FROM {$dbInfo['table']}";

            // append where clause if search column is set in the config
            $whereClause = '';
            if (!empty($dbInfo['searchColumn'])) {
                $whereClause .= " WHERE {$dbInfo['searchColumn']} LIKE :query";
                $sql .= $whereClause;
            }

            // get the number of total result
            $stmt = $db->prepare($sql);

            if (!empty($whereClause)) {
                $search_query = $query . '%';
                $stmt->bindParam(':query', $search_query, \PDO::PARAM_STR);
            }

            $stmt->execute();
            $number_of_result = (int)$stmt->fetch(\PDO::FETCH_COLUMN);

            // initialize variables
            $HTML = '';
            $number_of_pages = 1;

            if (!empty($number_of_result) && $number_of_result !== 0) {
                if (!empty($dbInfo['filterResult'])) {
                    $fromColumn = implode(',', $dbInfo['filterResult']);
                } else {
                    $fromColumn = '*';
                }

                $baseSQL = "SELECT {$fromColumn} FROM {$dbInfo['table']}";

                if (!empty($whereClause)) {
                    $baseSQL .= "{$whereClause} ORDER BY {$dbInfo['searchColumn']}";
                }

                if ($items_per_page === 0) {
                    // show all
                    $stmt = $db->prepare($baseSQL);

                    if (!empty($whereClause)) {
                        $stmt->bindParam(':query', $search_query, \PDO::PARAM_STR);
                    }

                } else {
                    /*
                     * pagination
                     *
                     * calculate total pages
                     */
                    if ($number_of_result < $items_per_page) {
                        $number_of_pages = 1;
                    } elseif ($number_of_result > $items_per_page) {
                        $number_of_pages = floor($number_of_result / $items_per_page) + 1;
                    } else {
                        $number_of_pages = $number_of_result / $items_per_page;
                    }

                    /*
                     * pagination
                     *
                     * calculate start
                     */
                    $start = ($current_page > 0) ? ($current_page - 1) * $items_per_page : 0;

                    $stmt = $db->prepare(
                        "{$baseSQL} LIMIT {$start}, {$items_per_page}"
                    );

                    if (!empty($whereClause)) {
                        $stmt->bindParam(':query', $search_query, \PDO::PARAM_STR);
                    }
                }

                // run the query and get the result
                $stmt->execute();
                $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);

                // generate HTML
                foreach ($results as $result) {
                    $HTML .= '<tr>';
                    foreach ($result as $column) {
                        $HTML .= "<td>{$column}</td>";
                    }
                    $HTML .= '</tr>';
                }

            } else {
                // To prevent XSS prevention convert user input to HTML entities
                $query = htmlentities($query, ENT_NOQUOTES, 'UTF-8');

                // there is no result - return an appropriate message.
                $HTML .= "<tr><td>ไม่มีผลลัพธ์ของ \"{$query}\"</td></tr>";
            }

            // form the return
            return array(
                'html' => $HTML,
                'number_of_results' => (int)$number_of_result,
                'total_pages' => $number_of_pages
            );
        }

        /**
         * @return string
         */
        public static function getJavascriptAntiBot()
        {
            return $_SESSION['ls_session']['anti_bot'] = Config::getConfig('antiBot');
        }

        /**
         * Calculate the timestamp difference between the time page is loaded
         * and the time searching is started for the first time in seconds
         *
         * @param $page_loaded_at
         *
         * @return bool
         */
        public static function verifyBotSearched($page_loaded_at)
        {
            // if searching starts less than start time offset it seems it's a Bot
            return (time() - $page_loaded_at < Config::getConfig('searchStartTimeOffset')) ? false : true;
        }
    }
