# Builder

This library provides a base class for helping implement the Builder Pattern. This is handy for building objects from 
JSON data being delivered from APIs. It is also handy for converting one class to another. 

From [Wikipedia](https://en.wikipedia.org/wiki/Builder_pattern):

The builder pattern is a design pattern that provides a flexible solution to various object creation problems in 
object-oriented programming. The builder pattern separates the construction of a complex object from its representation.

```php
use Moonspot\Builder;
use Example\MyObject;

class MyBuilder extends Builder {

    public function create(array|object $data): object {
        // need an array to work with
        if(is_object($data)) {
            $data = (array)$data;
        }

        $obj = new MyObject();
        
        // setValue handles nulls and isset issues
        $this->setValue($obj, 'id', $data);
        
        // setValue can also look for multiple keys
        // If either name or description is set in the data array
        // it will be set to name in the object.
        $this->setValue($obj, 'name', $data, ['description'])
    }
}

$object = MyBuilder::build([
    'id'   => 12345, 
    'name' => 'Some name',
]);
```
## See Also

To make the objects easier to work with, use [Value Objects](https://github.com/brianlmoon/value-objects).
