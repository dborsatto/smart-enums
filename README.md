# Smart Enums - A dumb way to use enums in PHP

[![Packagist](https://img.shields.io/packagist/v/dborsatto/smart-enums.svg)](https://packagist.org/packages/dborsatto/smart-enums)
[![PHP version](https://img.shields.io/packagist/php-v/dborsatto/smart-enums.svg)](https://packagist.org/packages/dborsatto/smart-enums)
[![Packagist](https://img.shields.io/github/license/dborsatto/smart-enums.php.svg)](https://packagist.org/packages/dborsatto/smart-enums)

`dborsatto/smart-enums` is a PHP library that allows you to use enums in PHP without having to wait for version 8.1 It provides integration with Doctrine, so you can use enum objects in your entities, and with Symfony forms.

## Installation

As with any Composer package, run the CLI command to require the library in your application:

```bash
composer require dborsatto/smart-enums
```

## Getting started

This library is built around the need of having a defined set of values available, and some sort of description for each value. We find ourselves often in a situation where a property's value must be limited to a given set of options (and that's an enum), but those options are really internal representations of what the user in our applications will see as a more informative, descriptive message.

Let's use an example: an order's status can be either open, shipped, or delivered. The enum in this situation has clearly these three possible values. The problem is, when showing a message to the user with the current status, you need some sort of conversion from the string `'open'` to some text that can be used in a given context. Furthermore, if you really think about it, at the end of the day `'open'` is just an internal representation of an enum and you only care about it _being open_ and not really about _using the `'open'` string to define the status_.

This library is built around the concept that every possibile value for an enum will have some sort of textual description, which can just be a symbol used in an internationalization process, and methods to verify internal status and apply transactions (and more).

At the core of this library, there are an interface and an abstract class which implements this interface. Your job is to extend the abstract class and implement the only method it requires you to create. Let's have an example using the order status from earlier.

```php
class OrderStatus extends \DBorsatto\SmartEnums\AbstractEnum
{
    // First is a list of all possible values, defined as constants
    private const STATUS_OPEN = 'open';
    private const STATUS_SHIPPED = 'shipped';
    private const STATUS_DELIVERED = 'delivered';

    // In this example, the text representation is implemented in a way
    // that can be easily fed into an internationalization system
    // for easy translation, but if you don't need that you can use actual text
    private const STATUSES = [
        self::STATUS_OPEN => 'order_status.open',
        self::STATUS_SHIPPED => 'order_status.shipped',
        self::STATUS_DELIVERED => 'order_status.delivered',
    ];

    // This is the only method you will *need* to implement
    // You need to return an array with available options as keys,
    // and their text representation as values
    protected static function getValues(): array
    {
        return self::STATUSES;
    }
    
    // Even though you could have public constants and create enums using
    // OrderStatus::fromValue(OrderStatus::STATUS_OPEN), we think that's not the right way.
    // We see the constant as an internal representation of the possible value,
    // but the user does not need to be aware of this.
    // Also, from a purely formal point of value, `::fromValue()` can throw an exception
    // if the given value is not available, but calling the method using the constant
    // you are sure that the status is available, yet you still need to handle the exception.
    // By implementing named constructors, you can keep the visibility to private,
    // and there is no need to handle meaningless exceptions.
    public static function open(): self
    {
        return self::newInstance(self::STATUS_OPEN);
    }
    
    public static function shipped(): self
    {
        return self::newInstance(self::STATUS_SHIPPED);
    }
    
    public static function delivered(): self
    {
        return self::newInstance(self::STATUS_DELIVERED);
    }
    
    public function isDelivered(): bool
    {
        return $this->value === self::STATUS_DELIVERED;
    }
    
    public function canBeShipped(): bool
    {
        return $this->value === self::STATUS_OPEN;
    }
    
    public function canBeDelivered(): bool
    {
        return $this->value === self::STATUS_SHIPPED;
    }
    
    /**
     * @throws OrderStatusException
     */
    public function ship(): self
    {
        if (!$this->canBeShipped()) {
            // We recommend creating your own exceptions
            throw OrderStatusException::orderCannotBeShipped();
        }
        
        return self::shipped();
    }
    
    /**
     * @throws OrderStatusException
     */
    public function deliver(): self
    {
        if (!$this->canBeDelivered()) {
            throw OrderStatusException::orderCannotBeDelivered();
        }
        
        return self::delivered();
    }
}

// Elsewhere
$status = OrderStatus::open();
// Will return order_status.open, as defined in the STATUSES constant
echo $status->getDescription();
// ...
try {
    $shippedStatus = $status->ship();
} catch (OrderStatusException $exception) {
    // ...
}
```

This is quite a lot of boilerplate, especially considering the magic provided by other libraries such as `myclabs/php-enum`, where you don't need to write half as much code. But this is intentional, because we don't like magic and would rather have things that are a bit longer but are also explicit. That's why this library is called _smart enums_: it's smart because everything is designed to be dumb and make as few assumptions as possible.

You can add as many methods as you need. The great thing about this is that your enums will be fully self-aware and contain the logic they need. In this example the statuses are just three and their relationship is clear, but we have situations with a dozen possible options and transitions are complex. You can code anything in the enum, and the logic will be fully encapsulated. 

### Use within entities

The main benefit of using an enum that contains logic is when it becomes part of your entities:

```php
class Order
{
    // ...

    /**
     * @var OrderStatus
     */
    private $status;
    
    // ...

    public function __construct()
    {
        $this->status = OrderStatus::open();
    }
    
    // ...
    
    /**
     * @throws OrderStatusException 
     */
    public function ship(): void
    {
        $this->status = $this->status->ship();
    }
    
    /**
     * @throws OrderStatusException 
     */
    public function delivered(DateTimeImmutable $deliveryDate): void
    {
        $this->status = $this->status->deliver();
        $this->deliveryDate = $deliveryDate;
    }
}
```

In this example, all logic regarding order status will be encapsulated in the enum, and the entity will be able to access it and behave accordingly.

In order to ease this integration within entities, we created a bridge with Doctrine that allows you to easily add custom types so that instead of a string, an enum object will be created.

There are two steps required for you to use enums with Doctrine: create a custom type, and tell Doctrine about it. The first step is where this library helps you:

```php
class OrderStatusType extends \DBorsatto\SmartEnums\Bridge\Doctrine\Type\AbstractEnumType
{
    public const NAME = 'order_status_type';

    protected function getEnumClass(): string
    {
       return OrderStatus::class;
    }

    public function getName(): string
    {
        return self::NAME;
    }
}
```

By extending `AbstractEnumType`, all conversion processes will be handled for you. If in your configuration you declared the type as nullable, `null` values will be properly handled for you.

The second step is to let Doctrine know about your custom type. We use Symfony so we add the proper configuration to the `doctrine.dbal.types` section (see the [reference configuration](https://symfony.com/doc/current/reference/configuration/doctrine.html) for more details). If you are using vanilla Doctrine, you must call `Doctrine\DBAL\Types\Type::addType()` as explained in the [official docs](https://www.doctrine-project.org/projects/doctrine-orm/en/2.8/cookbook/custom-mapping-types.html).

After having set up the type, you can configure you entity to use it. If you are using annotations, your code will look like this:

```php
class Order
{
    // ...

    /**
     * @var OrderStatus
     *
     * @ORM\Column(type="enum_order_status")
     */
    private $status;
}
```

### Integration with Symfony forms

As we already mentioned, we use Symfony. This meant that we had to find a way to make enums work with forms, and for this reason we also included a form type that is ready to use:

```php

use DBorsatto\SmartEnums\Bridge\Symfony\Form\Type\EnumType;

class OrderType extends \Symfony\Component\Form\AbstractType
{
    public function buildForm(FormBuilderInterface $builder,array $options)
    {
        // This example is not the best because ideally you would transition
        // an order status manually by calling an entity method,
        // but sometimes you just have to let users pick an option 
        $builder->add('orderStatus', EnumType::class, [
            'enum_class' => OrderStatus::class,
            'label' => 'Status',
        ]);
    }
}
```

This will give you a `ChoiceType` input will all available options. If you need to restrict the selection of possible choices, you can pass the `choices` value to the configuration array with a list of available objects for the user to choose.

### Integration with Symfony validator

This library ships with a constraint you can use with Symfony's validator. It requires the `enumClass` parameter, and optionally an error `message`.

```php

/** @var \Symfony\Component\Validator\Validator\ValidatorInterface $validator */
$violations = $validator->validate($value, [
    new \DBorsatto\SmartEnums\Bridge\Symfony\Validator\EnumConstraint(['enumClass' => Enum::class]),
]);
```

The constraint works just like any other Symfony constraint, which means you can also use it as an annotation.

### Utilities

This library ships with a couple of utility classes that you probably will not need in everyday use, but are still available for you.

```php
// EnumFactory acts as a wrapper for when you only have the enum class available,
// but you need guarantees about it being a valid enum
$factory = new \DBorsatto\SmartEnums\EnumFactory(OrderStatus::class);
// At this point, all methods just forward to the actual enum methods
$factory->fromValue('...');
$factory->fromValues([...]);
$factory->all();

// Sometimes you just need to get the enum value and description as an key => value array
// Because this is usually a formatting problem, instead of breaking encapsulation
// and making the enum constant public, use this formatter
$formatter = new \DBorsatto\SmartEnums\EnumFormatter(OrderStatus::class);

// These methods both return array<string, string> values
$formatter->toKeyValueList();
$formatter->toValueKeyList();
```

### An important note about enum identity

Because two enums with the same value are conceptually the same, we built `AbstractEnum` to make sure that instances are reused. This means that `OrderStatus::open() === OrderStatus::open()` will evaluate to true.

For this to work, you need to remember two things:
- For technical reasons, this can't be enforced at an interface level. This is why we recommend you always extend `AbstractEnum` and never implement `EnumInterface` directly.
- Inside an enum, you must **never** modify `$this->value`. State transitions must always return a new enum and the must never update the current enum. Unfortunately PHP does not support read only properties and as we said, we don't like magic solutions that would let use work around this, so we trust users to be smart and not mess this up.

## License

This repository is published under the [MIT](LICENSE) license.
