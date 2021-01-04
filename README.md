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


NOTE :
There is still some 'Todo' for being perfect, that you can check above.
Unfortunately I got an issue to enable amqp extension in my php-fpm container for the RabbitMQ, as it will take times to fix..
I have decided to send events with the Symfony component..

Otherwise, I think I have covered all the assessment.

at the beginning, in order to go faster, I decided to take a skeleton with an authentication system based on JWT (based on FOS/user-bundle.. first bad choice ^^ )
Then I have seen it was an old symfony version so I have upgraded it for the LTS version 4.4. (I was thinking, for a client purpose it might be good having that one..)
I got depreciated info and I have seen that it will be better for sure to change FOS/user-bundle component for an other ; here again it was time consuming so I didn't do it.

You'll like to know that setting up a docker env and setting up a symfony app from scratch and using the doctrine orm was the first time to me :)
I've learned a lot, so thank you for that !

I hope you'll appreciate the code structure, there will be for sure things to say ;
let's have a call to discuss about that !
ps : it could be nice if I will be able to share my screen and doing a demo of the app

See you soon !
