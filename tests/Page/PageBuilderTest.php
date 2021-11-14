<?php

declare(strict_types=1);

namespace App\Tests\Page;

use App\Common\JsonObject\JsonObject;
use App\Page\Component\None\NoneComponent;
use App\Page\Component\Page\PageComponent;
use App\Page\PageFactory;
use App\Tests\TestClass\FunctionalTestCase;

class PageFactoryTest extends FunctionalTestCase
{
    private PageFactory $pageFactory;

    public function setUp(): void
    {
        parent::setUp();
        /** @var PageFactory $pageFactory */
        $pageFactory = $this->container()->get(PageFactory::class);
        $this->pageFactory = $pageFactory;
    }

    public function testPageFactoryBuildsPageObject()
    {
        $json = JsonObject::ofArray([
            'name' => 'PageComponent',
            'children' => [
                ['name' => 'NoneComponent'],
                ['name' => 'NoneComponent'],
            ],
        ]);

        $builtPage = $this->pageFactory->build($json);

        $this->assertInstanceOf(PageComponent::class, $builtPage);
    }

    public function testPageFactoryBuildsNothingOnInvalidJson()
    {
        $json = JsonObject::ofArray([
            'when-name-missing-component-is-invalid' => 'PageComponent',
            'children' => [
                ['name' => 'NoneComponent'],
                ['name' => 'NoneComponent'],
            ],
        ]);

        $builtPage = $this->pageFactory->build($json);

        $this->assertInstanceOf(NoneComponent::class, $builtPage);
    }

    public function testBuildsNoneComponentsWhenInvalidPartialNames()
    {
        $json = JsonObject::ofArray([
            'name' => 'PageComponent',
            'backgroundColor' => '#bbbbbb',
            'children' => [
                ['name' => 'InvalidName'],
                ['name' => 'NoneComponent'],
            ],
        ]);

        $builtPage = $this->pageFactory->build($json);

        $this->assertInstanceOf(PageComponent::class, $builtPage);
    }

    public function testPageFactoryWorksWithNestedModel()
    {
        $json = JsonObject::ofArray([
            'name' => 'PageComponent',
            'backgroundColor' => '#bbbbbb',
            'textColor' => '#000001',
            'children' => [
                ['name' => 'HtmlComponent', 'content' => 'test html'],
                ['name' => 'NoneComponent'],
                [
                    'name' => 'ThreeColumnsComponent',
                    'column1' => ['name' => 'NoneComponent'],
                    'column2' => ['name' => 'NoneComponent'],
                    'column3' => ['name' => 'HtmlComponent', 'content' => 'other test html'],
                ],
                ['name' => 'NoneComponent'],
            ],
        ]);

        $builtPage = $this->pageFactory->build($json);

        $this->assertInstanceOf(PageComponent::class, $builtPage);
//        $this->assertEquals($json->getArray(), $builtPage->jsonSerialize());
    }
}
