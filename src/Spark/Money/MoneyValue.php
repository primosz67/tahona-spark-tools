<?php


namespace Spark\Money;


use Spark\Utils\Asserts;

class MoneyValue {

    private $value;
    private $currency;

    public function __construct(float $value, string $currency) {
        $this->value = $value;
        $this->currency = $currency;
    }


    public function add(MoneyValue $moneyValue): MoneyValue {
        $this->checkArg($moneyValue);
        $newValue = $this->value + $moneyValue->getValue();
        return new MoneyValue($newValue, $this->getCurrency());
    }

    public function sub(MoneyValue $moneyValue): MoneyValue {
        $this->checkArg($moneyValue);
        $newValue = $this->value - $moneyValue->getValue();
        return new MoneyValue($newValue, $this->getCurrency());
    }

    public function div(float $value): MoneyValue {
        $newValue = $this->value / $value;
        return new MoneyValue($newValue, $this->getCurrency());
    }

    public function pow(float $value): MoneyValue {
        $newValue = $this->value * $value;
        return new MoneyValue($newValue, $this->getCurrency());
    }


    public function getCurrency(): string {
        return $this->currency;
    }

    public function getValue(): float {
        return $this->value;
    }

    /**
     * @param MoneyValue $moneyValue
     */
    private function checkArg(MoneyValue $moneyValue): void {
        Asserts::checkArgument($this->currency === $moneyValue->getCurrency(), "Cannot use diffrent currency");
    }


}