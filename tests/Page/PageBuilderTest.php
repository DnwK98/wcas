<?php

declare(strict_types=1);

namespace App\Tests\Page;

use App\Common\JsonObject\JsonObject;
use App\Page\Component\None\NoneComponent;
use App\Page\Component\Page\PageComponent;
use App\Page\PageBuilder;
use App\Tests\TestClass\FunctionalTestCase;

class PageBuilderTest extends FunctionalTestCase
{
    private PageBuilder $pageBuilder;

    public function setUp(): void
    {
        parent::setUp();
        /** @var PageBuilder $pageBuilder */
        $pageBuilder = $this->container()->get(PageBuilder::class);
        $this->pageBuilder = $pageBuilder;
    }

    public function testPageBuilderBuildsPageObject()
    {
        $json = JsonObject::ofArray([
            'name' => 'PageComponent',
            'children' => [
                ['name' => 'NoneComponent'],
                ['name' => 'NoneComponent'],
            ],
        ]);

        $builtPage = $this->pageBuilder->build($json);

        $this->assertInstanceOf(PageComponent::class, $builtPage);
    }

    public function testPageBuilderBuildsNothingOnInvalidJson()
    {
        $json = JsonObject::ofArray([
            'when-name-missing-component-is-invalid' => 'PageComponent',
            'children' => [
                ['name' => 'NoneComponent'],
                ['name' => 'NoneComponent'],
            ],
        ]);

        $builtPage = $this->pageBuilder->build($json);

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

        $builtPage = $this->pageBuilder->build($json);

        $this->assertInstanceOf(PageComponent::class, $builtPage);
    }

    public function testPageBuilderWorksWithNestedModel()
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

        $builtPage = $this->pageBuilder->build($json);

        $this->assertInstanceOf(PageComponent::class, $builtPage);
        $this->assertEquals($json->getArray(), $builtPage->jsonSerialize());
    }
}
