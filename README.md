# CarbonNetwork Core BackOffice

## Authoritative player locations

The authenticated player-location API stores one authoritative destination per
`(player_uuid, gamemode)`. `POST /api/player-location` validates that the player
already exists and uses an idempotent upsert, while
`GET /api/player-location/{gamemode}/{uuid}` supplies the saved server to the
Velocity routing flow.

Onboarding completes players in this order:

```text
transactional player defaults -> authoritative location -> COMPLETED
    -> GAMEMODE_TRANSFER -> validated proxy connection -> Redis route cache
```

BackOffice is the route authority. The onboarding plugin does not seed the
Redis cache directly; Velocity populates its expiring cache only after a
successful connection. Retrying a failed location write updates the same
composite row and cannot create a duplicate route.
