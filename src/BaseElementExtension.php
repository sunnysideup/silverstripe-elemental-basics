<?php

declare(strict_types=1);

namespace Sunnysideup\ElementalBasics\Extensions;

use Dom\Element;
use Fromholdio\ColorPalette\Fields\ColorPaletteField;
use SilverStripe\Core\Extension;
use SilverStripe\Forms\CheckboxField;
use SilverStripe\Forms\DropdownField;
use SilverStripe\Forms\FieldGroup;
use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\LiteralField;
use SilverStripe\Forms\Tab;
use SilverStripe\ORM\FieldType\DBHTMLText;
use Sunnysideup\SelectedColourPicker\Model\Fields\DBBackgroundColour;
use Sunnysideup\SelectedColourPicker\Model\Fields\DBFontColour;

/**
 * Class \Sunnysideup\ElementalBasics\Extensions\BaseElementExtension
 *
 * @property \DNADesign\Elemental\Models\BaseElement|\App\Website\Extension\BaseElementExtension $owner
 */
class BaseElementExtension extends Extension
{

    private static $margin_and_padding_options = [
        // 'none',
        // 'small',
        // 'medium',
        // 'large',
        // 'xlarge',
    ];

    private static $element_width_options = [
        // 'full-width',
        // 'normal-width',
        // 'text-width'
    ];

    private static $db = [
        'ElementBackgroundColour' => 'BackgroundColour',
        'ElementTextColour' => 'FontColour',
        'TopMargin' => 'Varchar(30)',
        'InvertTopMargin' => 'Boolean',
        'TopPadding' => 'Varchar(30)',
        'ElementWidth' => 'Varchar(30)',
        'BottomPadding' => 'Varchar(30)',
        'BottomMargin' => 'Varchar(30)',
        'InvertBottomMargin' => 'Boolean',
    ];

    private static $field_labels = [
        'ElementBackgroundColour' => 'Background Colour',
        'ElementTextColour' => 'Font Colour',
        'TopMargin' => 'Top Margin',
        'InvertTopMargin' => 'Overlap with previous block instead of adding space (invert top margin)?',
        'TopPadding' => 'Top Padding',
        'ElementWidth' => 'Content Width',
        'BottomPadding' => 'Bottom Padding',
        'BottomMargin' => 'Bottom Margin',
        'InvertBottomMargin' => 'Overlap with next block instead of adding space (invert bottom margin)?',
    ];

    private static $field_labels_right = [
        'ElementBackgroundColour' => '',
        'ElementTextColour' => 'This is the colour of text, links, and icons',
        'TopMargin' => 'Space above block',
        'InvertTopMargin' => '',
        'TopPadding' => 'Top space inside block',
        'ElementWidth' => 'Width of content within block',
        'BottomPadding' => 'Bottom space inside block',
        'BottomMargin' => 'Space below block',
        'InvertBottomMargin' => '',
    ];


    /**
     * Small hack!
     * Allow archiving elements even on pages that cannot be deleted (e.g., home page, pages with children)
     *
     * @param $member
     * @return boolean
     */
    public function canDelete($member = null)
    {
        $owner = $this->getOwner();
        return $owner->canEdit($member);
    }

