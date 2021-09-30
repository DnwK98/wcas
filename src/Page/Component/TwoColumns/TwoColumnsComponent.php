<?php

declare(strict_types=1);

namespace App\Page\Component\TwoColumns;

use App\Page\Component\AbstractComponent;

class TwoColumnsComponent extends AbstractComponent
{
    const COLUMN_NAMES = [
        'column1',
        'column2',
    ];

    private AbstractComponent $column1;
    private AbstractComponent $column2;

    public function __construct(AbstractComponent $column1, AbstractComponent $column2)
    {
        $this->column1 = $column1;
        $this->column2 = $column2;
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
            'name' => 'TwoColumnsComponent',
            'column1' => $this->column1->jsonSerialize(),
            'column2' => $this->column2->jsonSerialize(),
        ];
    }
}
