---
title: GET users/:id
---

Receives a user ID.
Returns a single object with a status code of `200`.
It provides a user profile with training info.

## Parameters

Parameter   | Type          | Description
---- | ---- | ---- 
id _required_ | Integer, positive   | Unique ID for user

### Example request

```
GET https://api.evscalculator.com/v1/users/12.json
```

### Example response

```json
{

}
```