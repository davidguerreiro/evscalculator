---
title: The response
implemented: true
---

Depending on the request and the conditions, the API would return a number of different errors.


## Status codes 

There are a number of status codes that you need to consider when receiving the response from the API.

- `200`: GET resource is successful and returns data.
- `201`: POST request is successful and returns created data.
- `204`: DELETE request is successful and returns empty response.
- `301`: The endpoint has changed permanently, returns new location header.
- `314`: Response format (csv, for example) is not available.
- `400`: Validation error.
- `403`: Forbidden request. You don't have the permissions.
- `404`: Endpoint or data not found.
- `405`: Endpoint is found but not available for that method.
- `409`: POST request for data already found (duplicated content/double submit).


## The body

With the exception of successful `DELETE` requests, the API will always return an object with at least **3 properties**.

For example:

```json
{
	"stat": "ok",
	"count": 55,
	"data": [
		{
			"id": 21,
			"name": "Graveler",
			"stat_name": "defense",
			"stat_value": 10,
			"game": 0
		},
		[ … ]
	]
}
```

Both `stat` and `count` will always be available. The values can change depending on the following:

### If the request is **successful**.

- `stat` will be `ok`.
- `data` will be available.
- `count` will be the number of elements in `data`.

### If the request causes an **error**.

- `stat` will be `error`.
- `data` will NOT be availabe.
- `errors` will be available.
- `count` will be the number of elements in `errors`.

### If some of the data is **invalid** (validation).

- `stat` will be `invalid`.
- `data` may be available with only the valid data, depending on the request.
- `errors` will be available with the validation tips.
- `count` will be the number of elements in `errors`.

This way you can assume almost every request will have a `stat` and `count` you can base your code on.
The `count` will help you abstract the calculation of the length of the array. 

Bear in mind that `data` will return always an object array or a single object (if `count` equals `1`).
On the other hand, `errors` will always return an array of strings, no matter the amount.
