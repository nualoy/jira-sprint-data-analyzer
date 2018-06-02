# Jira Sprint Data Crawler

![PHP from Packagist](https://img.shields.io/packagist/php-v/symfony/symfony.svg)

This application uses the two JIRA APIs to retrieve relevant Sprint data with which to build custom charts and reports.

The **Jira Software Cloud REST API** gives access to the information related to Agile boards and sprints.
https://developer.atlassian.com/cloud/jira/software/rest/
 
The **Jira Server REST API** provides with a wide number of endpoints to, among many other functions, query detailed issue information and make custom searches.
https://docs.atlassian.com/software/jira/docs/api/REST/latest

### Setup

1. Run `composer install`

2. Run `composer dump-autoload`. This is to workaround an issue with the autoload of the JMS Serializer/Doctrine annotations in Symfony, as described here: https://github.com/symfony/symfony/issues/25555

3. Set the Jira instance host and credentials in the `.env` file. You can use a password but it is safer to create an API token. You can do it here: https://id.atlassian.com/manage/api-tokens

### Usage


#### Getting data to draw a Burndown or Burnup Chart

#### Getting the data to represent a Sprint Board


## Known issues

- The duration of a sprint is automatically calculated from the start and end dates after subtracting the weekend days. In Jira you can add extra working days at the board configuration. These are not made available via the APIs, hence the duration of the sprint may not always match the one in Jira.