<?php


namespace App\Tests\Page\Component;

use App\Common\JsonObject\JsonObject;
use App\Page\Component\ComponentBuilderProvider;
use App\Page\Component\Html\HtmlComponent;
use App\Page\Component\Page\PageComponent;
use App\Tests\TestClass\FunctionalTestCase;

class PageComponentTest extends FunctionalTestCase
{
    protected ComponentBuilderProvider $provider;

    public function setUp(): void
    {
        parent::setUp();
        /** @var ComponentBuilderProvider $provider */
        $provider = $this->container()->get(ComponentBuilderProvider::class);
        $this->provider = $provider;
    }

    public function testCreatesValidPaAgeComponent()
    {
        $json = JsonObject::ofArray([
            'name' => 'PageComponent',
        ]);

        $component = $this->provider
            ->provide('PageComponent')
            ->build($this->provider, $json);

        $this->assertInstanceOf(PageComponent::class, $component);
    }

    public function testCreatesPageComponentWithChildren()
    {
        $json = JsonObject::ofArray([
            'name' => 'PageComponent',
            'children' => [
                ['name' => 'NoneComponent'],
                [
                    'name' => 'HtmlComponent',
                    'content' => '<html></html>'
                ]
            ]
        ]);

        $component = $this->provider
            ->provide('PageComponent')
            ->build($this->provider, $json);

        $this->assertInstanceOf(PageComponent::class, $component);
    }

    public function testJsonEncodingWorks()
    {
        $json = JsonObject::ofArray([
            'name' => 'PageComponent',
            'children' => [
                ['name' => 'NoneComponent'],
                ['name' => 'NoneComponent'],
                ['name' => 'NoneComponent'],
                ['name' => 'NoneComponent'],
                ['name' => 'NoneComponent'],
            ]
        ]);

        $component = $this->provider
            ->provide('PageComponent')
            ->build($this->provider, $json);

        $this->assertEquals($json->getArray(), $component->jsonSerialize());
    }

    public function testRendersPage()
    {
        $component = new PageComponent([]);
        $html = $component->render();

        $this->assertStringContainsString('html', $html);
        $this->assertStringContainsString('<body>', $html);
        $this->assertStringContainsString('div', $html);
    }

    public function testRendersPageWithChildren()
    {
        $component = new PageComponent([new HtmlComponent('test-content-to-render')]);

        $html = $component->render();

        $this->assertStringContainsString('test-content-to-render', $html);
    }
}