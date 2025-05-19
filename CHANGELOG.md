# CHANGELOG - SEworqs Commons Cache

## v2.0.0
Fully rewritten on top of `symfony_cache`.

### Added
- New `CacheManager` with PSR-16 per-namespace cache support
- Stand-alone + Laminas-compatible `CacheManagerFactory`
- TTL handling per adapter
- ArrayAdapter and FilesystemAdapter support

### Changed
- Removed Laminas cache support
- Removed `NamespaceCacheService`
- Removed custom `CacheInterface` in favor of PSR-16

### Fixed
- TTL expiry tests added
- Namespace isolation guaranteed across adapters
