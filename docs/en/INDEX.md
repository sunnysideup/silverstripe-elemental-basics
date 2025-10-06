# tl;dr

Adds padding, margin and background / foreground color to any element.


## Background colours

Make sure you review the associated colour module on how to implement colours!

See `sunnysideup/selected-colour-picker` module.

## set up defaults

```yml
---
name: ElementalBasicsConfiguration
---
Sunnysideup\ElementalBasics\Extensions\BaseElementExtension:
  margin_and_padding_options:
    - none
    - small
    - medium
    - large
    - xlarge
  element_width_options:
    - full-width
    - normal
    - text-width


```

## Customisation per elemental

```php

class MyDataObject extends DataObject 
{

    private static $defaults = [
        'ElementBackgroundColour' => '#ffffff',
        'ElementTextColour' => '#000000',
        'TopMargin' => 'none',
        'TopPadding' => 'medium',
        'ElementWidth' => 'full-width',
        'BottomPadding' => 'medium',
        'BottomMargin' => 'none',
    ];

    public function getCustomBackgroundColours($array $values): array
    {
        return [
            '#aaaaaa' => 'light grey'
        ];
    }

    public function getCustomTextColours($array $values): array
    {
        return [
            '#333333' => 'dark grey'
        ];
    }

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

    public function getCustomElementWidthValues(array $values): array
    {
        return [
            'full-width' => 'full width'
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

Here is a css example that you may want to set up to cover the colours and the like.

```css
/* Background colours */
.bg-000000 { background-color: rgb(0, 0, 0); }       /* black */
.bg-ffffff { background-color: rgb(255, 255, 255); } /* white */
.bg-ff0000 { background-color: rgb(255, 0, 0); }     /* red */

/* Text colours */
.text-000000, .text-000000 * { color: rgb(0, 0, 0); }
.text-ffffff, .text-ffffff * { color: rgb(255, 255, 255); }
.text-ff0000, .text-ff0000 * { color: rgb(255, 0, 0); }

:root {
  --space-none: 0;
  --space-small: 0.5rem;
  --space-medium: 1rem;
  --space-large: 2rem;
  --space-xlarge: 4rem;
}

/* Padding top */
.pt-none { padding-top: var(--space-none); }
.pt-small { padding-top: var(--space-small); }
.pt-medium { padding-top: var(--space-medium); }
.pt-large { padding-top: var(--space-large); }
.pt-xlarge { padding-top: var(--space-xlarge); }

/* Padding bottom */
.pb-none { padding-bottom: var(--space-none); }
.pb-small { padding-bottom: var(--space-small); }
.pb-medium { padding-bottom: var(--space-medium); }
.pb-large { padding-bottom: var(--space-large); }
.pb-xlarge { padding-bottom: var(--space-xlarge); }

/* Margin top */
.mt-none { margin-top: var(--space-none); }
.mt-small { margin-top: var(--space-small); }
.mt-medium { margin-top: var(--space-medium); }
.mt-large { margin-top: var(--space-large); }
.mt-xlarge { margin-top: var(--space-xlarge); }
.mt-small-invert { margin-top: calc(var(--space-small) * -1); }
.mt-medium-invert { margin-top: calc(var(--space-medium) * -1); }
.mt-large-invert { margin-top: calc(var(--space-large) * -1); }
.mt-xlarge-invert { margin-top: calc(var(--space-xlarge) * -1); }

/* Margin bottom */
.mb-none { margin-bottom: var(--space-none); }
.mb-small { margin-bottom: var(--space-small); }
.mb-medium { margin-bottom: var(--space-medium); }
.mb-large { margin-bottom: var(--space-large); }
.mb-xlarge { margin-bottom: var(--space-xlarge); }
.mb-small-invert { margin-bottom: calc(var(--space-small) * -1); }
.mb-medium-invert { margin-bottom: calc(var(--space-medium) * -1); }
.mb-large-invert { margin-bottom: calc(var(--space-large) * -1); }
.mb-xlarge-invert { margin-bottom: calc(var(--space-xlarge) * -1); }
```
