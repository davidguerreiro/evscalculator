---
title: POST trainings
---


Receives the data necessary for a new training, returns information and IDs about it if successful.

## Parameters

Parameter   | Type            | Description
----      | ----            | ----
id_user     | Integer, 0 or positive  | If a user is logged in, then we send her ID.
hp, attack, defense, spattack, spdefense, speed _requires at least one positive_ |  Integer, 1 to 252 | These values represent the stats which are to be trained.
game      |  Numeric, positive    | Numeric ID that represents the game used. 
pokerus     |  Boolean          | False by default.
power_brace   |  Boolean          | False by default.
sturdy_object   |  Boolean          | False by default.
timestamp     |  Integer, positive    | False by default.

Example: `https://api.evscalculator.com/v1/trainings.json`