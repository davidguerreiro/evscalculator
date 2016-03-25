---
title: POST trainings
---

Receives the data necessary for a new training, returns the new training if successful.
If a user is logged in, it will link with the account.

## Parameters

Parameter   | Type            | Description
---- | ---- | ---- 
hp, attack, defense, spattack, spdefense, speed _requires at least one positive_ |  Integer, 1 to 252 | These values represent the stats which are to be trained.
game      |  Numeric, positive    | Numeric ID that represents the game used. 
pokerus     |  Boolean          | False by default.
power_brace   |  Boolean          | False by default.
sturdy_object   |  Boolean          | False by default.
timestamp     |  Integer, positive    | False by default.

### Example request 

```
POST https://api.evscalculator.com/v1/trainings
```

### Example response

The response should be the same as [GET trainings/:id](/trainings/get-trainings-id/), returning the created training.