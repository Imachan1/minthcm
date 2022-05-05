<?php
namespace Api\V8\Param;

use Api\V8\Param\Options as ParamOption;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CalendarDataParams extends BaseParam
{
    public function getId()
    {
        return $this->parameters['id'];
    }

    public function getDate()
    {
        return $this->parameters['date'];
    }

    protected function configureParameters(OptionsResolver $resolver)
    {
        $this->setOptions(
            $resolver,
            [
                ParamOption\Id::class,
                ParamOption\Date::class
            ]
        );
    }
}
