<?php

namespace calendar\models;

use PDO;
use system\abstracts\model_abstracts;

class model_age extends model_abstracts
{

    /**
     * Responds with database driven dates
     */
    public function calculate($data = array())
    {
        $time_sql = "SELECT DATE_SUB(NOW(), INTERVAL :days DAY) `date`;";
        $statement = $this->pdo->prepare($time_sql);

        $params = array(
            ":days" => (int)($data[0] ?? 0),
        );
        $statement->execute($params);

        /**
         * One record: fetch()
         * Multiple records: fetchAll()
         */
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);

        return $result;
    }

}
