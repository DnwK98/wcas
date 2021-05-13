<?php

declare(strict_types=1);

namespace App\Page\Component;

use App\Page\Component\BackgroundImage\BackgroundImageComponentBuilder;
use App\Page\Component\Html\HtmlComponentBuilder;
use App\Page\Component\None\NoneComponentBuilder;
use App\Page\Component\Page\PageComponentBuilder;
use App\Page\Component\ThreeColumns\ThreeColumnsComponentBuilder;

class ComponentBuilderProvider
{
    /** @var ComponentBuilderInterface[] */
    private array $componentBuilders;

    private NoneComponentBuilder $noneBuilder;

    public function __construct(PageComponentBuilder $pageComponentBuilder,
                                HtmlComponentBuilder $htmlComponentBuilder,
                                ThreeColumnsComponentBuilder $threeColumnsComponent,
                                NoneComponentBuilder $noneComponentBuilder,
                                BackgroundImageComponentBuilder $backgroundImageComponentBuilder)
    {
        $this->noneBuilder = $noneComponentBuilder;

        /** @var ComponentBuilderInterface $builder */
        foreach (func_get_args() as $builder) {
            $this->componentBuilders[$builder->getComponentName()] = $builder;
        }
    }

    public function provide(?string $componentName): ComponentBuilderInterface
    {
        $componentName = $componentName ?? $this->noneBuilder->getComponentName();

        if (!isset($this->componentBuilders[$componentName])) {
            return $this->noneBuilder;
        }

        return $this->componentBuilders[$componentName];
    }
}
