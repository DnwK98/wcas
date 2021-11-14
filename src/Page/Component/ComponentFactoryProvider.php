<?php

declare(strict_types=1);

namespace App\Page\Component;

use App\Page\Component\BackgroundImage\BackgroundImageComponentFactory;
use App\Page\Component\Html\HtmlComponentFactory;
use App\Page\Component\Image\ImageComponentFactory;
use App\Page\Component\Margins\MarginsComponentFactory;
use App\Page\Component\None\NoneComponentFactory;
use App\Page\Component\Page\PageComponentFactory;
use App\Page\Component\ThreeColumns\ThreeColumnsComponentFactory;
use App\Page\Component\TwoColumns\TwoColumnsComponentFactory;
use App\Page\Component\YouTube\YouTubeComponentFactory;

class ComponentFactoryProvider
{
    /** @var ComponentFactoryInterface[] */
    private array $componentFactories;

    private NoneComponentFactory $noneFactory;

    public function __construct(PageComponentFactory $pageComponentFactory,
                                HtmlComponentFactory $htmlComponentFactory,
                                TwoColumnsComponentFactory $twoColumnsComponentFactory,
                                ThreeColumnsComponentFactory $threeColumnsComponent,
                                NoneComponentFactory $noneComponentFactory,
                                BackgroundImageComponentFactory $backgroundImageComponentFactory,
                                MarginsComponentFactory $marginsComponentFactory,
                                ImageComponentFactory $imageComponentFactory,
                                YouTubeComponentFactory $youTubeComponentFactory)
    {
        $this->noneFactory = $noneComponentFactory;

        /** @var ComponentFactoryInterface $factory */
        foreach (func_get_args() as $factory) {
            $this->componentFactories[$factory->getComponentName()] = $factory;
        }
    }

    public function provide(?string $componentName): ComponentFactoryInterface
    {
        $componentName = $componentName ?? $this->noneFactory->getComponentName();

        if (!isset($this->componentFactories[$componentName])) {
            return $this->noneFactory;
        }

        return $this->componentFactories[$componentName];
    }
}
