<?php

declare(strict_types=1);


namespace App\Tests\Common\JsonObject;


use App\Common\JsonObject\Exception\JsonParseException;
use App\Common\JsonObject\JsonObject;
use PHPUnit\Framework\TestCase;

class JsonObjectTest extends TestCase
{
    public function testCreatesObjectFromJson()
    {
        $json = json_encode(['a'=> 1, 'b' => 2.2]);

        $object = JsonObject::ofJson($json);
        $this->assertEquals(1, $object->getInt('a'));
        $this->assertEquals(2.2, $object->getFloat('b'));
    }

    public function testThrowsOnInvalidJson()
    {
        $invalidJson = json_encode(['a'=> 1, 'b' => 2.2]) . "sth";

        $this->expectException(JsonParseException::class);

        JsonObject::ofJson($invalidJson);
    }

    public function testJsonObjectGetString()
    {
        $object = $this->getTestJsonObject();

        $this->assertSame('some string', $object->getString('string'));
        $this->assertSame('3.14', $object->getString('float'));
        $this->assertSame('4', $object->getString('int'));
        $this->assertSame('1', $object->getString('bool_true'));
        $this->assertSame('', $object->getString('bool_false'));
    }

    public function testJsonObjectGetInt()
    {
        $object = $this->getTestJsonObject();

        $this->assertSame(0, $object->getInt('string'));
        $this->assertSame(1, $object->getInt('string_numeric'));
        $this->assertSame(1, $object->getInt('string_numeric_comma'));
        $this->assertSame(3, $object->getInt('float'));
        $this->assertSame(9, $object->getInt('float_round'));
        $this->assertSame(4, $object->getInt('int'));
        $this->assertSame(1, $object->getInt('bool_true'));
        $this->assertSame(0, $object->getInt('bool_false'));
    }

    public function testJsonObjectGetFloat()
    {
        $object = $this->getTestJsonObject();

        $this->assertSame(0.0, $object->getFloat('string'));
        $this->assertSame(1.23, $object->getFloat('string_numeric'));
        $this->assertSame(1.0, $object->getFloat('string_numeric_comma'));
        $this->assertSame(3.14, $object->getFloat('float'));
        $this->assertSame(9.99, $object->getFloat('float_round'));
        $this->assertSame(4.0, $object->getFloat('int'));
        $this->assertSame(1.0, $object->getFloat('bool_true'));
        $this->assertSame(0.0, $object->getFloat('bool_false'));
    }
    public function testJsonObjectGetBool()
    {
        $object = $this->getTestJsonObject();

        $this->assertSame(true, $object->getBool('string'));
        $this->assertSame(true, $object->getBool('float'));
        $this->assertSame(true, $object->getBool('assoc'));

        $this->assertSame(false, $object->getBool('array_empty'));
        $this->assertSame(false, $object->getBool('string_empty'));

        $this->assertSame(null, $object->getBool('empty'));
    }

    public function testGetArray()
    {
        $object = $this->getTestJsonObject();

        $assocArray = $object->getArray('assoc');
        $array = $object->getArray('array');

        $this->assertSame($assocArray, [
            'a1' => 1,
            'a2' => 2,
            'a3' => 3,
            'a4' => 4,
            'a5' => 5,
        ]);
        $this->assertSame($array,[
            'a', 'b', 'c', 'd', 'e'
        ]);
    }

    public function testJsonObjectGetObject()
    {
        $object = $this->getTestJsonObject();

        $value = $object->getJson('assoc')->getJson('a1')->getInt();
        $this->assertSame(1, $value);
    }

    public function testGetDate()
    {
        $object = $this->getTestJsonObject();

        $this->assertEquals($object->getDateTime('date')->getTimestamp(),
            (new \DateTime('2000-01-01 12:00:00'))->getTimestamp());

        $this->assertNull($object->getDateTime('string'));
    }

    public function testIterator()
    {
        $object = $this->getTestJsonObject();

        foreach ($object->getJson('assoc') as $key => $value){
            $this->assertArrayHasKey($key, array_flip(['a1', 'a2', 'a3', 'a4', 'a5']));
            $this->assertInstanceOf(JsonObject::class, $value);
        }
    }

    public function testGetValueUsingDotKey()
    {
        $object = $this->getTestJsonObject();

        $this->assertSame(1, $object->getInt('assoc.a1'));
        $this->assertSame(2, $object->getInt('assoc.a2'));

        $this->assertSame('a', $object->getString('array.0'));
        $this->assertSame('b', $object->getString('array.1'));
    }

    public function testIterateOverInvalidJson()
    {
        $object = $this->getTestJsonObject();

        foreach ($object->getJson('string') as $key => $value){
            $this->fail('Should not iterate though invalid object');
        }
        foreach ($object->getJson('string_empty') as $key => $value){
            $this->fail('Should not iterate though invalid object');
        }
        foreach ($object->getJson('float') as $key => $value){
            $this->fail('Should not iterate though invalid object');
        }
        foreach ($object->getJson('empty') as $key => $value){
            $this->fail('Should not iterate though invalid object');
        }

        $this->assertTrue(true);
    }

    public function testJsonObjectCanWalkMissingValues()
    {
        $object = $this->getTestJsonObject();

        $missingObject = $object
            ->getJson('assoc')
            ->getJson('a1')
            ->getJson('missing')
            ->getJson('another missing');

        $this->assertSame(null, $missingObject->getInt());
        $this->assertSame(null, $missingObject->getString());
        $this->assertSame(null, $missingObject->getFloat());

        $this->assertFalse($missingObject->isset());
    }

    private function getTestJsonObject():JsonObject
    {
        return JsonObject::ofArray([
            'string' => 'some string',
            'string_empty' => '',
            'string_numeric' => '1.23',
            'string_numeric_comma' => '1,23',
            'float' => 3.14,
            'float_round' => 9.99,
            'int' => 4,
            'assoc' => [
                'a1' => 1,
                'a2' => 2,
                'a3' => 3,
                'a4' => 4,
                'a5' => 5,
            ],
            'array' => [
                'a', 'b', 'c', 'd', 'e'
            ],
            'array_empty' => [],
            'bool_true' => true,
            'bool_false' => false,
            'empty' => null,
            'date' => '2000-01-01 12:00:00',
        ]);
    }
}
