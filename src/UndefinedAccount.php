<?php

declare(strict_types = 1);

namespace byrokrat\banking;

/**
 * Account number implementation for accounts not belonging to a bank
 */
class UndefinedAccount implements AccountNumber
{
    use Formatter\FormattableTrait;

    /**
     * @var string
     */
    private $raw;

    /**
     * @var string Account clearing number
     */
    private $clearing;

    /**
     * @var string Check digit of the clearing number
     */
    private $clearingCheckDigit;

    /**
     * @var string Account serial number
     */
    private $serial;

    /**
     * @var string Check digit
     */
    private $checkDigit;

    public function __construct(string $raw, string $clearing, string $clearingCheck, string $serial, string $check)
    {
        $this->raw = $raw;
        $this->clearing = $clearing;
        $this->clearingCheckDigit = $clearingCheck;
        $this->serial = $serial;
        $this->checkDigit = $check;
    }

    public function getBankName(): string
    {
        return '';
    }

    public function getRawNumber(): string
    {
        return $this->raw;
    }

    public function getClearingNumber(): string
    {
        return $this->clearing;
    }

    public function getClearingCheckDigit(): string
    {
        return $this->clearingCheckDigit;
    }

    public function getSerialNumber(): string
    {
        return $this->serial;
    }

    public function getCheckDigit(): string
    {
        return $this->checkDigit;
    }

    public function equals(AccountNumber $account, bool $strict = false): bool
    {
        $has = function (string $number): bool {
            return $number !== '';
        };

        return (!$this->getBankName() || $this->getBankName() == $account->getBankName())
            && $this->getClearingNumber() == $account->getClearingNumber()
            && $this->getSerialNumber() == $account->getSerialNumber()
            && $this->getCheckDigit() == $account->getCheckDigit()
            && !(($has($this->getClearingCheckDigit()) xor $has($account->getClearingCheckDigit())) && $strict)
            && (
                !$has($this->getClearingCheckDigit())
                || !$has($account->getClearingCheckDigit())
                || $this->getClearingCheckDigit() == $account->getClearingCheckDigit()
            );
    }

    protected function getFormattable(): AccountNumber
    {
        return $this;
    }
}
