<?php


namespace App\Tests\Page\Component;

use App\Common\JsonObject\JsonObject;
use App\Page\Component\ComponentFactoryProvider;
use App\Page\Component\Html\HtmlComponent;
use App\Page\Component\None\NoneComponent;
use App\Page\Component\ThreeColumns\ThreeColumnsComponent;
use App\Tests\TestClass\FunctionalTestCase;

class ThreeColumnComponentTest extends FunctionalTestCase
{
    protected ComponentFactoryProvider $provider;

    public function setUp(): void
    {
        parent::setUp();
        /** @var ComponentFactoryProvider $provider */
        $provider = $this->container()->get(ComponentFactoryProvider::class);
        $this->provider = $provider;
    }

    public function testCreatesValidColumnsComponent()
    {
        $json = JsonObject::ofArray([
            'name' => 'ThreeColumnsComponent',
            'column1' => ['name' => 'NoneComponent'],
            'column2' => ['name' => 'NoneComponent'],
            'column3' => ['name' => 'NoneComponent'],
        ]);

        $component = $this->provider
            ->provide('ThreeColumnsComponent')
            ->build($this->provider, $json);

        $this->assertInstanceOf(ThreeColumnsComponent::class, $component);
    }

    public function testCreatesValidColumnsComponentWhenChildrenMissing()
    {
        $json = JsonObject::ofArray([
            'name' => 'ThreeColumnsComponent',
            'column3' => ['name' => 'NoneComponent'],
        ]);

        $component = $this->provider
            ->provide('ThreeColumnsComponent')
            ->build($this->provider, $json);

        $this->assertInstanceOf(ThreeColumnsComponent::class, $component);
    }

    public function testJsonEncodingWorks()
    {
        $json = JsonObject::ofArray([
            'name' => 'ThreeColumnsComponent',
            'column1' => ['name' => 'NoneComponent'],
            'column2' => ['name' => 'NoneComponent'],
            'column3' => ['name' => 'NoneComponent'],
        ]);

        $component = $this->provider
            ->provide('ThreeColumnsComponent')
            ->build($this->provider, $json);

        $this->assertEquals($json->getArray(), $component->jsonSerialize());
    }

    public function testRendersPageWithChildren()
    {
        $component = new ThreeColumnsComponent(
            new HtmlComponent('test-content-to-render'),
            new NoneComponent(),
            new NoneComponent()
        );

        $html = $component->render();

        $this->assertStringContainsString('test-content-to-render', $html);
    }
}
