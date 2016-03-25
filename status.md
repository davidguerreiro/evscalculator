---
title: Status codes
---

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