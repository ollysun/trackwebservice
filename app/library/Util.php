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


}