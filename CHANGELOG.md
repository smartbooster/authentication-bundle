CHANGELOG for 1.x
===================

## v1.1.4 - (2021-10-14)

### Updated

- [[#35](https://github.com/smartbooster/authentication-bundle/issues/35)] Update dependencies to allow SF5

## v1.1.3 - (2021-07-06)

### Added

- [[#33](https://github.com/smartbooster/authentication-bundle/issues/33)] Add a ControllerTrait for the admin.extension.action_send_account_creation_email

## v1.1.2 - (2021-05-04)

### Added

- [[#30](https://github.com/smartbooster/authentication-bundle/pull/30)] Add NelmioSecurityBundle dependancie to enhance security overall
- [[#30](https://github.com/smartbooster/authentication-bundle/pull/30)] Add autocomplete="off" on security form

### Fixed

- [[#31](https://github.com/smartbooster/authentication-bundle/issues/31)] Fix default bundle config loading by using prependExtensionConfig

## v1.1.1 - (2021-04-20)

### Added

- [[#25](https://github.com/smartbooster/authentication-bundle/issues/25)] Add Impersonate Extension
- [[#28](https://github.com/smartbooster/authentication-bundle/issues/28)] Add recurrent config for SonataAdminBundle & YokaiSecurityTokenBundle
- [[#24](https://github.com/smartbooster/authentication-bundle/issues/24)] Add an action to notify new user

### Changed

- [[#22](https://github.com/smartbooster/authentication-bundle/issues/22)] Migrate from travis to github actions
- [[#26](https://github.com/smartbooster/authentication-bundle/issues/26)] Email not found on the forgotPassword page doesn't redirect to login page anymore

### Fixed

- [[#22](https://github.com/smartbooster/authentication-bundle/issues/22)] Fix Impersonate translation

## v1.1.0 - (2020-11-17)

### Changed

- [[#21](https://github.com/smartbooster/authentication-bundle/pull/21)] Replace yokai/messenger-bundle by symfony/mailer

## v1.0.3 - (2020-01-28)

### Added

- [[#19](https://github.com/smartbooster/authentication-bundle/pull/19)] Add IsPasswordSafe constraints
- [[#18](https://github.com/smartbooster/authentication-bundle/pull/18)] Refactor Userprocessor to use Fidry\AliceDataFixtures\ProcessorInterface
- Migrate to sf 4.4 action namming with 3.4 compatibility

## v1.0.2 - (2020-01-06)

### Fixed

- [[#10](https://github.com/smartbooster/authentication-bundle/issues/10)] Add message when user is unknown on forgot password

### Changed

### Added

- Add QA tools (without phpstan will be added next release)
