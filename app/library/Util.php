<?php
use Phalcon\Di;


/**
 * User: Adeyemi Olaoye <yemi@cottacush.com>
 * Date: 9/18/15
 * Time: 12:56 PM
 */
class Util
{

    /**
     * Debug with Slack
     * @author Adeyemi Olaoye <yemi@cottacush.com>
     * @param $tag
     * @param $text
     */
    public static function slackDebug($tag, $text)
    {
       /* if($tag == 'Email Not Sent'){
            return;
        }
        //xoxb-55390846452-E4AmK4St8uQBvR21WDqtMhx9
        /** @var \Phalcon\Http\Request $request */
        $environment_name = Di::getDefault()->has('request') ? Di::getDefault()->getRequest()->getServer('SERVER_NAME') : 'CLI';
        $httpClient = new HttpClient();
        $data = ['username' => 'La Verita Bot', 'icon_emoji' => ':rat:',
            'attachments' => [
                [
                    'fallback' => "$tag",
                    'color' => '#205081',
                    'author_name' => 'La Veritas - ' . $environment_name,
                    'title' => "$tag",
                    'text' => $text
                ]
            ]];
        //$httpClient->post('https://hooks.slack.com/services/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', json_encode($data));
        $httpClient->post('https://hooks.slack.com/services/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', json_encode($data));

        try{

        }catch (Exception $exception){
            //var_dump($exception);
        }

    }

    /**
     * @author Adeyemi Olaoye <yemi@cottacush.com>
     */
    public static function getCurrentDateTime()
    {
        return date('Y-m-d H:i:s');
    }

    /**
     * @author Adeyemi Olaoye <yemi@cottacush.com>
     * @param $pattern
     * @param $params
     * @return array
     */
    public static function filterArrayKeysWithPattern($pattern, $params)
    {
        $filtered = [];
        foreach ($params as $key => $value) {
            if (preg_match($pattern, $key)) {
                $filtered[] = $key;
            }
        }
        return $filtered;
    }


    /**
     * set a date range condition
     * @author Adeyemi Olaoye <yemi@cottacush.com>
     * @param $builder \Phalcon\Mvc\Model\Query\BuilderInterface
     * @param $column
     * @param $from
     * @param $end
     * @return \Phalcon\Mvc\Model\Query\Builder|\Phalcon\Mvc\Model\Query\BuilderInterface
     */
    public static function betweenDateRange($builder, $column, $from, $end)
    {
        if (!is_null($end)) {
            $builder = $builder->andWhere("$column <= :end:", ['end' => $end], ['end' => PDO::PARAM_STR]);
        }

        if (!is_null($from)) {
            $builder = $builder->andWhere("$column >= :from:", ['from' => $from], ['from' => PDO::PARAM_STR]);
        }

        return $builder;
    }

    public static function formatCurrency($value, $dp = 2)
    {
        if (intval($value) <= 0) return $value;
        $decimal_holder = explode('.', $value);
        $value_arr = str_split($decimal_holder[0]);
        if (count($value_arr) <= 3) return $value;
        $final_value = number_format($value, $dp, ".", ",");
        return $final_value;
    }


    /**
     * Regex float validation
     * @author Olawale Lawal <wale@cottacush.com>
     * @param float
     * @return bool
     */
    public static function validateFloat($float)
    {
        return preg_match('/^-?(?=.*[0-9])\d+(\.\d+)?$/', $float);
    }

    /**
     * @param $message
     * @param $params
     * @author Adeyemi Olaoye <yemi@cottacush.com>
     * @return mixed
     */
    public static function replaceTemplate($message, $params)
    {
        foreach ($params as $param => $value) {
            $message = str_replace('{{' . $param . '}}', $value, $message);
        }
        return $message;
    }

    /**
     * @author Adeyemi Olaoye <yemi@cottacush.com>
     * @param $value
     * @return string
     */
    public static function formatWeight($value)
    {
        if (intval($value) <= 0) return $value;
        $decimal_holder = explode('.', $value);
        $value_arr = str_split($decimal_holder[0]);
        if (count($value_arr) <= 3) return $value;
        $final_value = number_format($value, 0, ".", ",");
        return $final_value;
    }

    /**
     * Show waybill number in a user friendly format
     * @author Adeyemi Olaoye <yemi@cottacush.com>
     * @param $waybill_number
     * @return mixed
     */
    public static function humanizeWaybillNumber($waybill_number)
    {
        if (!is_string($waybill_number) && strlen($waybill_number) > 0) {
            return $waybill_number;
        }

        $splitPart = '';
        if (($pos = strpos($waybill_number, '-')) !== false) {
            $splitPart = substr($waybill_number, $pos, (strlen($waybill_number) - $pos));
            $waybill_number = substr($waybill_number, 0, $pos);
        }

        $parts = str_split($waybill_number, 3);
        $end = $parts[count($parts) - 1];
        if (strlen($end) < 3) {
            $parts[count($parts) - 2] = $parts[count($parts) - 2] . $parts[count($parts) - 1];
            unset($parts[count($parts) - 1]);
        }

        $parts[count($parts) - 1] = $parts[count($parts) - 1] . $splitPart;

        return implode(' ', $parts);
    }

    /**
     * Add where in condition
     * @author Adeyemi Olaoye <yemi@cottacush.com>
     * @param $where
     * @param $bind
     * @param $model
     * @param $field
     * @param $value
     */
    public static function addWhereInCondition(&$where, &$bind, $model, $field, $value)
    {
        if (strpos($value, ',') === false) {
            $where[] = $model . '.' . $field . ' = :' . $field . ':';
            $bind[$field] = $value;
        } else {
            $values = str_replace(',', "','", $value);
            $values = "'" . $values . "'";
            $where[] = "$model.$field IN ($values)";
        }
    }


    /**
     * Convert pascal casing to snake casing
     * @author Adeyemi Olaoye <yemi@cottacush.com>
     * @credits http://stackoverflow.com/questions/1993721/how-to-convert-camelcase-to-camel-case
     * @param $input
     * @return string
     */
    public static function  convertPascalToSnakeCasing($input)
    {
        preg_match_all('!([A-Z][A-Z0-9]*(?=$|[A-Z][a-z0-9])|[A-Za-z][a-z0-9]+)!', $input, $matches);
        $ret = $matches[0];
        foreach ($ret as &$match) {
            $match = $match == strtoupper($match) ? strtolower($match) : lcfirst($match);
        }
        return implode('_', $ret);
    }
}