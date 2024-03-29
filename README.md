# Technical Assessment for Vincent

## Domain
We have a service called **Decks** with **cards** in it. 
Each card has a _face value_ existing out of a color (red, green, blue, black) and a number from 1 to 9. Each card in the Deck is unique.

## Assignment

* Users can be just JWT tokens (can be unsigned)
* A user with ROLE_SYSTEM permissions can can _initialise_ a deck with cards in it. When the deck is initialised an **DeckInitialised** event is emit.
* A user with ROLE_PLAYER permissions can _Take A Card_ from a given **Deck**. 
    - The **Card** that is returned is randomly selected. 
    - The **Deck** is then updated so that it doesn't have the selected card anymore in it.
    - When the card is selected, a **CardTaken** event is emit.
    - If all Cards are given, then a **DeckDistributed** event is emit. 
        - As a reaction on that event, a new **Deck** with the same cards are created (which results in anonther **DeckInitialised** event.)

_Use MySQL as current state storage and emit events to RabbitMQ._

**Minimal requirements**

* Use Symfony
* Apply TDD or BDD
* Documentation and steps that describe how to test the setup
* Produce the best code you can

### Bonus points
- Bonus points for a worker that listens to **DeckDistributed** event and creates a new **Deck** based on a configuration.
- API documentation (Swagger, Raml, ... )
- Docker (docker-compose.yaml)

_**PS:** You are free to use the tools (EventSauce/Broadway/PHPUnit/CodeCeption/Psalm/PhpStan/PHP 7.3, 7.4 or 8) you prefer._

**Questions**

If you have a question please make an issue with the `question` label and mention `@SerkanYildiz` in the issue.

**Timing**

You have 2,5 weeks to accomplish the assignment.

When you are finished, create a `Pull Request` and mention `@SerkanYildiz` in it. 

We will then review your assignment and do a Code Review together.


==============================================================

SETUP
========

A Symfony 4 (LTS) project, with an API skeleton using JWT for user authentication.

## Installation

First off, build the docker images

`docker-compose build`

Run the containers

`docker-compose up -d`

Now shell into the PHP container

`docker-compose exec php-fpm bash`

And install all the dependencies

`composer install`

#### Configuration Parameters

After hitting composer install, you will be prompted to fill in your parameters.
 
You may add the default ones given by hitting enter (as the values are set by Docker config), except for the **mailer parameters**, please update those with your mailer provider.

#### Creating the database schema

Once you've installed the dependencies, you may now create a database and the schema. 

You can do this by running the script, which will create a clean database and schema.

`bash install-clean.sh`

**Note: This script will actually delete any database that's already created, so be careful when using this.

#### Fixtures (todo)

If you want to create the database with some fixtures, you may run the script 

`bash install-import-fixtures.sh`

**Note: This script will actually delete any database that's already created, so be careful when using this.


## Docker

To run the application

`docker-compose up -d`

## Clear Cache

Shell into the PHP container

`docker-compose exec php-fpm bash`

To clear the cache, run the script with the environment parameter

`bash cacl.sh prod`

`bash cacl.sh dev`

## Tests
Shell into the PHP container

`docker-compose exec php-fpm bash`

Then run the script

`bash run-tests.sh`

With coverage

`bash run-tests-coverage.sh`

==============================================================

## DB Schema

![img.png](img.png)

==============================================================

TODO
========
- events with RabbitMQ (for now I am using the normal symfony EventDispatcher)
- Finish covering tests for 100%
- Refactoring some folders structure depreciated
- Add fixtures
- Use a library to do HATEOAS in api
- Change library FOS/user-bundle
- Fix multiple deprecations
- Add more details annotation for doc api
- Create a custom error handling
- Cover more exceptions
- add logs
