# Upgrade to Silverstripe 6

## Dependency Changes

⚠️ **BREAKING CHANGE**: Update `composer.json` to require the following minimum versions:

| Package | SS5 | SS6 |
|---|---|---|
| `silverstripe/framework` | `^5.0` | `^6.0` |
| `silverstripe/admin` | `^2.0` | `^3.0` |
| `dnadesign/silverstripe-elemental` | `^5` | `^6.0` |

🚨 **CRITICAL REVIEW REQUIRED**: `sunnysideup/selected-colour-picker` has been **removed from `require`** because no SS6-compatible release exists yet. If your project depends on this package for colour picker functionality in elements, you cannot complete this upgrade until a compatible version is released. The `yet-to-update` key in `composer.json` tracks this — this is a non-standard key and will be ignored by Composer; it is a placeholder reminder only. Monitor [sunnysideup/selected-colour-picker](https://packagist.org/packages/sunnysideup/selected-colour-picker) for an SS6-compatible release before upgrading projects that use it.

---

## PHP / API Changes

No significant API changes. One internal guard condition in `BaseElementExtension` was tightened from `!empty($fieldsToAdd)` to `$fieldsToAdd !== []` — this has no functional impact under normal usage.
