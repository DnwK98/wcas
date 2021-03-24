<?php

namespace App\Page\Component\ThreeColumns;

use App\Page\Component\AbstractComponent;
use App\Page\Component\ComponentInterface;

class ThreeColumnsComponent extends AbstractComponent implements ComponentInterface
{
    const COLUMN_NAMES = [
        'column1',
        'column2',
        'column3'
    ];

    private ComponentInterface $column1;
    private ComponentInterface $column2;
    private ComponentInterface $column3;

    public function __construct(ComponentInterface $column1, ComponentInterface $column2, ComponentInterface $column3)
    {
        $this->column1 = $column1;
        $this->column2 = $column2;
        $this->column3 = $column3;
    }

    public function render(): string
    {
        return $this->outputGet(function (){
            require __DIR__ . '/view.phtml';
        });
    }

    public function jsonSerialize()
    {
        return [
            'name' => 'ThreeColumnsComponent',
            'column1' => $this->column1->jsonSerialize(),
            'column2' => $this->column2->jsonSerialize(),
            'column3' => $this->column3->jsonSerialize(),
        ];
    }
}