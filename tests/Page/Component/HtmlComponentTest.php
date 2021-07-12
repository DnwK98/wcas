<?php


namespace App\Tests\Page\Component;

use App\Common\JsonObject\JsonObject;
use App\Page\Component\ComponentBuilderProvider;
use App\Page\Component\Html\HtmlComponent;
use App\Tests\TestClass\FunctionalTestCase;

class HtmlComponentTest extends FunctionalTestCase
{
    protected ComponentBuilderProvider $provider;

    public function setUp(): void
    {
        parent::setUp();
        /** @var ComponentBuilderProvider $provider */
        $provider = $this->container()->get(ComponentBuilderProvider::class);
        $this->provider = $provider;
    }

    public function testCreatesValidHtmlComponent()
    {
        $json = JsonObject::ofArray([
            'name' => 'HtmlComponent',
            'content' => 'any html content'
        ]);

        $component = $this->provider
            ->provide('HtmlComponent')
            ->build($this->provider, $json);

        $this->assertInstanceOf(HtmlComponent::class, $component);
    }

    public function testJsonEncodingWorks()
    {
        $json = JsonObject::ofArray([
            'name' => 'HtmlComponent',
            'content' => 'any html content'
        ]);

        $component = $this->provider
            ->provide('HtmlComponent')
            ->build($this->provider, $json);

        $this->assertEquals($json->getArray()['name'], $component->jsonSerialize()['name']);
        $this->assertEquals($json->getArray()['content'], $component->jsonSerialize()['content']);
    }

    public function testRenderHtml()
    {
        $component = new HtmlComponent('<html>');
        $html = $component->render();

        $this->assertTrue(false !== strpos($html, '<html>'));
    }
}
