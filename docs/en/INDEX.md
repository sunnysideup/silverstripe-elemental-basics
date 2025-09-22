# tl;dr

Adds padding, margin and background / foreground color to any element.


## Background colours

Make sure you review the associated colour module on how to implement colours!

See `sunnysideup/selected-colour-picker` module.

## set up defaults

```yml

Sunnysideup\ElementalBasics\Extensions\BaseElementExtension:
  margin_and_padding_options:
    - small
    - medium
    - large
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

    public function getCustomElementWidthValues()
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

/* Padding top */
.pt-none    { padding-top: 0; }
.pt-small   { padding-top: 0.5rem; }
.pt-medium  { padding-top: 1rem; }
.pt-large   { padding-top: 2rem; }
.pt-xlarge  { padding-top: 4rem; }

/* Padding bottom */
.pb-none    { padding-bottom: 0; }
.pb-small   { padding-bottom: 0.5rem; }
.pb-medium  { padding-bottom: 1rem; }
.pb-large   { padding-bottom: 2rem; }
.pb-xlarge  { padding-bottom: 4rem; }

/* Margin top */
.mt-none         { margin-top: 0; }
.mt-small        { margin-top: 0.5rem; }
.mt-medium       { margin-top: 1rem; }
.mt-large        { margin-top: 2rem; }
.mt-xlarge       { margin-top: 4rem; }
.mt-small-invert { margin-top: -0.5rem; }
.mt-medium-invert{ margin-top: -1rem; }
.mt-large-invert { margin-top: -2rem; }
.mt-xlarge-invert{ margin-top: -4rem; }

/* Margin bottom */
.mb-none         { margin-bottom: 0; }
.mb-small        { margin-bottom: 0.5rem; }
.mb-medium       { margin-bottom: 1rem; }
.mb-large        { margin-bottom: 2rem; }
.mb-xlarge       { margin-bottom: 4rem; }
.mb-small-invert { margin-bottom: -0.5rem; }
.mb-medium-invert{ margin-bottom: -1rem; }
.mb-large-invert { margin-bottom: -2rem; }
.mb-xlarge-invert{ margin-bottom: -4rem; }
```
