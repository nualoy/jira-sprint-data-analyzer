<?php

namespace App\Service;

class JiraCloudClient
{
    /** @var string */
    private $baseUri = '/rest/agile/latest';

    public function get(string $uri): string
    {
        $baseUrl = getenv('JIRA_HOST') . $this->baseUri;
        $ch = curl_init($baseUrl . $uri);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Accept: application/json',
            'Content-Type: application/json',
            'X-Atlassian-Token: no-check'
        ]);
        $username = getenv('JIRA_USER');
        $password = getenv('JIRA_PASS');
        curl_setopt($ch, CURLOPT_USERPWD, "$username:$password");
        $res = curl_exec($ch);
        curl_close($ch);
        return (string)$res;
    }
}