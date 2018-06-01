<?php

namespace App\Service;

use Nahid\JsonQ\Jsonq;

class JiraApiClient
{
    /** @var string */
    private $baseUrl = 'https://instapro.atlassian.net/rest/agile/latest';

//    private $baseUrl = 'https://instapro.atlassian.net/rest/api/2'; //@todo: get from conf

//board/{boardId}/sprint/{sprintId}/issue
    private function get(string $uri): string
    {
        $ch = curl_init($this->baseUrl . $uri);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Accept: application/json',
            'Content-Type: application/json',
            'Authorization: Basic bnVyaWEuYWxveUB3ZXJrc3BvdC5ubDpOMHJ0aGVybkxpdGU='
        ));
        $res = curl_exec($ch);
        curl_close($ch);
        return (string)$res;
    }

    public function getSprint(string $teamKey, string $sprintName): ?\stdClass
    {
        $data = json_decode($this->get('/board'));
        $boardId = null;

        foreach ($data->values as $board) {

            if (strpos($board->location->name, "({$teamKey})") !== false) {
                $boardId = $board->id;
            }
        }

        if ($boardId) {
            $data = json_decode($this->get("/board/{$boardId}/sprint"));

            foreach ($data->values as $sprint) {

                if ($sprint->name === $sprintName || $sprint->name === "$teamKey $sprintName") {
                    return $sprint;
                }
            }
        }
        return null;
    }
}