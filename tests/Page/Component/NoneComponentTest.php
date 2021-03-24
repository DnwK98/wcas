<?php


namespace App\Tests\Page\Component;

use App\Common\JsonObject\JsonObject;
use App\Page\Component\ComponentBuilderProvider;
use App\Page\Component\None\NoneComponent;
use App\Tests\TestClass\FunctionalTestCase;

class NoneComponentTest extends FunctionalTestCase
{
    protected ComponentBuilderProvider $provider;

    public function setUp(): void
    {
        parent::setUp();
        /** @var ComponentBuilderProvider $provider */
        $provider = $this->container()->get(ComponentBuilderProvider::class);
        $this->provider = $provider;
    }

    public function testCreatesValidNoneComponent()
    {
        $json = JsonObject::ofArray([
            'name' => 'NoneComponent',
        ]);

        $component = $this->provider
            ->provide('NoneComponent')
            ->build($this->provider, $json);

        $this->assertInstanceOf(NoneComponent::class, $component);
    }

    public function testCreatesNoneComponentOnMissingAnyField()
    {
        $json = JsonObject::ofArray([
        ]);

        $component = $this->provider
            ->provide('NoneComponent')
            ->build($this->provider, $json);

        $this->assertInstanceOf(NoneComponent::class, $component);
    }

    public function testJsonEncodingWorks()
    {
        $json = JsonObject::ofArray([
            'name' => 'NoneComponent',
        ]);

        $component = $this->provider
            ->provide('NoneComponent')
            ->build($this->provider, $json);

        $this->assertEquals($json->getArray(), $component->jsonSerialize());
    }

    public function testRenderNone()
    {
        $component = new NoneComponent();
        $html = $component->render();

        $this->assertEmpty($html);
    }
}