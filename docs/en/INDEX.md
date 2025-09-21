# tl;dr

Adds padding, margin and background / foreground color to any element.


## Background colours

Make sure you review the associated colour module on how to implement colours!

See `sunnysideup/selected-colour-picker` module.

## Margin and padding options

```php

class MyDataObject extends DataObject 
{
    public function getCustomTopMarginValues(array $values): array
    {
        return [
            'xxx-large' => 'XXXL'
        ];
    }
    public function getCustomTopPaddingValues(array $values): array
    {
        return [
            'xxx-large' => 'XXXL'
        ];
    }
    public function getCustomBottomMarginValues(array $values): array
    {
        return [
            'xxx-large' => 'XXXL'
        ];
    }
    public function getCustomBottomPaddingValues(array $values): array
    {
        return [
            'xxx-large' => 'XXXL'
        ];
    }
}
```
