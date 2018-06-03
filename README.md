# Jira Sprint Data ~~Analyzer~~ Provider

![PHP from Packagist](https://img.shields.io/packagist/php-v/symfony/symfony.svg)

This application uses the two JIRA APIs to retrieve and analyze relevant sprint data:

The **Jira Software Cloud REST API** gives access to the information related to Agile boards and sprints.
https://developer.atlassian.com/cloud/jira/software/rest/
 
The **Jira Server REST API** provides with a wide number of endpoints to, among many other functions, query detailed issue information and make custom searches.
https://docs.atlassian.com/software/jira/docs/api/REST/latest

### Setup

1. Run `composer install`

2. Run `composer dump-autoload`. This is to workaround an issue with the autoload of the JMS Serializer/Doctrine annotations in Symfony, as described here: https://github.com/symfony/symfony/issues/25555

3. Set the Jira instance host and credentials in the `.env` file. You can use a password but it is safer to create an API token. You can do it here: https://id.atlassian.com/manage/api-tokens

### Usage

#### REST API

* Start the local web server: `bin/console server:start`
* By default the server listens on `http://localhost:8001`. Type this address in the browser.

###### Methods:

GET `/boards` List all available boards

GET `/board/{id}` Get a board with a list of all the sprints

GET `/sprint/{id}` Get a sprint by id

#### Command Line

Run `bin/console sprint:load {teamKey} {sprintName}` to get all the information about a sprint
