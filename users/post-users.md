---
title: POST users
---

- Receives the data to create a new user.  
- Returns a single object with a status code of `201` and `Location` header to the new resource.
- It creates an user and responds with the data for that new user.

## Parameters

Parameter     | Type          | Description
---- | ---- | ---- 
email _required_  | String        | Email of the user.
id_training     | Integer, positive   | Refers to the training ID (if any)
timestamp       | Integer, positive   | False by default.


### Example request

```
GET {{ site.api_url }}/v1/users
```

### Example response

```json
{

}
```