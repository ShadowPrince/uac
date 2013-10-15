## U[ser]ac[counts]
Uac is component for [slimext](http://github.com/shadowprince/slimext).
Provides user accounts features - creating, managing, authorization and more.

That component does not provide any views, just functions. 
[Typical usage example](http://github.com/shadowprince/uac/wiki/Example)

## Cookie authorization
If there is `"uac_sessid"` cookie (with session identifier), session receive from database, and if it's not expired, user is not deleted or not deactivated it becomes authorized, else session removes from database.

## Uac models
### User
General data - username, password-hash, status, permissions, date joined. Used for authorization, permission system.
### UserProfile
UserProfile (returned by `profile()` method of User) is model of class from application config (`"uac.user_profile"`)for additional data like email, reputation, etc.
### AuthSession
Information about user authorization sessions - each user can have unlimited count of sessions, one per cookie set.
