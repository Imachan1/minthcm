<?php

namespace eVolpe\Suite;

class CustomLocaleSettingsPage extends LocaleSettingsPage
{

    public function setElements()
    {
        parent::setElements();
        $elements = array(
            'fields' => array(
                'Currency on right' => array(
                    'selector' => array(
                        'type' => 'css',
                        'value' => 'input[name=currency_on_right]:not([type=hidden])'
                     ),
                    'type' => 'Checkbox',
                ),
            ),
        );

        $this->elements = array_merge($this->elements, $elements);
    }
}
