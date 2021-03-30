<?php

declare(strict_types=1);

namespace App\Page\Component\ThreeColumns;

use App\Page\Component\AbstractComponent;

class ThreeColumnsComponent extends AbstractComponent
{
    const COLUMN_NAMES = [
        'column1',
        'column2',
        'column3',
    ];

    private AbstractComponent $column1;
    private AbstractComponent $column2;
    private AbstractComponent $column3;

    public function __construct(AbstractComponent $column1, AbstractComponent $column2, AbstractComponent $column3)
    {
        $this->column1 = $column1;
        $this->column2 = $column2;
        $this->column3 = $column3;
    }

    public function render(): string
    {
        return $this->outputGet(function () {
            require __DIR__ . '/view.phtml';
        });
    }

    public function jsonSerialize(): array
    {
        return [
            'name' => 'ThreeColumnsComponent',
            'column1' => $this->column1->jsonSerialize(),
            'column2' => $this->column2->jsonSerialize(),
            'column3' => $this->column3->jsonSerialize(),
        ];
    }
}