    public function updateCMSFields(FieldList $fields)
    {
        $owner = $this->getOwner();
        $labels = $owner->fieldLabels();
        $labelsRight = $owner->config()->get('field_labels_right') ?: [];

        $options = $owner->config()->get('margin_and_padding_options');
        $marginAndPaddingOptions = array_combine(
            $options,
            $options
        );
        $topMarginValues = $marginAndPaddingOptions;
        $topPaddingValues = $marginAndPaddingOptions;
        $bottomPaddingValues = $marginAndPaddingOptions;
        $bottomMarginValues = $marginAndPaddingOptions;

        if ($owner->hasMethod('getCustomTopMarginValues')) {
            $topMarginValues = $this->makeArrayAssociativeForCMSFields($owner->getCustomTopMarginValues($topMarginValues));
        }
        if ($owner->hasMethod('getCustomTopPaddingValues')) {
            $topPaddingValues = $this->makeArrayAssociativeForCMSFields($owner->getCustomTopPaddingValues($topPaddingValues));
        }
        if ($owner->hasMethod('getCustomBottomPaddingValues')) {
            $bottomPaddingValues = $this->makeArrayAssociativeForCMSFields($owner->getCustomBottomPaddingValues($bottomPaddingValues));
        }
        if ($owner->hasMethod('getCustomBottomMarginValues')) {
            $bottomMarginValues = $this->makeArrayAssociativeForCMSFields($owner->getCustomBottomMarginValues($bottomMarginValues));
        }
        $options = $owner->config()->get('element_width_options');
        $elementWidthValues = array_combine(
            $options,
            $options
        );
        if ($owner->hasMethod('getCustomElementWidthValues')) {
            $elementWidthValues = $owner->getCustomElementWidthValues($elementWidthValues);
        }
        $allowedInvertedMargins = true;
        if ($owner->hasMethod('AllowInvertedMargins')) {
            $allowedInvertedMargins = $owner->AllowInvertedMargins();
        }
        $settings = [
            'TopMargin' => $topMarginValues,
            'TopPadding' => $topPaddingValues,
            'ElementWidth' => $elementWidthValues,
            'BottomPadding' => $bottomPaddingValues,
            'BottomMargin' => $bottomMarginValues,
        ];
        $fieldsToAdd = [];
        foreach ($settings as $fieldName => $values) {
            $fieldsToAddInner = [];
            $fields->removeByName($fieldName);
            if ($fieldName === 'TopMargin' || $fieldName === 'BottomMargin') {
                $fields->removeByName('Invert' . $fieldName);
            }
            if (!empty($values)) {
                //add top ones together
                $label = $labels[$fieldName] ?? $fieldName;
                $fieldsToAddInner[] = DropdownField::create(
                    $fieldName,
                    $label,
                    $values
                )->setDescription(
                    $labelsRight[$fieldName] ?? ''
                );
                if ($fieldName === 'TopMargin' || $fieldName === 'BottomMargin') {
                    $ddInvert = CheckboxField::create(
                        'Invert' . $fieldName,
                        $labels['Invert' . $fieldName] ?? 'Invert ' . $label . ' - overlap with ' . ($fieldName === 'TopMargin' ? 'previous' : 'next') . ' block?'
                    )
                        ->setDescription(
                            $labelsRight['Invert' . $fieldName] ?? ''
                        );
                    $fieldsToAddInner[] = $ddInvert;
                }
            }
            if (! empty($fieldsToAddInner)) {
                $fieldsToAdd[] = FieldGroup::create(
                    $fieldsToAddInner
                );
            }
        }

        if (! empty($fieldsToAdd)) {

            $fields->insertAfter(
                'Main',
                Tab::create(
                    'Spacing',
                    'Spacing',
                    ...$fieldsToAdd
                )
            );
        }


        $backgroundColours = DBBackgroundColour::get_colours_for_dropdown();
        $textColours = DBFontColour::get_colours_for_dropdown();
        if ($owner->hasMethod('getCustomBackgroundColours')) {
            $backgroundColours = $this->makeArrayAssociativeForCMSFields($owner->getCustomBackgroundColours($backgroundColours));
            $backgroundColours = $this->alignColoursForCMSFields($backgroundColours);
        }
        if ($owner->hasMethod('getCustomTextColours')) {
            $textColours = $this->makeArrayAssociativeForCMSFields($owner->getCustomTextColours($textColours));
            $textColours = $this->alignColoursForCMSFields($textColours);
        }
        $fieldsToAdd = [];
        $colours = [
            'ElementBackgroundColour' => $backgroundColours,
            'ElementTextColour' => $textColours,
        ];
        foreach ($colours as $fieldName => $values) {
            if (! empty($values)) {
                $label = $labels[$fieldName] ?? $fieldName;
                $fieldsToAdd[] = ColorPaletteField::create(
                    $fieldName,
                    $label,
                    $values,
                )
                    ->setEmptyString('-- select colour --')
                    ->setDescription(
                        $labelsRight[$fieldName] ?? ''
                    );
            }
            $fields->removeByName($fieldName);
        }

        if (!empty($fieldsToAdd)) {
            $fieldsToAdd[] =
                new LiteralField(
                    'ColourHack',
                    '
                    <style>

                        .ColorPaletteField.colorpalettefield.ColorPaletteField li.ColorPaletteField__color label:before {
                            left: 0 !important;
                        }
                        .ColorPaletteField__color:not([data-colorpalette-label]) {
                            label:before {
                                content: "not set" !important;
                                font-size: 12px!important;
                            }
                        }
                    </style>
                '
                );

            $fields->insertAfter(
                'Main',
                Tab::create(
                    'Colours',
                    'Colours',
                    ...$fieldsToAdd
                )
            );
        }
    }

    public function updateStyleVariant(?string &$styleVariant = null): string
    {
        $styleVariant = (string) $styleVariant;
        $owner = $this->getOwner();
        if ($owner->ElementBackgroundColour) {
            $styleVariant .= ' bg-' . str_replace('#', '', $owner->ElementBackgroundColour);
        }
        if ($owner->ElementTextColour) {
            $styleVariant .= ' text-' . str_replace('#', '', $owner->ElementTextColour);
        }
        if ($owner->TopPadding && $owner->TopPadding !== 'none') {
            $styleVariant .= ' pt-' . $owner->TopPadding;
        }
        if ($owner->BottomPadding && $owner->BottomPadding !== 'none') {
            $styleVariant .= ' pb-' . $owner->BottomPadding;
        }
        if ($owner->ElementWidth && $owner->ElementWidth !== 'none') {
            $styleVariant .= ' ew-' . $owner->ElementWidth;
        }
        if ($owner->TopMargin && $owner->TopMargin !== 'none') {
            $styleVariant .= ' mt-' . $owner->TopMargin;
            if ($owner->InvertTopMargin) {
                $styleVariant .= ' mt-invert';
            }
        }
        if ($owner->BottomMargin && $owner->BottomMargin !== 'none') {
            $styleVariant .= ' mb-' . $owner->BottomMargin;
            if ($owner->InvertBottomMargin) {
                $styleVariant .= ' mb-invert';
            }
        }
        return trim($styleVariant);
    }

    public function getAnchorTitle()
    {
        $owner = $this->getOwner();
        if ($owner->config()->enable_title_in_template) {
            return $owner->getField('Title');
        }
        return 'page-section-' . ($owner->getField('Title') ?: '#' . $owner->ID);
    }
    protected function makeArrayAssociativeForCMSFields(array $list): array
    {
        if ($list === [] || array_keys($list) !== range(0, count($list) - 1)) {
            // already associative or empty
            return $list;
        }

        return array_combine($list, $list);
    }

    protected function alignColoursForCMSFields(array $colours): array
    {
        foreach ($colours as $key => $value) {
            if (!is_array($value)) {
                $colours[$key] = [
                    'label' => $value,
                    'background_css' => $key,
                    'color_css' => '',
                    'sample_text' => 'Aa',
                ];
            }
        }
        return $colours;
    }
}
