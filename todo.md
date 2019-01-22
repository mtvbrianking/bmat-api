Pending...
--

**User authentication**

1. Client credentials client app

```bash
curl -X POST http://localhost:8000/oauth/token \
  -H 'Accept: application/json' \
  -F 'grant_type=client_credentials' \
  -F 'client_id=af560d6a-f9f3-474c-a233-bbff07be5621' \
  -F 'client_secret=PpB9lnqRVnwqWpZSzEeaIvQMJl1X621C2YDFNmfK'
```

Response

```json
{
  "token_type": "Bearer",
  "expires_in": 86400,
  "access_token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiIsImp0aSI6ImY0ZDJiYTE4NTA0YTA0NDZlZGFiMGFhYTNkNzU1NGNjMDI1ODgwZGVmMzhlZDI3YzUyYjlhYWY2ZjI0NzJhNmIxZTI2NzI2OWI1NDdhNzFmIn0.eyJhdWQiOiJhZjU2MGQ2YS1mOWYzLTQ3NGMtYTIzMy1iYmZmMDdiZTU2MjEiLCJqdGkiOiJmNGQyYmExODUwNGEwNDQ2ZWRhYjBhYWEzZDc1NTRjYzAyNTg4MGRlZjM4ZWQyN2M1MmI5YWFmNmYyNDcyYTZiMWUyNjcyNjliNTQ3YTcxZiIsImlhdCI6MTU0ODE1MjYyMiwibmJmIjoxNTQ4MTUyNjIyLCJleHAiOjE1NDgyMzkwMjIsInN1YiI6IiIsInNjb3BlcyI6W119.HDmw0XSD_S_leTzrNWIhi_s4jujwitRmpko4IlGj10bbLfKQ3LG4INHZCVVAcAsS1212O7ZEXLwp1UicxPLF6Bp2vSPwm47iMxreuRu98p3KeEpFiFMpQ9lGwoq3gBgDzFlEUgqV901323xUa4G7cZ1TsHbEtwafp0qSm2G4-kd4J9O6cTjxRcDF0M6s4tnbtrdduGTS9IxLq_ihkTqtUiMVm8uItKJuNd_7Xs-9wye1T7bV-MU9TrOxGp2FqfxbAIWFMnqmsvVTBldQmvGtYwYx4vHhpsXSE_cBCXiD_InRM6YPXlRGhPZThp_5KHtvpl_Y7m0WMoumpqDFB60CJtYE25j2g3eyYBhrlVbwS3A0OqtEJC6eIgs-U8kzqEErAJVlWqAHaYU0KZEo1XdriVxDI4n-ntxu-ufZlU9_hY8Rz0qsDK0raAbnqWU4YkFstU0osLEDvwG0oTJFYCoKgo5BbPem8lDz5xWDbAE8o02fiZw1dioQWaxuxfsvKcOIgHleaQOJKfuBb3pj97pzOiiH_ZHWToF2WofbvefxyELXbb8aVr_kkY0NGo5vo_ryE68c741J4_IrBqsJuYlMi21UXH64mbwjLXsNs6vnus6duH3s4sA80V8Fm6c_4RYHw3J_Uruwo1pDbNn8qr4QndnZbidGfYtBdONVVzdqEgM"
}
```

2. Authenticate user

**Note**: Only password grant clients can authenticate a user using his/her password. Other client app will be declined.

```bash
curl -X POST \
  http://localhost:8000/api/v1/users/auth \
  -H 'Accept: application/json' \
  -H 'Authorization: Bearer {CLIENT_APP_ACCESS_TOKEN}' \
  -F 'email=jdoe@example.com' \
  -F 'password=123456'
```

Request:

