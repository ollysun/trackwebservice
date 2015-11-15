<?php


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
        /** @var \Phalcon\Http\Request $request */
        $request = \Phalcon\Di::getDefault()->getRequest();
        $httpClient = new HttpClient();
        $data = ['username' => 'TNT Debug Bot', 'icon_emoji' => ':rat:',
            'attachments' => [
                [
                    'fallback' => "$tag",
                    'color' => '#205081',
                    'author_name' => 'TNT SERVICE - ' . $request->getServer('SERVER_NAME'),
                    'title' => "$tag",
                    'text' => $text
                ]
            ]];

        $httpClient->post('https://hooks.slack.com/services/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', json_encode($data));
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


    /**
     * Regex float validation
     * @author Olawale Lawal <wale@cottacush.com>
     * @param float
     * @return bool
     */
    public static function validateFloat($float)
    {
        return preg_match('/^(?=.*[0-9])\d+(\.\d+)?$/', $float);
    }
}