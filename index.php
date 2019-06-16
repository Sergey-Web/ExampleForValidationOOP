<?php

declare(strict_types = 1);

interface IValidation {};

class ValidationUser implements IValidation
{
    public function name($name): bool
    {
        if (!is_string($name)) {
            throw new Exception('failed name');
        }

        return true;
    }

    public function lastName($lastName): bool
    {
        if (!is_string($lastName)) {
            throw new Exception('failed last name');
        }

        return true;
    }

    public function email($email): bool
    {
        if (!is_string($email)) {
            throw new Exception('failed email');
        }

        return true;
    }
}

class Validation
{
    private $validations = [
        'user' => ValidationUser::class,
    ];

    /**
     * Validation
     */
    private $validation;

    public function __construct(string $validation)
    {
        if (!array_key_exists($validation, $this->validations)) {
            throw new MyOtherException('No such validation class');
        }

        $this->validation = new $this->validations[$validation]();
    }

    public function check(): IValidation
    {
        return $this->validation;
    }
}

class User
{
    /**
     * Validation
     */
    private $valid;

    private $data;

    public function __construct(array $data, IValidation $valid)
    {
        $this->valid = $valid;
        $this->data = $this->checkData($data);
    }

    public function getName(): string
    {
        return $this->data['name'];
    }

    public function getLastName(): string
    {
        return $this->data['lastName'];
    }

    public function getEmail(): string
    {
        return $this->data['email'];
    }

    public function toArray(): array
    {
        return [
            $this->getName(),
            $this->getLastName(),
            $this->getEmail(),
        ];
    }

    private function checkData(array $data): array
    {
        $this->valid->name($data['name']);
        $this->valid->lastName($data['lastName']);
        $this->valid->email($data['email']);

        return $data;
    }
}

$data = ['name' => 'Sergey', 'lastName' => 'Yarmoshuk', 'email' => 'sergey@gmail.com'];
$valid = (new Validation('user'))->check();

try {
    $collection = new User($data, $valid);
    var_dump($collection->toArray());
} catch(Exception $e) {
    echo 'Caught exception: ',  $e->getMessage(), "\n";
}