```bash
curl -X POST \
  http://localhost:8000/api/v1/users/auth \
  -H 'Accept: application/json' \
  -H 'Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiIsImp0aSI6ImY0ZDJiYTE4NTA0YTA0NDZlZGFiMGFhYTNkNzU1NGNjMDI1ODgwZGVmMzhlZDI3YzUyYjlhYWY2ZjI0NzJhNmIxZTI2NzI2OWI1NDdhNzFmIn0.eyJhdWQiOiJhZjU2MGQ2YS1mOWYzLTQ3NGMtYTIzMy1iYmZmMDdiZTU2MjEiLCJqdGkiOiJmNGQyYmExODUwNGEwNDQ2ZWRhYjBhYWEzZDc1NTRjYzAyNTg4MGRlZjM4ZWQyN2M1MmI5YWFmNmYyNDcyYTZiMWUyNjcyNjliNTQ3YTcxZiIsImlhdCI6MTU0ODE1MjYyMiwibmJmIjoxNTQ4MTUyNjIyLCJleHAiOjE1NDgyMzkwMjIsInN1YiI6IiIsInNjb3BlcyI6W119.HDmw0XSD_S_leTzrNWIhi_s4jujwitRmpko4IlGj10bbLfKQ3LG4INHZCVVAcAsS1212O7ZEXLwp1UicxPLF6Bp2vSPwm47iMxreuRu98p3KeEpFiFMpQ9lGwoq3gBgDzFlEUgqV901323xUa4G7cZ1TsHbEtwafp0qSm2G4-kd4J9O6cTjxRcDF0M6s4tnbtrdduGTS9IxLq_ihkTqtUiMVm8uItKJuNd_7Xs-9wye1T7bV-MU9TrOxGp2FqfxbAIWFMnqmsvVTBldQmvGtYwYx4vHhpsXSE_cBCXiD_InRM6YPXlRGhPZThp_5KHtvpl_Y7m0WMoumpqDFB60CJtYE25j2g3eyYBhrlVbwS3A0OqtEJC6eIgs-U8kzqEErAJVlWqAHaYU0KZEo1XdriVxDI4n-ntxu-ufZlU9_hY8Rz0qsDK0raAbnqWU4YkFstU0osLEDvwG0oTJFYCoKgo5BbPem8lDz5xWDbAE8o02fiZw1dioQWaxuxfsvKcOIgHleaQOJKfuBb3pj97pzOiiH_ZHWToF2WofbvefxyELXbb8aVr_kkY0NGo5vo_ryE68c741J4_IrBqsJuYlMi21UXH64mbwjLXsNs6vnus6duH3s4sA80V8Fm6c_4RYHw3J_Uruwo1pDbNn8qr4QndnZbidGfYtBdONVVzdqEgM' \
  -F 'email=jdoe@example.com' \
  -F 'password=123456'  
```

Response:

```json
{
  "id": "574819d1-051a-405e-ae26-eb319b9cee9e",
  "name": "jdoe",
  "email": "jdoe@example.com",
  "email_verified_at": "2018-11-16 14:03:17",
  "created_at": "2018-11-16 14:03:17",
  "updated_at": "2018-11-16 14:03:17",
  "deleted_at": null,
  "token": {
    "token_type": "Bearer",
    "expires_in": 86400,
    "access_token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiIsImp0aSI6ImI1YmZkYjVmZTU0MTdjMDIwNzM2OGJlZDRhZTI2MjlmZTVjOThjMTBlYjMyYTkxYTYzZjA4OTMyNjdiMDI2N2UzMTgzYjdmZTkzYzY0NzE2In0.eyJhdWQiOiJhZjU2MGQ2YS1mOWYzLTQ3NGMtYTIzMy1iYmZmMDdiZTU2MjEiLCJqdGkiOiJiNWJmZGI1ZmU1NDE3YzAyMDczNjhiZWQ0YWUyNjI5ZmU1Yzk4YzEwZWIzMmE5MWE2M2YwODkzMjY3YjAyNjdlMzE4M2I3ZmU5M2M2NDcxNiIsImlhdCI6MTU0ODE1MjgwOSwibmJmIjoxNTQ4MTUyODA5LCJleHAiOjE1NDgyMzkyMDksInN1YiI6IjU3NDgxOWQxLTA1MWEtNDA1ZS1hZTI2LWViMzE5YjljZWU5ZSIsInNjb3BlcyI6W119.MsQKh5qRdiSma1W709PS5qhB_pLnRAxVO83HygJllN8cuORxXlxUmYhN3TMVG8JkM1JKastKWtLceFYttMmFU57oaGmOW25sOWOdGWf3fUUnmQsXIVhlENkVfrf16JN_FRM2koB-EVrbD5b10pvMj0kgX7kWFMiAlUx6xWXvnILFAQSB5esfrM4ecJ7tYyNoWw9K2F6zzjsWfYMsxMEFuPDKwZ12UbJyGs8bTylvlPrGDeOZLcdz3bW3gGNmGFls3OzBZzDRMg90ogS1LPL4o-nPMLZFqwH2GZIa1suJLaZt9HoLVLqVWrwgpLK433a64E9cH7Wnj5ERD_T3GRt76XOiADlnTJVRee8Ps7dp4fuHOfMhShFsdsGpy5966-S0NSniScCzLIcWLJ8xh8Vunb2Q174wp937mRV2zkqyKWiq7jNveiYkMrBRMIPshfqPykiOxLocaLEa26Jus87QkINIb9oLHOVtSTkvk3zr5Wq83Y823A4fzWXsxN49k2Kl9NCFBzP0mGn7w1O9JJjM0IR-OR2dzUz2w4w9OIv_bHGqSUlGdz1ZddD_pWzZv2ufd-Ist-4c5Na0gBT4tkJmNz1FgODVfgEpwgH7XFSXnhGchVhblkxpS8vhmzPSYEhlLAaVEZE05-Hhq7yX4StkOmjlIvNgXqVjZLMkNf4LuhY",
    "refresh_token": "def5020079fba4f2f41f9274fa181637b0340b627e70b49b6265c5889ecede1cb2b2b4f9c75d0ec57384823c2a753b6e1265d2d577b0be04128972f21fb6d06d24895db6ac70dcbb727a993bdcba8c7c152f2b161255a906c9c1db6b177f40c92eb28606ddab6386cd3877d15297d0d15bbd040a9dcd82b56bfe5e9cdccb61b4a753d6603a8d8b2be2b7371446213e8785a4f21e1dd76ac2c69135f51fb4672a57fe26ef55e391e98809d077556dd88bbc0ab05225336534da941bcde4613b53df1b9d7c9f41b42d39faaf0c812b400512e999ebd287c0599d8d2a1757da4c97d6ef4c9b0aff9ee6a27c70d4885fcb55618543986fd96921ed9bd234e2a598663dcde2a5191b8188456892dbc7f25aa1fed22fc5ca00f020fa224a816ea734f03a2d4f237fdb533b5d68b18f9b127f4858caeb4f2744b9525d1bbce14061f901179ecfc0946070a2db37b01b9c820250ca23ed65dcf56308af9d73212d334370f76d9578c7beda5b1d40c6610e74bcf873d3748d19644d46bc253b66b672d384225fb5e0ed6966c7ae26faf69f150eae45b25fde937dc31d13da84f0b52999d73da9e4b2b9d9732804"
  }
}
```

3. Logout user

```bash
curl -X POST \
  http://localhost:8000/api/v1/users/logout \
  -H 'Accept: application/json' \
  -H 'Authorization: Bearer {USER_ACCESS_TOKEN}'
```

Request:

```bash
curl -X POST \
  http://localhost:8000/api/v1/users/logout \
  -H 'Accept: application/json' \
  -H 'Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiIsImp0aSI6ImI1YmZkYjVmZTU0MTdjMDIwNzM2OGJlZDRhZTI2MjlmZTVjOThjMTBlYjMyYTkxYTYzZjA4OTMyNjdiMDI2N2UzMTgzYjdmZTkzYzY0NzE2In0.eyJhdWQiOiJhZjU2MGQ2YS1mOWYzLTQ3NGMtYTIzMy1iYmZmMDdiZTU2MjEiLCJqdGkiOiJiNWJmZGI1ZmU1NDE3YzAyMDczNjhiZWQ0YWUyNjI5ZmU1Yzk4YzEwZWIzMmE5MWE2M2YwODkzMjY3YjAyNjdlMzE4M2I3ZmU5M2M2NDcxNiIsImlhdCI6MTU0ODE1MjgwOSwibmJmIjoxNTQ4MTUyODA5LCJleHAiOjE1NDgyMzkyMDksInN1YiI6IjU3NDgxOWQxLTA1MWEtNDA1ZS1hZTI2LWViMzE5YjljZWU5ZSIsInNjb3BlcyI6W119.MsQKh5qRdiSma1W709PS5qhB_pLnRAxVO83HygJllN8cuORxXlxUmYhN3TMVG8JkM1JKastKWtLceFYttMmFU57oaGmOW25sOWOdGWf3fUUnmQsXIVhlENkVfrf16JN_FRM2koB-EVrbD5b10pvMj0kgX7kWFMiAlUx6xWXvnILFAQSB5esfrM4ecJ7tYyNoWw9K2F6zzjsWfYMsxMEFuPDKwZ12UbJyGs8bTylvlPrGDeOZLcdz3bW3gGNmGFls3OzBZzDRMg90ogS1LPL4o-nPMLZFqwH2GZIa1suJLaZt9HoLVLqVWrwgpLK433a64E9cH7Wnj5ERD_T3GRt76XOiADlnTJVRee8Ps7dp4fuHOfMhShFsdsGpy5966-S0NSniScCzLIcWLJ8xh8Vunb2Q174wp937mRV2zkqyKWiq7jNveiYkMrBRMIPshfqPykiOxLocaLEa26Jus87QkINIb9oLHOVtSTkvk3zr5Wq83Y823A4fzWXsxN49k2Kl9NCFBzP0mGn7w1O9JJjM0IR-OR2dzUz2w4w9OIv_bHGqSUlGdz1ZddD_pWzZv2ufd-Ist-4c5Na0gBT4tkJmNz1FgODVfgEpwgH7XFSXnhGchVhblkxpS8vhmzPSYEhlLAaVEZE05-Hhq7yX4StkOmjlIvNgXqVjZLMkNf4LuhY'
```

--

**Registration**

| Method | URI                    | Name                | Action                                               | Middleware                   |
|:-------|:-----------------------|:--------------------|:-----------------------------------------------------|:-----------------------------|
| GET    | register               | register            | ..\Auth\RegisterController@showRegistrationForm      | web,guest                    |
| POST   | register               |                     | ..\Auth\RegisterController@register                  | web,guest                    |

**Email verification**

| Method | URI                    | Name                | Action                                               | Middleware                   |
|:-------|:-----------------------|:--------------------|:-----------------------------------------------------|:-----------------------------|
| GET    | email/verify           | verification.notice | ..\Auth\VerificationController@show                  | web,auth                     |
| GET    | email/verify/{id}      | verification.verify | ..\Auth\VerificationController@verify                | web,auth,signed,throttle:6,1 |
| GET    | email/resend           | verification.resend | ..\Auth\VerificationController@resend                | web,auth,throttle:6,1        |

**Authentication**

| Method | URI                    | Name                | Action                                               | Middleware                   |
|:-------|:-----------------------|:--------------------|:-----------------------------------------------------|:-----------------------------|
| GET    | login                  | login               | ..\Auth\LoginController@showLoginForm                | web,guest                    |
| POST   | login                  |                     | ..\Auth\LoginController@login                        | web,guest                    |
| POST   | logout                 | logout              | ..\Auth\LoginController@logout                       | web                          |

**Password reset**

| Method | URI                    | Name                | Action                                               | Middleware                   |
|:-------|:-----------------------|:--------------------|:-----------------------------------------------------|:-----------------------------|
| GET    | password/reset         | password.request    | ..\Auth\ForgotPasswordController@showLinkRequestForm | web,guest                    |
| POST   | password/email         | password.email      | ..\Auth\ForgotPasswordController@sendResetLinkEmail  | web,guest                    |
| GET    | password/reset/{token} | password.reset      | ..\Auth\ResetPasswordController@showResetForm        | web,guest                    |
| POST   | password/reset         | password.update     | ..\Auth\ResetPasswordController@reset                | web,guest                    |
