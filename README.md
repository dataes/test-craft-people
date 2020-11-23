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